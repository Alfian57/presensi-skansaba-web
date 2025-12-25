<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-4">
                    @if (Auth::check() && Auth::user()->profile_picture)
                        <div class="avatar-lg"><img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                alt="image profile" class="avatar-img rounded-circle img-fluid">
                        </div>
                    @else
                        <i class="fas fa-user-circle" style="font-size: 50px; color: #495057;"></i>
                    @endif
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                            <span class="user-level">
                                @if (Auth::check() && Auth::user()->hasRole('admin'))
                                    Administrator
                                @elseif (Auth::check() && Auth::user()->hasRole('teacher'))
                                    Guru
                                @elseif (Auth::check() && Auth::user()->hasRole('student'))
                                    Siswa
                                @else
                                    Guest
                                @endif
                            </span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('dashboard.profile.edit') }}" class="collapsed">
                                    <span class="link-collapse">Ganti Password</span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.profile.edit') }}" class="collapsed">
                                    <span class="link-collapse">Ganti Foto Profile</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">

                {{-- ========================================= --}}
                {{-- STUDENT MENU --}}
                {{-- ========================================= --}}
                @if (Auth::user()->hasRole('student'))
                    <li class="nav-item {{ Request::routeIs('dashboard.student.home') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.student.home') }}">
                            <i class="fas fa-home"></i>
                            <p class="ms-3">Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Data Saya</h4>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.student.attendance') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.student.attendance') }}">
                            <i class="fas fa-clipboard-check"></i>
                            <p class="ms-3">Riwayat Presensi</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.student.schedule') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.student.schedule') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p class="ms-3">Jadwal Pelajaran</p>
                        </a>
                    </li>
                @endif

                {{-- ========================================= --}}
                {{-- TEACHER & ADMIN MENU --}}
                {{-- ========================================= --}}
                @if (Auth::user()->hasAnyRole(['admin', 'teacher']))
                    {{-- Dashboard --}}
                    <li class="nav-item {{ Request::routeIs('dashboard.home') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.home') }}">
                            <i class="fas fa-home"></i>
                            <p class="ms-3">Dashboard</p>
                        </a>
                    </li>

                    {{-- SECTION: PRESENSI --}}
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Presensi</h4>
                    </li>

                    <li
                        class="nav-item {{ Request::routeIs('dashboard.attendances.*') && !Request::routeIs('dashboard.attendances.recap.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.attendances.index') }}">
                            <i class="fas fa-clipboard-check"></i>
                            <p class="ms-3">Data Presensi</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.class-absences.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.class-absences.index') }}">
                            <i class="fas fa-user-times"></i>
                            <p class="ms-3">Bolos Pelajaran</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.attendances.recap.*') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#recapMenu"
                            class="{{ Request::routeIs('dashboard.attendances.recap.*') ? '' : 'collapsed' }}"
                            aria-expanded="{{ Request::routeIs('dashboard.attendances.recap.*') ? 'true' : 'false' }}">
                            <i class="fas fa-chart-bar"></i>
                            <p class="ms-3">Rekap Presensi</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ Request::routeIs('dashboard.attendances.recap.*') ? 'show' : '' }}"
                            id="recapMenu">
                            <ul class="nav nav-collapse">
                                <li class="{{ Request::routeIs('dashboard.attendances.recap.student') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.attendances.recap.student') }}">
                                        <i class="fas fa-user-check"></i>
                                        <span class="sub-item">Rekap per Siswa</span>
                                    </a>
                                </li>
                                <li class="{{ Request::routeIs('dashboard.attendances.recap.classroom') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.attendances.recap.classroom') }}">
                                        <i class="fas fa-users"></i>
                                        <span class="sub-item">Rekap per Kelas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    {{-- Teacher's own schedules --}}
                    @if (Auth::user()->hasRole('teacher'))
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Jadwal</h4>
                        </li>

                        <li class="nav-item {{ Request::routeIs('dashboard.schedules.mine') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.schedules.mine') }}">
                                <i class="fas fa-calendar-check"></i>
                                <p class="ms-3">Jadwal Saya</p>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- ========================================= --}}
                {{-- ADMIN ONLY MENU --}}
                {{-- ========================================= --}}
                @if (Auth::user()->hasRole('admin'))
                    {{-- SECTION: DATA MASTER --}}
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Data Master</h4>
                    </li>

                    <li
                        class="nav-item {{ Request::routeIs(['dashboard.classrooms.*', 'dashboard.subjects.*', 'dashboard.homerooms.*']) ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#dataUmum"
                            class="{{ Request::routeIs(['dashboard.classrooms.*', 'dashboard.subjects.*', 'dashboard.homerooms.*']) ? '' : 'collapsed' }}"
                            aria-expanded="{{ Request::routeIs(['dashboard.classrooms.*', 'dashboard.subjects.*', 'dashboard.homerooms.*']) ? 'true' : 'false' }}">
                            <i class="fas fa-database"></i>
                            <p class="ms-3">Data Umum</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ Request::routeIs(['dashboard.classrooms.*', 'dashboard.subjects.*', 'dashboard.homeroom-teachers.*']) ? 'show' : '' }}"
                            id="dataUmum">
                            <ul class="nav nav-collapse">
                                <li class="{{ Request::routeIs('dashboard.classrooms.*') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.classrooms.index') }}">
                                        <i class="fas fa-school"></i>
                                        <span class="sub-item">Kelas</span>
                                    </a>
                                </li>
                                <li class="{{ Request::routeIs('dashboard.subjects.*') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.subjects.index') }}">
                                        <i class="fas fa-book"></i>
                                        <span class="sub-item">Mata Pelajaran</span>
                                    </a>
                                </li>
                                <li class="{{ Request::routeIs('dashboard.homeroom-teachers.*') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.homeroom-teachers.index') }}">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span class="sub-item">Wali Kelas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item {{ Request::routeIs('dashboard.schedules.*') && !Request::routeIs('dashboard.schedules.mine') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.schedules.index') }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p class="ms-3">Jadwal Mengajar</p>
                        </a>
                    </li>

                    {{-- SECTION: MANAJEMEN PENGGUNA --}}
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Manajemen Pengguna</h4>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.students.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.students.index') }}">
                            <i class="fas fa-user-graduate"></i>
                            <p class="ms-3">Data Siswa</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.teachers.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <p class="ms-3">Data Guru</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.admins.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.admins.index') }}">
                            <i class="fas fa-user-shield"></i>
                            <p class="ms-3">Akun Admin</p>
                        </a>
                    </li>

                    {{-- SECTION: SISTEM --}}
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Sistem</h4>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.config.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.config.index') }}">
                            <i class="fas fa-cog"></i>
                            <p class="ms-3">Konfigurasi</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.devices.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.devices.index') }}">
                            <i class="fas fa-mobile-alt"></i>
                            <p class="ms-3">Device Aktif</p>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::routeIs('dashboard.semester-transition.*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.semester-transition.index') }}">
                            <i class="fas fa-sync-alt"></i>
                            <p class="ms-3">Pergantian Semester</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>