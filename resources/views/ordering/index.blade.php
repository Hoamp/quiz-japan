<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kuis</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manajemen Kuis</h1>
        
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuizModal">Tambah Kuis</button>
        <a href="{{ route('dashboard') }}" class="btn btn-danger">Kembali</a>

        <h3 class="mt-4">Daftar Kuis</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="quizList">
                @foreach ($orderings as $order)
                    <tr data-id="{{ $order->id }}">
                        <td class="nama">{{ $order->nama }}</td>
                        <td class="deskripsi">{{ $order->deskripsi }}</td>
                        <td>
                            <a href="/ordering/{{ $order->id }}/questions" class="btn btn-info btn-sm">Soal</a>
                            <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editQuizModal">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Kuis -->
    <div class="modal fade" id="addQuizModal" tabindex="-1" aria-labelledby="addQuizModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuizModalLabel">Tambah Kuis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addQuizForm">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kuis:</label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi:</label>
                            <input type="text" class="form-control" id="deskripsi">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kuis -->
    <div class="modal fade" id="editQuizModal" tabindex="-1" aria-labelledby="editQuizModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuizModalLabel">Edit Kuis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editQuizForm">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Nama Kuis:</label>
                            <input type="text" class="form-control" id="edit-nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-deskripsi" class="form-label">Deskripsi:</label>
                            <input type="text" class="form-control" id="edit-deskripsi">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Tambah kuis
            $('#addQuizForm').submit(function (event) {
                event.preventDefault();
                $.post('{{ route("ordering.store") }}', {
                    nama: $('#nama').val(),
                    deskripsi: $('#deskripsi').val(),
                    _token: '{{ csrf_token() }}'
                }, function (response) {
                    Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                }).fail(function () {
                    Swal.fire('Error', 'Gagal menambahkan kuis', 'error');
                });
            });

            // Edit kuis
            $('.edit-btn').click(function () {
                let row = $(this).closest('tr');
                $('#edit-id').val(row.data('id'));
                $('#edit-nama').val(row.find('.nama').text());
                $('#edit-deskripsi').val(row.find('.deskripsi').text());
            });

            $('#editQuizForm').submit(function (event) {
                event.preventDefault();
                let id = $('#edit-id').val();
                $.ajax({
                    url: '/ordering/' + id,
                    method: 'PUT',
                    data: {
                        nama: $('#edit-nama').val(),
                        deskripsi: $('#edit-deskripsi').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal memperbarui kuis', 'error');
                    }
                });
            });

            // Hapus kuis
            $('.delete-btn').click(function () {
                let id = $(this).closest('tr').data('id');
                Swal.fire({
                    title: 'Hapus Kuis?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/ordering/' + id,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire('Error', 'Gagal menghapus kuis', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>