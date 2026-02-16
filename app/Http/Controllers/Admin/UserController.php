<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Получаем всех пользователей, кроме, возможно, текущего админа (опционально)
        // Пагинация по 20 человек — мастхэв для 2026 года
        $users = User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        // Метод для удаления пользователя
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }

public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        'role'  => ['required', 'in:candidate,admin'], // ограничиваем список ролей
        'password' => ['nullable', 'string', 'min:8', 'confirmed'], // пароль не обязателен
    ]);

    // Обновляем основные данные
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->role = $data['role'];

    // Если пароль ввели — хешируем и обновляем
    if ($request->filled('password')) {
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
}
}