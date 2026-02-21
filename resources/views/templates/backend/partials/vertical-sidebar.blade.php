<aside class="left-sidebar with-vertical">
    <div>
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="{{ url('/') }}" class="text-nowrap logo-img">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 40px; width: auto;" />
                    <span class="hide-menu ms-2 fw-bold text-dark"
                        style="font-size:13px;font-family:'Trebuchet MS',Arial,sans-serif;line-height:1.2;">
                        MAUPONGGO SATAP
                    </span>
                </a>
                <div class="d-block d-xl-none sidebartoggler cursor-pointer" style="margin-right: -10px;">
                    <i class="ti ti-x fs-8"></i>
                </div>
            </div>

            <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                <ul class="sidebar-menu" id="sidebarnav">

                    {{-- ======= HOME ======= --}}
                    <li class="nav-small-cap">
                        <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
                        <span class="hide-menu">Beranda</span>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)" aria-expanded="false">
                            <iconify-icon icon="solar:home-smile-bold-duotone"></iconify-icon>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    {{-- ======= AKADEMIK ======= --}}
                    <li class="nav-small-cap mt-2">
                        <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
                        <span class="hide-menu">Akademik</span>
                    </li>

                    {{-- Kelas --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                            <span class="hide-menu">Kelas</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('kelas.index') }}">
                                    <iconify-icon icon="solar:list-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Daftar Kelas</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('kelas.create') }}">
                                    <iconify-icon icon="solar:add-square-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Tambah Kelas</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Siswa --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                            <span class="hide-menu">Siswa</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('siswa.index') }}">
                                    <iconify-icon icon="solar:list-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Daftar Siswa</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('siswa.create') }}">
                                    <iconify-icon icon="solar:user-plus-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Tambah Siswa</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- ======= ADMINISTRASI ======= --}}
                    <li class="nav-small-cap mt-2">
                        <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
                        <span class="hide-menu">Administrasi</span>
                    </li>

                    {{-- User --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon>
                            <span class="hide-menu">Pengguna</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('user.index') }}">
                                    <iconify-icon icon="solar:list-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Daftar Pengguna</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('user.create') }}">
                                    <iconify-icon icon="solar:user-plus-bold-duotone" class="icon-small"
                                        style="font-size:14px"></iconify-icon>
                                    <span class="hide-menu">Tambah Pengguna</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>

        </div>
    </div>
</aside>
