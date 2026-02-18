@extends('layouts.admin')

@section('content')
<div class="container-fluid pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="title-3">{{ isset($vacancy) ? 'Edit Job Listing' : 'Create Job Listing' }}</h3>
        <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ isset($vacancy) ? route('admin.vacancies.update', $vacancy->id) : route('admin.vacancies.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($vacancy)) @method('PUT') @endif

        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-eye"></i> Frontend Visible Fields (Candidate View)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Row 1 --}}
                    <div class="col-md-6 mb-3">
                        <label>Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $vacancy->title ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $vacancy->company_name ?? '') }}" required>
                    </div>

                    {{-- Row 2 --}}
                    <div class="col-md-3 mb-3">
                        <label>Contract Type</label>
                        <select name="contract_type" class="form-control">
                            @foreach(['Permanent', 'Fixed Term Contract', 'Temporary'] as $opt)
                                <option value="{{ $opt }}" {{ (old('contract_type', $vacancy->contract_type ?? '') == $opt) ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Working Pattern</label>
                        <select name="working_mode" class="form-control">
                            @foreach(['Full-time', 'Part-time', 'Consultant'] as $opt)
                                <option value="{{ $opt }}" {{ (old('working_mode', $vacancy->working_mode ?? '') == $opt) ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>PQE Range (Display)</label>
                        <input type="text" name="pqe_range" class="form-control" value="{{ old('pqe_range', $vacancy->pqe_range ?? '') }}" placeholder="e.g. NQ–2 PQE">
                    </div>
                     <div class="col-md-3 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $vacancy->location ?? '') }}" placeholder="e.g. London">
                    </div>

                    {{-- Row 3 --}}
                    <div class="col-md-4 mb-3">
                        <label>Salary Band</label>
                        <input type="text" name="salary_range" class="form-control" value="{{ old('salary_range', $vacancy->salary_range ?? '') }}" placeholder="e.g. £150,000+">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Office Attendance</label>
                        <input type="text" name="office_attendance" class="form-control" value="{{ old('office_attendance', $vacancy->office_attendance ?? '') }}" placeholder="e.g. 4 days per week">
                    </div>
                     <div class="col-md-4 mb-3 d-flex align-items-center">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $vacancy->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="isActive">Job is Live / Published</label>
                        </div>
                    </div>

                    {{-- Text Areas --}}
                    <div class="col-md-12 mb-3">
                        <label>Short Role Summary (Teaser)</label>
                        <textarea name="summary" class="form-control" rows="3">{{ old('summary', $vacancy->summary ?? '') }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Full Role Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control tinymce-editor" id="editor_desc">{{ old('description', $vacancy->description ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Requirements (Public Text)</label>
                        <textarea name="requirements" class="form-control tinymce-simple">{{ old('requirements', $vacancy->requirements ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Benefits (Public Text)</label>
                        <textarea name="benefits" class="form-control tinymce-simple">{{ old('benefits', $vacancy->benefits ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-secondary">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fa fa-cogs"></i> Backend Hidden Fields (AI Matching Logic)</h5>
            </div>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Internal: Job Type</label>
                        <select name="internal_job_type" class="form-control">
                            <option value="">Select...</option>
                            @foreach(['Private practice', 'In-house', 'Alternative Legal Service'] as $opt)
                                <option value="{{ $opt }}" {{ (old('internal_job_type', $vacancy->internal_job_type ?? '') == $opt) ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Internal: Practice Area</label>
                        <input type="text" name="practice_area" class="form-control" value="{{ old('practice_area', $vacancy->practice_area ?? '') }}" placeholder="e.g. Corporate / M&A">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Internal: Seniority Level</label>
                        <select name="seniority_level" class="form-control">
                            <option value="">Select...</option>
                            @foreach(['Paralegal', 'Associate', 'Senior Associate', 'Partner', 'Counsel'] as $opt)
                                <option value="{{ $opt }}" {{ (old('seniority_level', $vacancy->seniority_level ?? '') == $opt) ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Internal: PQE Weighting (Logic)</label>
                        <input type="text" name="pqe_weighting" class="form-control" value="{{ old('pqe_weighting', $vacancy->pqe_weighting ?? '') }}" placeholder="e.g. Preferred 0–2 PQE, some flexibility">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Required Skills (AI Structured - Comma separated)</label>
                        <textarea name="required_skills" class="form-control" rows="3" placeholder="M&A, Private Equity, Cross-border...">{{ is_array($vacancy->required_skills ?? null) ? implode(', ', $vacancy->required_skills) : old('required_skills') }}</textarea>
                        <small class="text-muted">Used for strict matching</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Preferred Skills (AI Structured - Comma separated)</label>
                        <textarea name="preferred_skills" class="form-control" rows="3" placeholder="German language, Tech sector experience...">{{ is_array($vacancy->preferred_skills ?? null) ? implode(', ', $vacancy->preferred_skills) : old('preferred_skills') }}</textarea>
                        <small class="text-muted">Used for bonus scoring</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Internal Keywords / Tags</label>
                        <input type="text" name="keywords" class="form-control" value="{{ old('keywords', $vacancy->keywords ?? '') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Recruiter Notes (Internal only)</label>
                        <textarea name="internal_notes" class="form-control" rows="2">{{ old('internal_notes', $vacancy->internal_notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fa fa-save"></i> Save Job Listing
            </button>
        </div>
    </form>
</div>

{{-- Подключаем скрипты TinyMCE (оставляем твою конфигурацию) --}}
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>
<script>
    // Основной редактор (полный фарш)
    tinymce.init({
        selector: '#editor_desc',
        license_key: 'gpl',
        height: 500,
        plugins: 'code advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code image link',
        images_upload_url: '{{ route('admin.cms.upload_image') }}',
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            // Твой код загрузки (без изменений)
             const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.cms.upload_image') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = () => {
                if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.url !== 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                resolve(json.url);
            };
            xhr.onerror = () => { reject('Image upload failed due to a network error.'); }; 
            const formData = new FormData();
            formData.append('upload', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }),
        convert_urls: false,
        verify_html: false,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    // Упрощенный редактор для Requirements/Benefits (без загрузки картинок)
    tinymce.init({
        selector: '.tinymce-simple',
        license_key: 'gpl',
        height: 250,
        menubar: false,
        plugins: 'lists link code wordcount',
        toolbar: 'undo redo | bold italic | bullist numlist | removeformat | code',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
@endsection