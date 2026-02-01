@extends('layouts.frontend')

{{-- Fill SEO tags --}}
@section('seo_title', $page->meta_title ?: $page->title . ' - Albatross Recruit')
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

{{-- Inject Custom CSS --}}
@push('styles')
    @if(!empty($page->custom_css))
        <style>
            {!! $page->custom_css !!}
        </style>
    @endif
@endpush

@section('content')
    @if($page->banner_image)
        <section class="section-box">
            <div class="banner-hero" style="background-image: url({{ asset('storage/' . $page->banner_image) }}); height: 300px; background-size: cover;">
                <div class="container text-center">
                    <h1 class="text-white pt-100">{{ $page->title }}</h1>
                </div>
            </div>
        </section>
    @endif

    <section class="section-box mt-50 mb-50">
        <div class="container">
            @if($page->layout == 'with_sidebar')
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        {!! $page->content !!}
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        @include('frontend.partials.cms_sidebar')
                    </div>
                </div>
            @elseif($page->layout == 'two_columns')
                <div style="column-count: 2; column-gap: 40px;">
                    {!! $page->content !!}
                </div>
            @else
                <div class="content-page">
                    {!! $page->content !!}
                </div>
            @endif
        </div>
    </section>
@endsection

{{-- Inject Custom Scripts --}}
@push('scripts')
    @if(!empty($page->external_scripts))
        @foreach(explode("\n", str_replace("\r", "", $page->external_scripts)) as $url)
            @php $url = trim($url); @endphp
            @if($url)
                <script src="{{ $url }}"></script>
            @endif
        @endforeach
    @endif

    @if(!empty($page->custom_js))
        <script>
            {!! $page->custom_js !!}
        </script>
    @endif
@endpush