<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kuis</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Daftar Kuis Kanji Jepang</h1>
        <a href="{{ route('ordering.index') }}" class="btn btn-success">Manajemen kuis</a>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pilih Kuis</h5>
                        <ul class="list-group" id="quizList">
                            @foreach ($ordering as $order)
                                <li class="list-group-item">{{ $order->nama }} 
                                    <button class="btn btn-primary btn-sm float-end start-quiz" data-quiz="{{ $order->id }}">Gas</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilihan Jumlah Soal -->
    <div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quizModalLabel">Pilih Jumlah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quizSelectionForm">
                        <input type="hidden" id="selectedQuizId">
                        <div class="mb-3">
                            <input type="radio" name="question_count" value="all" id="allQuestions" checked>
                            <label for="allQuestions">Semua Soal</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name="question_count" value="some" id="someQuestions">
                            <label for="someQuestions">Beberapa</label>
                        </div>
                        <div class="mb-3" id="questionCountInput" style="display: none;">
                            <label for="questionCount">Jumlah Soal:</label>
                            <input type="number" id="questionCount" class="form-control" min="1">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Mulai Kuis</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $('.start-quiz').click(function() {
                let quizId = $(this).data('quiz');
                $('#selectedQuizId').val(quizId);
                $('#quizModal').modal('show');
            });

            $('input[name="question_count"]').change(function() {
                if ($('#someQuestions').is(':checked')) {
                    $('#questionCountInput').show();
                } else {
                    $('#questionCountInput').hide();
                }
            });

            $('#quizSelectionForm').submit(function(event) {
                event.preventDefault();
                let quizId = $('#selectedQuizId').val();
                let questionMode = $('input[name="question_count"]:checked').val();
                let questionCount = $('#questionCount').val();

                let url = `/quiz/${quizId}`;
                if (questionMode === 'some' && questionCount) {
                    url += `?count=${questionCount}`;
                }
                window.location.href = url;
            });
        });
    </script>
</body>
</html>
