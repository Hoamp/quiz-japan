<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kuis</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manajemen Kuis</h1>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Tambah Kuis</h5>
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

        <h3 class="mt-4">Daftar Kuis</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-danger">Kembali</a>
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
                            <td>
                                <a href="/ordering/{{ $order->id }}/questions" class="btn btn-info btn-sm">Soal</a>
                                <button class="btn btn-warning btn-sm edit-btn">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn">Hapus</button>
                            </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
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

            $('.edit-btn').click(function () {
                let row = $(this).closest('tr');
                let id = row.data('id');
                let nama = row.find('.nama').text();
                let deskripsi = row.find('.deskripsi').text();

                Swal.fire({
                    title: 'Edit Kuis',
                    html: `
                        <input id="edit-nama" class="swal2-input" value="${nama}">
                        <input id="edit-deskripsi" class="swal2-input" value="${deskripsi}">
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    preConfirm: () => {
                        return {
                            nama: $('#edit-nama').val(),
                            deskripsi: $('#edit-deskripsi').val()
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/ordering/' + id,
                            method: 'PUT',
                            data: {
                                nama: result.value.nama,
                                deskripsi: result.value.deskripsi,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire('Sukses!', response.success, 'success').then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire('Error', 'Gagal memperbarui kuis', 'error');
                            }
                        });
                    }
                });
            });

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
