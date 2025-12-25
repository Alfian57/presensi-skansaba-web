<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Siswa</title>
    <link rel="icon" href="/img/icon.ico" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/atlantis.min.css">
    <link rel="stylesheet" href="/css/present.css">
</head>

<body>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row px-md-5 px-2 py-3">
            <div class="col-md-7 second-content bg-light px-3 overflow-hidden order-md-1 order-2">
                @if (Request::is('present'))
                    <h1 class="text-primary mt-3 fs-5 d-block d-md-none text-center">Data Presensi Hari Ini</h1>
                    <h1 class="text-primary mt-3 d-none d-md-block text-center">Data Presensi Hari Ini</h1>
                @else
                    <h1 class="text-primary mt-3 fs-5 d-block d-md-none text-center">Data Presensi Pulang Hari Ini</h1>
                    <h1 class="text-primary mt-3 d-none d-md-block text-center">Data Presensi Pulang Hari Ini</h1>
                @endif

                <div class="row justify-content-end mb-3">
                    <div class="col-md-6">
                        {{-- Filter removed - classroom filter not implemented in this view --}}
                    </div>
                </div>

                <div id="table-container" class="table table-responsive">
                    <table class="table table-striped attendance-data">
                        {{-- PAKAI AJAX --}}
                    </table>
                </div>
            </div>
            <div class="col-md-5 mb-5 order-md-2 order-1">
                <div class="row main-content bg-success text-center w-100">
                    <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
                        <div class="container-fluid">
                            <div class="row">
                                <h2 class="mt-3 align-items-center">Scan Disini</h2>
                            </div>
                            <hr>
                            <div class="row">
                                @if (Auth::check())
                                    <div class="img-fluid mb-3">
                                        {!! QrCode::size(175)->generate($checkInQR->value ?? 'NO_QR') !!}
                                    </div>
                                @else
                                    <div class="img-fluid mb-3 position-relative">
                                        <div class="img-fluid lock-opacity">
                                            {!! QrCode::size(175)->generate('Ooooppppssss....') !!}
                                        </div>
                                        <div class="position-absolute lock-status">
                                            <img src="/img/pass.png" alt="Lock" class="mb-2 icon">
                                            <p
                                                class="text-danger bg-white border border-1 border-dark rounded mx-lg-3 mx-5">
                                                QR Tersedia Untuk<br>Admin dan Guru
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                @if (Auth::check())
                                    <button onclick="window.location.href='{{ route('dashboard.home') }}'"
                                        class="btn-dashboard w-100 py-2 mb-3">Kembali Ke Dashboard</button>
                                @else
                                    <button onclick="window.location.href='{{ route('auth.login') }}'"
                                        class="btn-login w-100 py-2 mb-3">Masuk Sebagai Admin</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center company__info">
                        <h4 class="company_title">
                            <img src="/img/logo2.png" alt="Logo" class="img-fluid">
                            SMKN 1 Bantul
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

    {{-- SweetAlert --}}
    {{--
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
    @include('sweetalert::alert')

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Datatables -->
    {{--
    <script src="/js/plugin/datatables/datatables.min.js"></script> --}}

    @if (Request::is('attendance'))
        <script src="/js/attendance.js"></script>
    @else
        <script src="/js/attendanceHome.js"></script>
    @endif
</body>

</html>