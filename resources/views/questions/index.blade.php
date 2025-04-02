<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Soal - {{ $quiz->nama }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manajemen Soal - {{ $quiz->nama }}</h1>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Tambah Soal</h5>
                <form id="addQuestionForm">
                    <input type="hidden" id="ordering_id" value="{{ $quiz->id }}">
                    <div class="mb-3">
                        <label for="kanji" class="form-label">Kanji:</label>
                        <input type="text" class="form-control" id="kanji" required>
                    </div>
                    <div class="mb-3">
                        <label for="meaning" class="form-label">Arti:</label>
                        <input type="text" class="form-control" id="meaning" required>
                    </div>
                    <div class="mb-3">
                        <label for="reading" class="form-label">Cara Baca:</label>
                        <input type="text" class="form-control" id="reading" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        <h3 class="mt-4">Daftar Soal</h3>
        <a href="{{ route('ordering.index') }}" class="btn btn-danger">Kembali</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Kanji</th>
                    <th>Arti</th>
                    <th>Cara Baca</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="questionList">
                @foreach ($questions as $question)
                    <tr data-id="{{ $question->id }}">
                        <td class="kanji">{{ $question->kanji }}</td>
                        <td class="meaning">{{ $question->meaning }}</td>
                        <td class="reading">{{ $question->reading }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#addQuestionForm').submit(function (event) {
                event.preventDefault();
                $.post('/questions', {
                    ordering_id: $('#ordering_id').val(),
                    kanji: $('#kanji').val(),
                    meaning: $('#meaning').val(),
                    reading: $('#reading').val(),
                    _token: '{{ csrf_token() }}'
                }, function (response) {
                    Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                }).fail(function () {
                    Swal.fire('Error', 'Gagal menambahkan soal', 'error');
                });
            });

            $('.delete-btn').click(function () {
                let id = $(this).closest('tr').data('id');

                Swal.fire({
                    title: 'Hapus Soal?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/questions/' + id,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire('Error', 'Gagal menghapus soal', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
