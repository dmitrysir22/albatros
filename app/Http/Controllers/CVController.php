<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\CandidateProfile;
use App\Models\CandidateExperience;
use App\Models\CandidateEducation;
use Carbon\Carbon;

class CVController extends Controller
{
    public function upload(Request $request)
    {

        $request->validate([
            'cv_file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,rtf',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/rtf',
                'max:10240',
            ],
        ]);

        $path = $request->file('cv_file')->store('cvs', 'public');
        $extension = $request->file('cv_file')->getClientOriginalExtension();
        $mimeType = $request->file('cv_file')->getMimeType();
        
        $fileContents = "";
        $isBinaryData = false;

        // 1. Извлекаем контент
        if ($extension == 'pdf') {
            $fileData = base64_encode(file_get_contents(storage_path("app/public/$path")));
            $isBinaryData = true;
        } else {
            try {
                $filePath = storage_path("app/public/$path");
                $reader = \PhpOffice\PhpWord\IOFactory::createReader(); 
                if ($extension === 'doc') {
                    $reader = \PhpOffice\PhpWord\IOFactory::createReader('MsDoc');
                }

                $phpWord = $reader->load($filePath);
                foreach($phpWord->getSections() as $section) {
                    foreach($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $fileContents .= $element->getText() . " ";
                        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach($element->getElements() as $textElement) {
                                if (method_exists($textElement, 'getText')) {
                                    $fileContents .= $textElement->getText();
                                }
                            }
                            $fileContents .= " ";
                        }
                    }
                }
                
                // fallback на бинарный парсинг, если текст не вытянулся
        if (strlen($fileContents) < 50 || preg_match('/[\x{4e00}-\x{9fa5}]/u', $fileContents)) {
            $fileContents = $this->extractTextFromBinaryDoc($filePath);
        }
		} catch (\Exception $e) {
                $fileContents = $this->extractTextFromBinaryDoc(storage_path("app/public/$path"));
            }
        }

        // 2. Настройка Gemini 3 Flash
        $modelId = "gemini-3-flash-preview"; 
		$modelId = "gemini-2.5-flash";
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelId}:generateContent?key=" . env('GEMINI_API_KEY');

        $promptText = "Act as an expert Legal Recruitment AI. Extract information from this CV into a strictly structured JSON.
        
        Rules:
        - Name: Convert FIRST_NAME and LAST_NAME to Title Case (e.g., 'Christopher Wood').
        - Role Level: (Trainee, NQ, Associate, Senior Associate, Counsel, Partner, In-House Counsel, Other).
        - Professional Certs: Look for 'Referee', 'LPC', 'SQE', 'Solicitor', 'Bar' and put them in 'professional_certs' array.
        - Languages: Extract all mentioned languages (French, Latin, etc.) into 'languages' array.
        - If you see languages (like French or Latin) or professional qualifications (like Referee or Solicitor status) anywhere in the text, extract them into the 'languages' and 'professional_certs' arrays even if they are listed within the school/university section.		        
        - If you find a standalone professional qualification (like 'Referee', 'LPC', or 'Solicitor status') that is NOT tied to a specific university, create a NEW entry in the 'education' array. Use the awarding body (e.g., 'English Football Association') as 'institution' and put the title in 'professional_certs'.
        - Even if a qualification is mentioned in the 'Work Experience' or 'Summary' section, it MUST be duplicated into the 'education' array if it's a certificate or professional status.		
        JSON Structure:
        {
            \"personal\": { \"first_name\": \"\", \"last_name\": \"\", \"email\": \"\", \"phone\": \"\", \"location_city\": \"\", \"location_country\": \"\", \"linkedin_url\": \"\" },
            \"qualification\": { \"role_level\": \"\", \"jurisdictions\": [], \"admission_status\": \"\", \"pqe_years\": 0, \"right_to_work\": \"\" },
            \"expertise\": { \"primary_practice_area\": \"\", \"secondary_practice_areas\": [], \"industry_sectors\": [], \"skills_tags\": [], \"profile_summary\": \"\" },
            \"experience\": [ { \"organisation\": \"\", \"role_title\": \"\", \"experience_type\": \"\", \"location\": \"\", \"start_date\": \"YYYY-MM-DD\", \"end_date\": \"YYYY-MM-DD\", \"is_current\": false, \"practice_area\": \"\", \"description\": \"\" } ],
            \"education\": [ { \"qualification_type\": \"\", \"institution\": \"\", \"grade\": \"\", \"professional_certs\": [], \"languages\": [] } ],
            \"ai_hidden\": { \"keywords\": [], \"seniority_score\": 0, \"practice_confidence\": 0, \"transactional_contentious\": \"\", \"regulatory_tags\": [] }
        }
        Return ONLY pure JSON.";

        $parts = [['text' => $promptText]];
        if ($isBinaryData) {
            $parts[] = ['inline_data' => ['mime_type' => $mimeType, 'data' => $fileData]];
        } else {
            $parts[] = ['text' => "CV CONTENT:\n" . $fileContents];
        }


        $response = Http::post($apiUrl, [
            'contents' => [['parts' => $parts]],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0
            ]
        ]);

        if ($response->successful()) {
            $data = json_decode($response->json('candidates.0.content.parts.0.text'), true);
			
			//print_R($parts);
			//print_r($data);
			//die;
            if (!$data) return back()->with('error', 'AI parse error.');

            return DB::transaction(function () use ($data, $path) {
                // Нормализация регистра
                $firstName = mb_convert_case($data['personal']['first_name'] ?? '', MB_CASE_TITLE, "UTF-8");
                $lastName = mb_convert_case($data['personal']['last_name'] ?? '', MB_CASE_TITLE, "UTF-8");

                $profile = CandidateProfile::create([
                    'user_id' => auth()->id(),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $data['personal']['phone'] ?? null,
                    'location_city' => $data['personal']['location_city'] ?? null,
                    'location_country' => $data['personal']['location_country'] ?? null,
                    'linkedin_url' => $data['personal']['linkedin_url'] ?? null,
                    'role_level' => $data['qualification']['role_level'] ?? null,
                    'jurisdictions' => $data['qualification']['jurisdictions'] ?? [],
                    'admission_status' => $data['qualification']['admission_status'] ?? null,
                    'pqe_years' => $data['qualification']['pqe_years'] ?? 0,
                    'right_to_work' => $data['qualification']['right_to_work'] ?? null,
                    'primary_practice_area' => $data['expertise']['primary_practice_area'] ?? null,
                    'secondary_practice_areas' => $data['expertise']['secondary_practice_areas'] ?? [],
                    'industry_sectors' => $data['expertise']['industry_sectors'] ?? [],
                    'skills_tags' => $data['expertise']['skills_tags'] ?? [],
                    'profile_summary' => $data['expertise']['profile_summary'] ?? null,
                    'ai_keywords' => $data['ai_hidden']['keywords'] ?? [],
                    'ai_seniority_score' => $data['ai_hidden']['seniority_score'] ?? null,
                    'ai_practice_confidence' => $data['ai_hidden']['practice_confidence'] ?? null,
                    'ai_transactional_contentious' => $data['ai_hidden']['transactional_contentious'] ?? null,
                    'ai_regulatory_tags' => $data['ai_hidden']['regulatory_tags'] ?? [],
                    'cv_path' => $path,
                    'cv_parsed_at' => now(),
                ]);

                if (!empty($data['experience'])) {
                    foreach ($data['experience'] as $exp) {
                        CandidateExperience::create([
                            'candidate_profile_id' => $profile->id,
                            'organisation' => $exp['organisation'] ?? 'Unknown',
                            'role_title' => $exp['role_title'] ?? 'N/A',
                            'experience_type' => $exp['experience_type'] ?? 'Other',
                            'location' => $exp['location'] ?? null,
                            'start_date' => $this->parseDate($exp['start_date']),
                            'end_date' => $this->parseDate($exp['end_date']),
                            'is_current' => $exp['is_current'] ?? false,
                            'practice_area' => $exp['practice_area'] ?? null,
                            'description' => mb_substr($exp['description'] ?? '', 0, 2000), 
                        ]);
                    }
                }

                if (!empty($data['education'])) {
                    foreach ($data['education'] as $edu) {
                        CandidateEducation::create([
                            'candidate_profile_id' => $profile->id,
                            'qualification_type' => $edu['qualification_type'] ?? 'Other',
                            'institution' => $edu['institution'] ?? 'N/A',
                            'grade' => mb_substr($edu['grade'] ?? '', 0, 1000), // Тот самый фикс
                            'professional_certs' => $edu['professional_certs'] ?? [],
                            'languages' => $edu['languages'] ?? [],
                        ]);
                    }
                }

// В CVController.php
if (!auth()->check()) {
    // Сохраняем ID в сессию — это безопасно, т.к. сессия хранится на сервере
    session(['guest_profile_id' => $profile->id]);
    
    // Возвращаем просто статус успеха без ID
    return response()->json([
        'status' => 'success', 
        'redirect_url' => route('profile.edit') 
    ]);
}

return response()->json([
    'status' => 'success', 
    'redirect_url' => route('profile.edit') 
]);


            });
        }

        return back()->with('error', 'AI could not read the file.');
    }

    private function parseDate($date)
    {
        if (!$date || $date == 'null' || str_contains(strtolower($date), 'present')) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

 private function extractTextFromBinaryDoc($filename) {
	$content = file_get_contents($filename);
    // Просто выкидываем всё нечитаемое, но не делим на строки по 0x0d
    $t = preg_replace("/[^a-zA-Z0-9\s\,\.\-\/\(\)\@\:\?\!\=\%\&\*\+\_\;]/", " ", $content);
    // Убираем лишние пробелы
    $t = preg_replace('/\s\s+/', ' ', $t);
    return $t;
}
    public function showUploadForm() { return view('frontend.cv.upload'); }
}