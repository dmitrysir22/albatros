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
        $data = $this->validateVacancy($request);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        $data['is_active'] = $request->has('is_active');
        
        // Обработка массивов (превращаем строки с запятыми в массив)
        $data['required_skills'] = $this->processTags($request->required_skills);
        $data['preferred_skills'] = $this->processTags($request->preferred_skills);

        Vacancy::create($data);

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy created!');
    }

    public function edit(Vacancy $vacancy)
    {
        return view('admin.vacancies.form', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $data = $this->validateVacancy($request);

        if ($vacancy->title !== $request->title) {
            $data['slug'] = Str::slug($request->title) . '-' . time();
        }

        $data['is_active'] = $request->has('is_active');
        
        // Обработка массивов
        $data['required_skills'] = $this->processTags($request->required_skills);
        $data['preferred_skills'] = $this->processTags($request->preferred_skills);

        $vacancy->update($data);

        return redirect()->route('admin.vacancies.index')->with('success', 'Vacancy updated successfully!');
    }

    // Общая валидация
    private function validateVacancy(Request $request)
    {
        return $request->validate([
            // Frontend
            'title'             => 'required|string|max:255',
            'company_name'      => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'salary_range'      => 'nullable|string|max:255',
            'contract_type'     => 'required|string|max:100',
            'working_mode'      => 'nullable|string|max:100',
            'pqe_range'         => 'nullable|string|max:100',
            'office_attendance' => 'nullable|string|max:255',
            'summary'           => 'nullable|string',
            'description'       => 'required|string',
            'requirements'      => 'nullable|string',
            'benefits'          => 'nullable|string',

            // Backend
            'internal_job_type' => 'nullable|string|max:100',
            'practice_area'     => 'nullable|string|max:255',
            'seniority_level'   => 'nullable|string|max:100',
            'pqe_weighting'     => 'nullable|string|max:255',
            'required_skills'   => 'nullable|string', // Приходит строкой, уходит в JSON
            'preferred_skills'  => 'nullable|string', // Приходит строкой, уходит в JSON
            'keywords'          => 'nullable|string',
            'internal_notes'    => 'nullable|string',
        ]);
    }

    private function processTags($string) {
        if (!$string) return null;
        return array_map('trim', explode(',', $string));
    }
}