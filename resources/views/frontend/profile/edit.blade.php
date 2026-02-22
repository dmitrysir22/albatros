@extends('layouts.frontend') 

@section('content')
<style>
    .required-label::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
</style>

<section class="section-box mt-50 mb-50">
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Review Your Profile</h2>
            <p class="text-muted">Our AI has extracted the following details from your CV. Please review and edit them if necessary.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">First Name</label>
                        <input type="text" name="profile[first_name]" class="form-control @error('profile.first_name') is-invalid @enderror" value="{{ old('profile.first_name', $profile->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Last Name</label>
                        <input type="text" name="profile[last_name]" class="form-control @error('profile.last_name') is-invalid @enderror" value="{{ old('profile.last_name', $profile->last_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Phone Number</label>
                        <input type="text" name="profile[phone]" class="form-control @error('profile.phone') is-invalid @enderror" value="{{ old('profile.phone', $profile->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City / Country</label>
                        <div class="input-group">
                            <input type="text" name="profile[location_city]" class="form-control" value="{{ old('profile.location_city', $profile->location_city) }}" placeholder="City">
                            <input type="text" name="profile[location_country]" class="form-control" value="{{ old('profile.location_country', $profile->location_country) }}" placeholder="Country">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Expertise & Qualification</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">Role Level</label>
                        <input type="text" name="profile[role_level]" class="form-control @error('profile.role_level') is-invalid @enderror" value="{{ old('profile.role_level', $profile->role_level) }}" placeholder="e.g. Associate, Senior Associate" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Primary Practice Area</label>
                        <input type="text" name="profile[primary_practice_area]" class="form-control @error('profile.primary_practice_area') is-invalid @enderror" value="{{ old('profile.primary_practice_area', $profile->primary_practice_area) }}" placeholder="e.g. Corporate M&A" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Profile Summary</label>
                        <textarea name="profile[profile_summary]" class="form-control" rows="4">{{ old('profile.profile_summary', $profile->profile_summary) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Work Experience</h5>
            </div>
            <div class="card-body">
                @foreach($profile->experiences as $exp)
                    <div class="border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="experiences[{{ $exp->id }}][id]" value="{{ $exp->id }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold required-label">Organisation</label>
                                <input type="text" name="experiences[{{ $exp->id }}][organisation]" class="form-control" value="{{ $exp->organisation }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold required-label">Role Title</label>
                                <input type="text" name="experiences[{{ $exp->id }}][role_title]" class="form-control" value="{{ $exp->role_title }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="experiences[{{ $exp->id }}][start_date]" class="form-control" value="{{ $exp->start_date }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date (Leave blank if Current)</label>
                                <input type="date" name="experiences[{{ $exp->id }}][end_date]" class="form-control" value="{{ $exp->end_date }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Key Responsibilities & Achievements</label>
                                <textarea name="experiences[{{ $exp->id }}][description]" class="form-control" rows="3">{{ $exp->description }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Education & Certifications</h5>
            </div>
            <div class="card-body">
                @foreach($profile->educations as $edu)
                    <div class="border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="educations[{{ $edu->id }}][id]" value="{{ $edu->id }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold required-label">Institution</label>
                                <input type="text" name="educations[{{ $edu->id }}][institution]" class="form-control" value="{{ $edu->institution }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold required-label">Qualification</label>
                                <input type="text" name="educations[{{ $edu->id }}][qualification_type]" class="form-control" value="{{ $edu->qualification_type }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Languages / Certs (AI Extracted)</label>
                                <input type="text" class="form-control text-muted" readonly 
                                    value="Langs: {{ implode(', ', $edu->languages ?? []) }} | Certs: {{ implode(', ', $edu->professional_certs ?? []) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Grade / Details</label>
                                <textarea name="educations[{{ $edu->id }}][grade]" class="form-control" rows="2">{{ $edu->grade }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="alert alert-info shadow-sm mb-4 text-dark">
            <h5 class="alert-heading text-primary"><i class="bi bi-robot"></i> AI Profile Analysis</h5>
            <p class="mb-0"><strong>Keywords generated by AI:</strong> {{ implode(', ', $profile->ai_keywords ?? []) }}</p>
        </div>

        <div class="text-center mt-5 mb-5">
            <button type="submit" class="btn btn-success btn-lg px-5 shadow">Save & Continue</button>
        </div>
    </form>
</div>
</section>
@endsection