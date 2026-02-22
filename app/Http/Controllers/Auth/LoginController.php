<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Показать форму входа
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка попытки входа
     */
    public function login(Request $request)
    {
        // 1. Валидация данных
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Попытка аутентификации
        // remember — опционально, если добавишь чекбокс "Запомнить меня"
        if (Auth::attempt($credentials, $request->has('remember'))) {
          $user = Auth::user();
          if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your email address is not verified. Please check your inbox.',
            ])->onlyInput('email');
          }

            // Регенерируем сессию для безопасности
            $request->session()->regenerate();

            // Перенаправляем на главную или туда, куда пользователь шел изначально
            return redirect()->intended('/')->with('success', 'You are logged in!');
        }

        // 3. Если данные неверны, возвращаем обратно с ошибкой
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}