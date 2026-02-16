<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::latest()->paginate(15);
        return view('admin.vacancies.index', compact('vacancies'));
    }

    public function create()
    {
        return view('admin.vacancies.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'required|string',
            'salary_range' => 'nullable|string',
            'description' => 'required',
            'required_skills' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        $data['is_active'] = $request->has('is_active');

        Vacancy::create($data);

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy created!');
    }
	
public function edit(Vacancy $vacancy)
{
    return view('admin.vacancies.form', compact('vacancy'));
}

public function update(Request $request, Vacancy $vacancy)
{
    $data = $request->validate([
        'title'           => 'required|string|max:255',
        'company_name'    => 'required|string|max:255',
        'location'        => 'nullable|string|max:255',
        'type'            => 'required|string',
        'salary_range'    => 'nullable|string',
        'description'     => 'required',
        'required_skills' => 'nullable',
    ]);

    // Обновляем слаг только если заголовок изменился
    if ($vacancy->title !== $request->title) {
        $data['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . time();
    }

    $data['is_active'] = $request->has('is_active');

    $vacancy->update($data);

    return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy updated successfully!');
}	
}