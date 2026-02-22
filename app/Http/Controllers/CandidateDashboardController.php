<?php
namespace App\Http\Controllers;

use App\Models\CandidateProfile;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CandidateDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Получаем профиль (из сессии для гостя или из БД для юзера)
        $profile = null;

        if (Auth::check()) {
            $profile = CandidateProfile::where('user_id', Auth::id())->latest()->first();
        } else {
            $guestId = session('guest_profile_id'); // Используем тот же ключ, что и в CVController
            if ($guestId) {
                $profile = CandidateProfile::find($guestId);
            }
        }
		
        $isGuest = !Auth::check();
        
// 1. Разбиваем Practice Area на слова для более гибкого поиска
    // Например, из "Corporate M&A" сделаем ["Corporate", "M&A"]
    $searchTerms = explode(' ', str_replace(['/', '&', ','], ' ', $profile->primary_practice_area));
    
    $query = \App\Models\Vacancy::where('is_active', 1);

    $query->where(function($q) use ($searchTerms) {
        foreach ($searchTerms as $term) {
            if (strlen($term) > 2) { // Игнорируем предлоги
                $q->orWhere('practice_area', 'LIKE', '%' . trim($term) . '%')
                  ->orWhere('title', 'LIKE', '%' . trim($term) . '%')
                  ->orWhere('keywords', 'LIKE', '%' . trim($term) . '%');
            }
        }
    });

        $matchCount = $query->count();
        
        // Для залогиненных получаем сами вакансии
        $vacancies = $isGuest ? collect() : $query->latest()->take(6)->get();

        return view('frontend.dashboard', [
            'profile' => $profile,
            'isGuest' => $isGuest,
            'matchCount' => $matchCount,
            'vacancies' => $vacancies,
            'cvSummary' => $this->getShortSummary($profile)
        ]);
    }

    private function getShortSummary($profile)
    {
        // Просто берем пару строк из опыта или summary
        return \Str::limit($profile->summary ?? 'Legal professional focused on ' . $profile->primary_practice_area, 150);
    }
	

public function aiMatch(Request $request)
{
    $user = auth()->user();
    
    // Загружаем профиль со всеми связями: опыт, образование
    $profile = \App\Models\CandidateProfile::where('user_id', $user->id)
        ->with(['experiences', 'educations'])
        ->latest()
        ->first();

    if (!$profile) {
        return response()->json(['status' => 'error', 'message' => 'Profile not found.']);
    }

    // 1. Поиск вакансий (расширенный: по области практики и названию)
    $searchTerms = explode(' ', str_replace(['/', '&', ','], ' ', $profile->primary_practice_area));
    $query = \App\Models\Vacancy::where('is_active', 1);
    
    $query->where(function($q) use ($searchTerms) {
        foreach ($searchTerms as $term) {
            if (strlen($term) > 2) {
                $q->orWhere('practice_area', 'LIKE', '%' . trim($term) . '%')
                  ->orWhere('title', 'LIKE', '%' . trim($term) . '%');
            }
        }
    });

    $vacancies = $query->limit(40)->get();

    if ($vacancies->isEmpty()) {
        return response()->json(['status' => 'empty', 'message' => 'No vacancies found.']);
    }

    // 2. Сборка контекста из всех таблиц (Education, Experience, Profile)
    $candidateData = [
        'personal' => [
            'level' => $profile->role_level,
            'pqe' => $profile->pqe_years,
            'jurisdictions' => $profile->jurisdictions, // JSON cast в модели
            'admission' => $profile->admission_status,
            'primary_area' => $profile->primary_practice_area,
            'skills' => $profile->skills_tags
        ],
        'summary' => $profile->profile_summary,
        'experience_history' => $profile->experiences->map(fn($e) => [
            'role' => $e->role_title,
            'firm' => $e->organisation,
            'area' => $e->practice_area,
            'details' => $e->description
        ]),
        'education_history' => $profile->educations->map(fn($ed) => [
            'degree' => $ed->qualification_type,
            'uni' => $ed->institution,
            'certs' => $ed->professional_certs,
            'langs' => $ed->languages
        ])
    ];

    // 3. Данные вакансий
    $vacanciesData = $vacancies->map(fn($v) => [
        'id' => $v->id,
        'title' => $v->title,
        'pqe_req' => $v->pqe_range,
        'area' => $v->practice_area,
        'reqs' => $v->requirements,
        'desc' => $v->summary
    ]);

    // Используем Gemini 2.5 Flash
    $modelId = "gemini-2.5-flash"; 
    $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelId}:generateContent?key=" . env('GEMINI_API_KEY');

    $promptText = "You are a Legal Recruitment AI. Compare this Candidate against these Vacancies.
    
    CANDIDATE:
    " . json_encode($candidateData) . "

    VACANCIES:
    " . json_encode($vacanciesData) . "

    CRITICAL RULES:
    1. Score 0-100 based on PQE match, Jurisdiction, and Practice Area.
    2. If Candidate is NQ but vacancy is 5+ PQE, score must be < 40.
    3. If Practice Area is a total mismatch (e.g. Criminal vs M&A), score < 20.
    4. match_reason: Explain 'WHY' focusing on experience overlap.
    5. gap_analysis: Explain 'WHAT IS MISSING' (e.g. 'Needs more High Court experience').

    Return ONLY a JSON object with a 'matches' array.";

    $response = Http::post($apiUrl, [
        'contents' => [['parts' => [['text' => $promptText]]]],
        'generationConfig' => [
            'responseMimeType' => 'application/json',
            'temperature' => 0.1 // Низкая температура для точности в скоринге
        ]
    ]);

    if ($response->successful()) {
        $aiResponse = $response->json('candidates.0.content.parts.0.text');
        $result = json_decode($aiResponse, true);
        return response()->json(['status' => 'success', 'data' => $result['matches']]);
    }

    return response()->json(['status' => 'error', 'message' => 'Gemini 2.5 Flash failed.'], 500);
}

}