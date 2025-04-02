<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Riwayat Kuis</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
        <!-- FullCalendar -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">Riwayat Penyelesaian Kuis</h1>
    
            <form method="GET" action="{{ route('quiz.history') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <select name="month" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="year" class="form-control">
                            @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('dashboard') }}" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </form>
    
            <div id="calendar" class="mb-5"></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let calendarEl = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    events: '/quiz/history/data', // Endpoint untuk mengambil data JSON
                    fixedWeekCount: false, // Supaya tidak ada minggu berlebih
                    eventClick: function(info) {
                        Swal.fire({
                            title: info.event.title,
                            html: `<strong>Benar:</strong> ${info.event.extendedProps.correct} <br>
                                <strong>Salah:</strong> ${info.event.extendedProps.incorrect}`,
                            icon: 'info'
                        });
                    }
                });
                calendar.render();
            });
        </script>
    </body>            
</html>

