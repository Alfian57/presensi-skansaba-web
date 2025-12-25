<div class="main-header">
    <div class="logo-header" data-background-color="blue">

        <a href="{{ route('dashboard.home') }}" class="logo d-none d-lg-block">
            <img src="/img/logo2.png" alt="navbar brand" class="navbar-brand pb-2" width="30">
            <b class="text-white me-3">SMKN 1 BANTUL</b>
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="fas fa-bars" style="font-size: 25px;"></i>
            </span>
        </button>
        <button class="topbar-toggler more">
            <i class="fas fa-ellipsis-v" style="font-size: 25px;"></i>
        </button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="fas fa-bars" style="font-size: 25px;"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item" style="margin-right: 70px; padding-bottom: 5px">
                    <div class="dropdown">
                        <a class="qr-trigger" href="#" data-bs-toggle="dropdown" aria-expanded="false"
                            title="QR Code Presensi">
                            <i class="fas fa-qrcode"></i>
                        </a>
                        <ul class="dropdown-menu animated fadeIn">
                            <li><a class="dropdown-item" href="{{ route('dashboard.display.attendance.today') }}"><i
                                        class="fas fa-sign-in-alt me-2"></i>Display Presensi Hari Ini</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.config.qr.display') }}"><i
                                        class="fas fa-qrcode me-2"></i>QR Code Presensi</a></li>
                        </ul>
                    </div>

                </li>
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm" style="margin-left: -50px; margin-bottom: 20px;">
                            <div class="pt-2">
                                @if (Auth::check() && Auth::user()->profile_picture)
                                    <div class="avatar-lg"><img
                                            src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                            alt="image profile" class="avatar-img-nav rounded-circle img-fluid">
                                    </div>
                                @else
                                    <i class="fas fa-user-circle" style="font-size: 37px; color: #495057;"></i>
                                @endif
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    @if (Auth::check() && Auth::user()->profile_picture)
                                        <div class="avatar-lg"><img
                                                src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                                alt="image profile" class="avatar-img-nav rounded-circle img-fluid">
                                        </div>
                                    @else
                                        <i class="fas fa-user-circle" style="font-size: 40px; color: #495057;"></i>
                                    @endif

                                    <div class="u-text">
                                        <h4>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</h4>
                                        <p class="text-muted">{{ Auth::check() ? Auth::user()->email : '' }}</p>

                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('dashboard.profile.edit') }}">
                                    <i class="fas fa-key me-2"></i>Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('dashboard.logout') }}" method="POST" class="dropdown-item">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-danger btn-sm w-100 rounded collapsed btn-logout">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>