<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CandidateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Валидация
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms'    => ['accepted'],
        ]);

        // 2. Создание пользователя
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. ПРИВЯЗКА ПРОФИЛЯ (Наша новая логика)
        // Проверяем, есть ли в сессии ID профиля, созданного гостем при загрузке CV
        if (session()->has('guest_profile_id')) {
            $profileId = session('guest_profile_id');
            
            $profile = CandidateProfile::find($profileId);
            
            if ($profile) {
                // Обновляем user_id в профиле
                $profile->update([
                    'user_id' => $user->id
                ]);
            }

            // Очищаем сессию, чтобы не привязать профиль повторно
            session()->forget('guest_profile_id');
        }

        event(new Registered($user));
        Auth::login($user);

        // Редирект на верификацию, как ты и просил
        return redirect()->route('verification.notice')
                         ->with('success', 'Registration successful! Your CV profile has been linked to your account.');
    }
}