@extends('layouts.frontend')

@section('title', 'Legal Recruitment Platform')

@section('content')
    <section class="section-box">
        <div class="banner-hero hero-2">
            <div class="banner-inner">
                <div class="block-banner">
                    <h1 class="text-display-3 color-white wow animate__animated animate__fadeInUp">
                        <span class="color-green">AI-powered matching.</span><br>Human-led recruitment.
                    </h1>
                    <p class="font-lg color-white mt-20 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                        Upload your CV to see tailored legal roles with expert recruiters handling the rest.
                    </p>
                    
                    <div class="form-find mt-40 wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                        <form>
                            <input class="form-input input-keysearch mr-10" type="text" placeholder="Your keyword... ">
                            <select class="form-input mr-10 select-active">
                                <option value="">Location</option>
                                <option value="London">London</option>
                                <option value="Remote">Remote</option>
                            </select>
                            <button class="btn btn-default btn-find font-sm">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-box mt-80">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">Browse by category</h2>
                <p class="font-lg color-text-paragraph wow animate__animated animate__fadeInUp">Find the job that's perfect for you. about 800+ new jobs everyday</p>
            </div>
            <div class="row mt-70">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card-grid-hover">
                        <div class="card-grid-one">
                            <div class="icon-img"><img src="{{ asset('assets/imgs/theme/icons/marketing.svg') }}" alt="jobBox"></div>
                            <a href="#"><h5>Corporate Law</h5></a>
                            <p><span>156</span> Jobs Available</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card-grid-hover">
                        <div class="card-grid-one">
                            <div class="icon-img"><img src="{{ asset('assets/imgs/theme/icons/customer.svg') }}" alt="jobBox"></div>
                            <a href="#"><h5>Litigation</h5></a>
                            <p><span>89</span> Jobs Available</p>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </section>

    <section class="section-box mt-70">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title mb-10">How it works</h2>
                <p class="font-lg color-text-paragraph">Just a few steps to your dream job</p>
            </div>
            <div class="row mt-50">
                <div class="col-lg-4">
                    <div class="box-step-item">
                        <h4 class="mb-20">1. Upload CV</h4>
                        <p class="font-md color-text-paragraph">Our AI will analyze your background and skills automatically.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box-step-item">
                        <h4 class="mb-20">2. Get Matches</h4>
                        <p class="font-md color-text-paragraph">See only the jobs that fit your PQE level and law area.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box-step-item">
                        <h4 class="mb-20">3. Express Interest</h4>
                        <p class="font-md color-text-paragraph">Our recruiters will handle the rest of the process for you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection