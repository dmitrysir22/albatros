<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidateProfile;

class ProfileController extends Controller
{
// В ProfileController.php
public function edit()
{
    $profile = null;

    if (auth()->check()) {
        // Если юзер залогинен — берем его профиль
        $profile = CandidateProfile::where('user_id', auth()->id())->latest()->first();
    } else {
        // Если гость — берем строго из сессии
        $guestId = session('guest_profile_id');
        if ($guestId) {
            $profile = CandidateProfile::find($guestId);
        }
    }

    // Если профиль не найден или в сессии пусто — отправляем на загрузку
    if (!$profile) {
        return redirect()->route('cv.upload')
            ->with('error', 'Сессия истекла или профиль не найден. Пожалуйста, загрузите резюме снова.');
    }

    $profile->load(['experiences', 'educations']);
    return view('frontend.profile.edit', compact('profile'));
}

public function update(Request $request)
{
	
$request->validate([
    'profile.first_name' => 'required|string|max:255',
    'profile.last_name'  => 'required|string|max:255',
    'profile.phone'      => 'required|string|max:50',
    'profile.role_level' => 'required', // Critical for NQ/Associate/Partner filters
    'profile.primary_practice_area' => 'required', 
], [
    'profile.first_name.required' => 'Please provide your first name.',
    'profile.role_level.required' => 'Professional seniority level is required for candidate placement.',
    'profile.phone.required' => 'A contact number is required for recruitment updates.',
]);
	
    // 1. Находим профиль так же, как в edit() — через сессию или auth
    $profile = null;
    if (auth()->check()) {
        $profile = CandidateProfile::where('user_id', auth()->id())->latest()->first();
    } else {
        $guestId = session('guest_profile_id');
        $profile = CandidateProfile::find($guestId);
    }

    if (!$profile) {
        return redirect()->route('cv.upload')->with('error', 'Session expired.');
    }

    // 2. Обновляем основные данные профиля
    $profile->update($request->input('profile'));

    // 3. Обновляем опыт (Experience)
    if ($request->has('experiences')) {
        foreach ($request->input('experiences') as $id => $expData) {
            // Обновляем только те записи, которые принадлежат этому профилю (защита)
            $profile->experiences()->where('id', $id)->update([
                'organisation' => $expData['organisation'],
                'role_title'   => $expData['role_title'],
                'start_date'   => $expData['start_date'],
                'end_date'     => $expData['end_date'],
                'description'  => mb_substr($expData['description'], 0, 2000),
            ]);
        }
    }

    // 4. Обновляем образование (Education)
    if ($request->has('educations')) {
        foreach ($request->input('educations') as $id => $eduData) {
            $profile->educations()->where('id', $id)->update([
                'institution'        => $eduData['institution'],
                'qualification_type' => $eduData['qualification_type'],
                'grade'              => mb_substr($eduData['grade'], 0, 1000),
            ]);
        }
    }

    // 5. Идем дальше (например, на страницу поиска вакансий или логина)
return redirect()->route('dashboard')->with('success', 'Profile updated successfully! We have found matches for your expertise.');
}
}