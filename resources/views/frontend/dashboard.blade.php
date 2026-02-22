@extends('layouts.frontend') 

@section('content')

@php
    // Подготовка данных для цикла
    if ($isGuest) {
        $displayVacancies = collect([1, 2, 3]); // Заглушки для гостей
    } else {
        $displayVacancies = $vacancies; // Реальные данные
    }
@endphp

<style>
    /* 1. Глобальное выравнивание сетки */
    .card-grid-2 {
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
        min-height: 460px; /* Увеличили высоту для запаса под AI-текст */
        position: relative;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    /* 2. Основной блок контента растягивается */
    .card-block-info {
        display: flex !important;
        flex-direction: column !important;
        flex: 1 0 auto !important; /* Занимает всё доступное пространство */
        padding: 20px;
    }

    /* Заголовки строго в 2 строки */
    .card-block-info h6 {
        min-height: 44px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 10px;
    }

    /* Описание вакансии */
    .card-block-info .font-sm {
        margin-bottom: 15px;
    }

    /* 3. ФИНАЛЬНОЕ ИСПРАВЛЕНИЕ: Футер карточки (Зарплата) всегда внизу */
    .card-2-bottom {
        margin-top: auto !important; /* Выталкивает блок вниз */
        padding-top: 15px;
        border-top: 1px solid #f2f2f2;
        width: 100%;
    }

    /* 4. Стили AI Анализа */
    .ai-score-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 5;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: bold;
    }

    .ai-reason {
        background: #f0f4f8;
        padding: 10px;
        border-radius: 8px;
        margin-top: 10px;
        margin-bottom: 15px; /* Отступ до зарплаты */
        font-size: 11px;
        line-height: 1.4;
        color: #333;
        border-left: 3px solid #05264e;
        max-height: 100px;
        overflow-y: auto;
    }

    /* 5. Эффекты для гостей */
    .content-blurred {
        filter: blur(6px);
        opacity: 0.6;
        pointer-events: none;
        user-select: none;
    }
    .locked-overlay { position: relative; }
    .auth-badge {
        position: absolute;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        width: 100%;
        max-width: 350px;
    }
</style>

<section class="section-box mt-50 mb-50">
    <div class="container py-5">
        {{-- ПРИВЕТСТВИЕ --}}
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Welcome back, {{ $profile->first_name ?? 'Candidate' }}!</h2>
                <p class="text-muted">Analysis of your profile is complete.</p>
            </div>
        </div>

        <div class="row">
            {{-- ЛЕВАЯ КОЛОНКА --}}
            <div class="col-lg-3 col-md-12">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body">
                        <h5 class="mb-20">Profile Insights</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-primary border p-2 mb-1">{{ $profile->primary_practice_area }}</span>
                            <span class="badge bg-light text-secondary border p-2 mb-1">{{ $profile->role_level }}</span>
                        </div>
                        <p class="font-sm color-text-paragraph-2 italic">"{{ $cvSummary }}..."</p>
                        <div class="mt-25">
                            @if($isGuest)
                                <a href="{{ route('register') }}" class="btn btn-brand-1 w-100">Save & Register</a>
                            @else
                                <button id="runAiMatch" class="btn btn-brand-1 w-100">
                                    <span class="spinner-border spinner-border-sm d-none" id="aiLoader"></span>
                                    Start Deep AI Match
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ПРАВАЯ КОЛОНКА (ВАКАНСИИ) --}}
            <div class="col-lg-9 col-md-12">
                <h4 class="fw-bold mb-30">Top Vacancy Matches ({{ $matchCount }})</h4>
                
                <div class="locked-overlay">
                    @if($isGuest)
                        <div class="auth-badge text-center">
                            <div class="card shadow-lg border-0 p-4">
                                <h5 class="mb-3">Unlock Firm Details</h5>
                                <a href="{{ route('register') }}" class="btn btn-brand-1">Register for Free</a>
                            </div>
                        </div>
                    @endif

                    <div class="row {{ $isGuest ? 'content-blurred' : '' }} d-flex align-items-stretch">
                        @foreach($displayVacancies as $v)
                            @php 
                                $isReal = !is_int($v); 
                                $vacancyId = $isReal ? $v->id : 0;
                            @endphp
                            
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30 d-flex">
                                <div class="card-grid-2 hover-up w-100" data-vacancy-id="{{ $vacancyId }}">
                                    
                                    {{-- Бейдж процента AI --}}
                                    <div class="ai-score-badge d-none"></div>
                                    
                                    <div class="card-grid-2-image-left">
                                        <div class="image-box">
                                            <img src="{{ asset($isReal && $v->company_logo ? $v->company_logo : 'assets/imgs/brands/brand-1.png') }}" alt="logo">
                                        </div>
                                        <div class="right-info">
                                            <span class="name-job">{{ $isReal ? $v->company_name : 'Confidential Law Firm' }}</span>
                                            <span class="location-small">{{ $isReal ? $v->location : 'London' }}</span>
                                        </div>
                                    </div>

                                    <div class="card-block-info">
                                        <h6><a href="#">{{ $isReal ? $v->title : 'Associate — Corporate M&A' }}</a></h6>
                                        
                                        <div class="mt-5 mb-10">
                                            <span class="card-briefcase">{{ $isReal ? $v->contract_type : 'Full-time' }}</span>
                                            <span class="card-time">{{ $isReal ? $v->pqe_range : 'NQ-2 PQE' }}</span>
                                        </div>

                                        <p class="font-sm color-text-paragraph">
                                            {{ $isReal ? Str::limit($v->summary, 80) : 'Matching your experience at top tier firms...' }}
                                        </p>

                                        {{-- Сюда JS вставит объяснение AI --}}
                                        <div class="ai-reason d-none"></div>

                                        <div class="card-2-bottom mt-20">
                                            <div class="row align-items-center">
                                                <div class="col-12 text-start">
                                                    <span class="card-text-price text-primary fw-bold">
                                                        {{ $isReal ? $v->salary_range : '£ Competitive' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
document.getElementById('runAiMatch')?.addEventListener('click', function() {
    const btn = this;
    const loader = document.getElementById('aiLoader');
    btn.disabled = true;
    loader.classList.remove('d-none');

    fetch("{{ route('dashboard.ai_match') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(response => {
        if(response.status === 'success') {
            response.data.forEach(match => {
                // ИСПРАВЛЕНИЕ: ИИ вернул "id", а не "vacancy_id"
                const vacancyId = match.vacancy_id || match.id; 
                
                const card = document.querySelector(`[data-vacancy-id="${vacancyId}"]`);
                if(card) {
                    const badge = card.querySelector('.ai-score-badge');
                    const reasonDiv = card.querySelector('.ai-reason');
                    
                    if(badge) {
                        badge.innerHTML = `<i class="bi bi-robot"></i> ${match.score}%`;
                        badge.classList.remove('d-none');
                        
                        if(match.score > 75) badge.className = 'ai-score-badge badge bg-success';
                        else if(match.score > 45) badge.className = 'ai-score-badge badge bg-warning text-dark';
                        else badge.className = 'ai-score-badge badge bg-danger';
                    }

                    if(reasonDiv) {
                        // Добавим и причину, и анализ пробелов (gap analysis), раз ИИ его прислал
                        let reasonHtml = `<strong>Why:</strong> ${match.match_reason}`;
                        if(match.gap_analysis) {
                            reasonHtml += `<br><strong class="mt-1 d-block text-danger">Gaps:</strong> ${match.gap_analysis}`;
                        }
                        reasonDiv.innerHTML = reasonHtml;
                        reasonDiv.classList.remove('d-none');
                    }
                }
            });
        }
    })
    .catch(err => console.error('AI Match Error:', err))
    .finally(() => {
        btn.disabled = false;
        loader.classList.add('d-none');
    });
});
</script>

@endsection