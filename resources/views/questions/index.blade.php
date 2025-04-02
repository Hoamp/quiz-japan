<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Soal - {{ $quiz->nama }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manajemen Soal - {{ $quiz->nama }}</h1>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuestionModal">Tambah Soal</button>
        <a href="{{ route('ordering.index') }}" class="btn btn-danger">Kembali</a>

        <h3 class="mt-4">Daftar Soal</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kanji</th>
                    <th>Arti</th>
                    <th>Cara Baca</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="questionList">
                @foreach ($questions as $question)
                    <tr data-id="{{ $question->id }}">
                        <td class="no">
                            {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}
                        </td>
                        <td class="kanji">{{ $question->kanji }}</td>
                        <td class="meaning">{{ $question->meaning }}</td>
                        <td class="reading">{{ $question->reading }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $question->id }}" data-bs-toggle="modal" data-bs-target="#editQuestionModal">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $question->id }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <div class="d-flex">
                {!! $questions->links() !!}
            </div>
        </table>
    </div>

    <!-- Modal Tambah Soal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addQuestionForm">
                        <input type="hidden" id="ordering" value="{{ $quiz->id }}">
                        <div class="mb-3">
                            <label for="kanji" class="form-label">Kanji:</label>
                            <input type="text" class="form-control" id="kanji" required>
                        </div>
                        <div class="mb-3">
                            <label for="reading" class="form-label">Cara Baca:</label>
                            <input type="text" class="form-control" id="reading" required>
                        </div>
                        <div class="mb-3">
                            <label for="meaning" class="form-label">Arti:</label>
                            <input type="text" class="form-control" id="meaning" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Soal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editQuestionForm">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-kanji" class="form-label">Kanji:</label>
                            <input type="text" class="form-control" id="edit-kanji" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-reading" class="form-label">Cara Baca:</label>
                            <input type="text" class="form-control" id="edit-reading" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-meaning" class="form-label">Arti:</label>
                            <input type="text" class="form-control" id="edit-meaning" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#addQuestionForm').submit(function (event) {
                event.preventDefault();
                $.post('/questions', {
                    ordering: $('#ordering').val(),
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

            $('.edit-btn').click(function () {
                let row = $(this).closest('tr');
                $('#edit-id').val(row.data('id'));
                $('#edit-kanji').val(row.find('.kanji').text());
                $('#edit-meaning').val(row.find('.meaning').text());
                $('#edit-reading').val(row.find('.reading').text());
            });

            $('#editQuestionForm').submit(function (event) {
                event.preventDefault();
                let id = $('#edit-id').val();
                $.ajax({
                    url: '/questions/' + id,
                    method: 'PUT',
                    data: {
                        kanji: $('#edit-kanji').val(),
                        meaning: $('#edit-meaning').val(),
                        reading: $('#edit-reading').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal mengedit soal', 'error');
                    }
                });
            });

            $('.delete-btn').click(function () {
                let id = $(this).data('id');
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
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>