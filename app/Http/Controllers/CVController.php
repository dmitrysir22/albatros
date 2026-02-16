<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\CandidateProfile;

class CVController extends Controller
{
    public function upload(Request $request)
    {
		
	//	$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . env('GEMINI_API_KEY');

//$response = Http::get($url);

//dd($response->json());
//die;
        $request->validate([
            'cv_file' => 'required|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);
		

        // 1. Сохраняем файл локально
        $path = $request->file('cv_file')->store('cvs', 'public');
        $fileData = base64_encode(file_get_contents(storage_path("app/public/$path")));
        $mimeType = $request->file('cv_file')->getMimeType();

        // 2. Формируем запрос к Gemini
        // Мы просим его вернуть строго JSON по нашей структуре
        $prompt = "Extract information from this CV into a JSON format. 
        Fields: first_name, last_name, email, phone, location, headline (current role), 
        years_experience (integer), skills (array), 
        experience (array of objects with: company, role, years), 
        education (array), languages (array). 
        Return ONLY pure JSON, no markdown formatting.";

// Используем v1beta и актуальную модель 2.5 Flash
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . env('GEMINI_API_KEY');

$response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post($apiUrl, [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt],
                [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data' => $fileData
                    ]
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'responseMimeType' => 'application/json',
    ]
]);



if ($response->successful()) {
    // 1. Извлекаем строку JSON из ответа ИИ
    $aiRawText = $response->json('candidates.0.content.parts.0.text');
    
    // 2. Декодируем её в ассоциативный массив PHP
    $parsedData = json_decode($aiRawText, true);

    if (!$parsedData) {
        return back()->with('error', 'AI returned invalid JSON structure.');
    }

    // 3. Подготавливаем данные для модели (сопоставляем ключи JSON с базой)
    $profileData = [
        'user_id'          => auth()->id(), // Если юзер вошел
        'first_name'       => $parsedData['first_name'] ?? null,
        'last_name'        => $parsedData['last_name'] ?? null,
        'phone'            => $parsedData['phone'] ?? null,
        'location'         => $parsedData['location'] ?? null,
        'headline'         => $parsedData['headline'] ?? null,
        'years_experience' => (int)($parsedData['years_experience'] ?? 0),
        'skills'           => $parsedData['skills'] ?? [],
        'experience'       => $parsedData['experience'] ?? [],
        'education'        => $parsedData['education'] ?? [],
        'languages'        => $parsedData['languages'] ?? [],
        'cv_path'          => $path, // Путь к файлу, который мы сохранили в начале
        'cv_parsed_at'     => now(),
    ];

  echo '<pre>';
  print_r($profileData);
  die;

    // 4. Сохраняем или обновляем профиль
    // Мы ищем профиль по user_id, если его нет - создаем
    $profile = \App\Models\CandidateProfile::updateOrCreate(
        ['user_id' => auth()->id()], 
        $profileData
    );

        return back()->with('error', 'AI could not read the file. Please try again.');
    }
	}
	
/**
 * Показать страницу загрузки резюме
 */
  public function showUploadForm()
  {
    return view('frontend.cv.upload');
  }	
}