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

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    ClassicEditor.create(document.querySelector('#editor')).catch(error => { console.error(error); });

    // Auto-slug generator (only for new pages)
    @if(!isset($page))
    document.getElementById('page_title').addEventListener('input', function() {
        let slug = this.value.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
        document.getElementById('page_slug').value = slug;
    });
    @endif
</script>
@endsection