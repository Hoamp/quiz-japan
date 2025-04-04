<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Kanji</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Kuis Kanji</h1>
        
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card" id="quiz-card">
                    <div class="card-body">
                        <h5 class="card-title">Soal Kuis</h5>
                        <h1 id="kanji-question" class="text-center" style="font-size: 52px;">Memuat soal...</h1>
                        <form id="quizForm" autocomplete="off">
                            <div class="mb-3">
                                <label for="reading" class="form-label">Cara Baca:</label>
                                <input type="text" class="form-control" id="reading" required>
                            </div>
                            <div class="mb-3">
                                <label for="meaning" class="form-label">Arti:</label>
                                <input type="text" class="form-control" id="meaning" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Kirim Jawaban</button>
                        </form>
                    </div>
                </div>
                <div id="quiz-summary" class="mt-4" style="display: none;">
                    <h3>Hasil Kuis</h3>
                    <p>Benar: <span id="correct-count">0</span></p>
                    <p>Salah: <span id="incorrect-count">0</span></p>
                    <h4>Jawaban yang Salah:</h4>
                    <ul id="incorrect-list" class="list-group"></ul>
                    <a href="/dashboard" class="btn btn-primary w-100 mt-3">Kembali ke Dashboard</a>
                </div>
                
                <a href="{{ route('dashboard') }}" class="btn btn-danger mt-5">Kembali</a>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let quizId = new URL(window.location.href).pathname.split('/').pop();
            let count = urlParams.get('count') || ''; // Ambil jumlah soal dari URL jika ada
            let questions = [];
            let currentIndex = 0;
            let correctCount = 0;
            let incorrectCount = 0;
            let incorrectAnswers = [];

            $('#reading').focus();
    
            function loadQuestions() {
                let apiUrl = `/quiz/${quizId}/question` + (count ? `?count=${count}` : '');
                
                $.get(apiUrl, function(data) {
                    questions = data;
                    currentIndex = 0;
                    correctCount = 0;
                    incorrectCount = 0;
                    incorrectAnswers = [];
                    showQuestion();
                }).fail(function() {
                    Swal.fire('Error', 'Gagal mengambil soal. Pastikan backend berjalan.', 'error');
                });
            }
    
            function showQuestion() {
                if (currentIndex < questions.length) {
                    let question = questions[currentIndex];
                    $('#kanji-question').text(question.kanji);
                    $('#quizForm').data('answer-meaning', question.meaning);
                    $('#quizForm').data('answer-reading', question.reading);
                } else {
                    showSummary();
                }
            }
    
            $('#quizForm').submit(function(event) {
                event.preventDefault();
                let meaning = $('#meaning').val().trim();
                let reading = $('#reading').val().trim();
                let correctMeaning = $(this).data('answer-meaning');
                let correctReading = $(this).data('answer-reading');

                // Cek apakah jawaban benar atau salah
                if (meaning === correctMeaning && reading === correctReading) {
                    correctCount++;
                    Swal.fire({
                        title: 'Benar!',
                        text: 'Jawaban kamu benar!',
                        icon: 'success',
                        timer: 1000,
                        allowEnterKey: true // Menangani Enter
                    }).then(() => {
                        // Reset form setelah SweetAlert selesai
                        $('#quizForm')[0].reset();
                        currentIndex++;
                        showQuestion(); // Menampilkan soal berikutnya
                        setTimeout(() => {
                            $('#reading').focus(); // Fokus kembali ke input reading
                        }, 500);
                    });
                } else {
                    incorrectCount++;
                    incorrectAnswers.push({
                        kanji: $('#kanji-question').text(),
                        correctMeaning: correctMeaning,
                        correctReading: correctReading,
                        userMeaning: meaning,
                        userReading: reading
                    });
                    Swal.fire({
                        title: 'Salah!',
                        text: 'Jawaban kamu salah.',
                        icon: 'error',
                        timer: 1000,
                        allowEnterKey: true // Menangani Enter
                    }).then(() => {
                        // Reset form setelah SweetAlert selesai
                        $('#quizForm')[0].reset();
                        currentIndex++;
                        showQuestion(); // Menampilkan soal berikutnya
                        setTimeout(() => {
                            $('#reading').focus(); // Fokus kembali ke input reading
                        }, 500);
                    });
                }
            });

            function showSummary() {
                $('#quiz-card').hide();
                $('#quiz-summary').show();
                $('#correct-count').text(correctCount);
                $('#incorrect-count').text(incorrectCount);
                $('#incorrect-list').empty();
                incorrectAnswers.forEach(ans => {
                    $('#incorrect-list').append(`<li class="list-group-item">
                        <strong>${ans.kanji}</strong> → Cara Baca: <span class="${ans.userReading == ans.correctReading ? "text-success" : "text-danger"}">${ans.userReading}</span> (Benar: ${ans.correctReading}), Arti: <span class="${ans.userMeaning == ans.correctMeaning ? "text-success" : "text-danger"}">${ans.userMeaning}</span> (Benar: ${ans.correctMeaning})
                    </li>`);
                });

                
                // Simpan riwayat penyelesaian kuis
                saveQuizAttempt(quizId, correctCount, incorrectCount);
            }

            function saveQuizAttempt(orderingId, correct, incorrect) {
                $.post('/quiz/attempt', {
                    ordering_id: orderingId,
                    correct_answers: correct,
                    incorrect_answers: incorrect,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(response) {
                    console.log(response.success);
                }).fail(function() {
                    console.error("Gagal menyimpan riwayat kuis.");
                });
            }

            loadQuestions();
        });
    </script>
    
</body>
</html>