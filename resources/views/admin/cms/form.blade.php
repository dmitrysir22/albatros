@extends('layouts.admin')

@section('title', isset($page) ? 'Edit CMS Page: ' . $page->title : 'Create CMS Page')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="section-box">
            <div class="container">
                <form action="{{ isset($page) ? route('admin.cms.update', $page->id) : route('admin.cms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($page))
                        @method('PUT')
                    @endif
                    
                    <div class="panel-white mb-30">
                        <div class="box-padding">
                            <h6 class="color-brand-1 mb-20">1. Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Page Title *</label>
                                        <input class="form-control" type="text" name="title" id="page_title" 
                                               value="{{ old('title', $page->title ?? '') }}" required placeholder="e.g. About Us">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">URL Slug *</label>
                                        <input class="form-control" type="text" name="slug" id="page_slug" 
                                               value="{{ old('slug', $page->slug ?? '') }}" required placeholder="about-us">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Section *</label>
                                        <select class="form-control" name="section">
                                            @foreach(['General', 'Candidate Resources', 'Help Centre', 'Legal'] as $sec)
                                                <option value="{{ $sec }}" {{ (old('section', $page->section ?? '') == $sec) ? 'selected' : '' }}>
                                                    {{ $sec }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
							
<div class="row mt-20">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Page Layout</label>
            <select class="form-control" name="layout">
                <option value="full_width" {{ (old('layout', $page->layout ?? '') == 'full_width') ? 'selected' : '' }}>Full Width (Standard)</option>
                <option value="with_sidebar" {{ (old('layout', $page->layout ?? '') == 'with_sidebar') ? 'selected' : '' }}>With Sidebar</option>
                <option value="two_columns" {{ (old('layout', $page->layout ?? '') == 'two_columns') ? 'selected' : '' }}>Two Columns</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Header Banner Image</label>
            <input type="file" class="form-control" name="banner_image">
            @if(isset($page) && $page->banner_image)
                <small>Current: <a href="{{ asset('storage/' . $page->banner_image) }}" target="_blank">View Image</a></small>
            @endif
        </div>
    </div>
</div>							

                            <div class="form-group mt-30">
                                <label class="form-label">Main Content</label>
                                <textarea id="editor" name="content">{{ old('content', $page->content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="panel-white mb-30">
                        <div class="box-padding">
                            <h6 class="color-brand-1 mb-20">2. SEO & Meta Tags</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Meta Title</label>
                                        <input class="form-control" type="text" name="meta_title" 
                                               value="{{ old('meta_title', $page->meta_title ?? '') }}" placeholder="SEO Title">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20">
                                    <div class="form-group">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20">
                                    <div class="form-group">
                                        <label class="form-label">Meta Keywords</label>
                                        <input class="form-control" type="text" name="meta_keywords" 
                                               value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="panel-white mb-30">
    <div class="box-padding">
        <h6 class="color-brand-1 mb-20">3. Advanced (Scripts & Styles)</h6>
        <div class="row">
            <div class="col-md-12 mb-20">
                <div class="form-group">
                    <label class="form-label">External JS Libraries (URLs only, one per line)</label>
                    <textarea class="form-control font-monospace" name="external_scripts" rows="3" 
                              placeholder="https://cdn.example.com/library.js&#10;https://another-cdn.com/plugin.min.js">{{ old('external_scripts', $page->external_scripts ?? '') }}</textarea>
                    <small class="text-muted">Injects &lt;script src="..."&gt; for each line.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label text-primary">Custom CSS (Pure CSS code, NO &lt;style&gt; tags)</label>
                    <textarea class="form-control font-monospace" name="custom_css" rows="8" 
                              placeholder=".my-class { color: red; }">{{ old('custom_css', $page->custom_css ?? '') }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label text-danger">Custom JavaScript (Pure JS code, NO &lt;script&gt; tags)</label>
                    <textarea class="form-control font-monospace" name="custom_js" rows="8" 
                              placeholder="console.log('Page loaded');">{{ old('custom_js', $page->custom_js ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

                    <div class="panel-white mb-30">
                        <div class="box-padding">
                            <button class="btn btn-brand-1 hover-up" type="submit">
                                {{ isset($page) ? 'Update Page' : 'Save Page' }}
                            </button>
                            <a href="{{ route('admin.cms.index') }}" class="btn btn-default ml-15">Back to List</a>
                        </div>
                    </div>
                </form>
            </div>
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
    // Auto-slug generator (only for new pages)
    @if(!isset($page))
    document.getElementById('page_title').addEventListener('input', function() {
        let slug = this.value.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        document.getElementById('page_slug').value = slug;
    });
    @endif
</script>



@endsection
