<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kuis</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                <li class="list-group-item">{{ $order->nama }} <button class="btn btn-primary btn-sm float-end start-quiz" data-quiz="{{ $order->id }}">Gas</button></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $('.start-quiz').click(function() {
                let quizId = $(this).data('quiz');
                // Nanti diarahkan ke halaman kuis sesuai ID
                window.location.href = '/quiz/' + quizId;
            });
        });
    </script>
</body>
</html>
