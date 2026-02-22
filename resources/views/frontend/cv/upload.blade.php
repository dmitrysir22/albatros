@extends('layouts.frontend')

@section('content')
<section class="section-box mt-50 mb-50">
    <div class="container py-5 text-center">
        <div id="upload-container">
            <h2>Upload your CV</h2>
            <p class="text-muted">Our AI will build your legal profile in seconds</p>

            <form id="cv-upload-form" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-3">
                    <input type="file" name="cv_file" id="cv_file" class="form-control form-control-lg" accept=".pdf,.doc,.docx" required>
                </div>
                <button type="submit" id="submit-btn" class="btn btn-primary btn-lg px-5">Analyze CV</button>
            </form>
        </div>

        <div id="progress-container" style="display: none;">
            <h2 class="mb-4">Analyzing your experience...</h2>
            
            <div class="progress mb-3" style="height: 30px;">
                <div id="parsing-progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
            </div>
            
            <p id="progress-text" class="lead text-primary font-weight-bold">Reading file...</p>
            <p class="text-muted">Please don't close this window. Our AI is mapping your legal career.</p>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const progressTexts = [
        "Uploading document...",
        "AI is reading the text...",
        "Identifying Practice Areas...",
        "Calculating PQE and seniority...",
        "Structuring professional experience...",
        "Finalizing your profile draft..."
    ];

    $('#cv-upload-form').on('submit', function(e) {
        e.preventDefault();

        // Переключаем блоки
        $('#upload-container').hide();
        $('#progress-container').show();

        let formData = new FormData(this);
        let progress = 0;
        
        // Имитация движения прогресс-бара для UX
        let progressInterval = setInterval(function() {
            if (progress < 95) {
                progress += Math.floor(Math.random() * 5) + 1;
                $('#parsing-progress').css('width', progress + '%');
                
                // Меняем текст в зависимости от прогресса
                let textIdx = Math.floor((progress / 100) * progressTexts.length);
                $('#progress-text').text(progressTexts[textIdx]);
            }
        }, 1500);

        $.ajax({
            url: "{{ route('cv.upload') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
    clearInterval(progressInterval);
    $('#parsing-progress').css('width', '100%');
    $('#progress-text').text('Success! Redirecting...');

    // Теперь нам не нужно проверять 'guest' или 'success' отдельно.
    // Если сервер прислал ссылку — просто идем по ней.
    if (response.redirect_url) {
        window.location.href = response.redirect_url;
    } else {
        // Запасной вариант, если что-то пошло не так
        alert('Parsing complete, but no redirect URL provided.');
        console.log(response);
    }
},
            error: function(xhr) {
                clearInterval(progressInterval);
                alert('Error during parsing. Please try again or check file size.');
                $('#upload-container').show();
                $('#progress-container').hide();
            }
        });
    });
});
</script>
@endsection