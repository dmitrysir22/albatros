@extends('layouts.frontend')

@section('content')
    <section class="section-box mt-50 mb-50">
<div class="container py-5 text-center">
    <h2>Upload your CV</h2>
    <p class="text-muted">Our AI will build your profile in seconds</p>

    <form action="{{ route('cv.upload') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="mb-3">
            <input type="file" name="cv_file" class="form-control form-control-lg" accept=".pdf,.doc,.docx,.jpg,.png">
        </div>
        <button type="submit" class="btn btn-primary btn-lg px-5">Analyze CV</button>
    </form>
</div>
</section>
@endsection