@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="title-3">{{ isset($vacancy) ? 'Edit Job' : 'Create Job' }}</h3>
        <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($vacancy) ? route('admin.vacancies.update', $vacancy->id) : route('admin.vacancies.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($vacancy))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label>Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ old('title', $vacancy->title ?? '') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Job Type</label>
                        <select name="type" class="form-control">
                            @foreach(['Full Time', 'Part Time', 'Contract', 'Freelance'] as $type)
                                <option value="{{ $type }}" {{ (old('type', $vacancy->type ?? '') == $type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" 
                               value="{{ old('company_name', $vacancy->company_name ?? '') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" 
                               value="{{ old('location', $vacancy->location ?? '') }}" placeholder="e.g. London, Remote">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Salary Range</label>
                        <input type="text" name="salary_range" class="form-control" 
                               value="{{ old('salary_range', $vacancy->salary_range ?? '') }}" placeholder="e.g. $5000 - $8000">
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-center">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" 
                                   {{ old('is_active', $vacancy->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active / Published</label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control tinymce-editor" rows="12" id="editor">
                            {{ old('description', $vacancy->description ?? '') }}
                        </textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Required Skills (Comma separated)</label>
                        <textarea name="required_skills" class="form-control" rows="3" placeholder="PHP, Laravel, MySQL...">{{ old('required_skills', $vacancy->required_skills ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($vacancy) ? 'Update Vacancy' : 'Create Vacancy' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>

<script>
    tinymce.init({
        selector: '#editor', // твой ID textarea
        license_key: 'gpl', // Это говорит редактору, что ты используешь его под лицензией GPL
        height: 500,
        // Список плагинов (все локальные)
        plugins: 'code advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code image link',
        
        // Настройка загрузки изображений
        images_upload_url: '{{ route('admin.cms.upload_image') }}',
        
        // Кастомный обработчик для Laravel CSRF
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.cms.upload_image') }}');
            
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.onload = () => {
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.url !== 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                resolve(json.url);
            };

            xhr.onerror = () => {
                reject('Image upload failed due to a network error.');
            }; 

            const formData = new FormData();
            formData.append('upload', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }),

        // Настройки для сохранения "чистого" кода
        convert_urls: false, // чтобы не ломать пути к картинкам
        verify_html: false,  // отключаем агрессивную чистку тегов
        valid_elements: '*[*]', // разрешаем любые теги и атрибуты
        extended_valid_elements: 'script[src|async|defer|type|charset],style,iframe[src|width|height|allowfullscreen|frameborder]',
        
        // Стили редактора (чтобы внутри выглядело как на сайте)
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
@endsection
