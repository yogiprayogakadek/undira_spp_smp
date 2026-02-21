<header class="topbar">
    <div class="with-vertical"><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Header -->
        <!-- ---------------------------------- -->
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li class="nav-item nav-icon-hover ms-n3">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                    </a>
                </li>
            </ul>

            <div class="d-block d-lg-none">
                <span class="fw-bold fs-5 text-dark"
                    style="font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">Mal
                    Bali Galeria</span>
            </div>
            <a class="navbar-toggler p-0 border-0 nav-icon-hover" href="javascript:void(0)" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="p-2">
                    <i class="ti ti-dots fs-7"></i>
                </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">
                    <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                        <li class="nav-item nav-icon-hover">
                            <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                            </a>
                            <a class="nav-link sun light-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                            </a>
                        </li>

                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                <div class="d-flex align-items-center gap-2 lh-base">
                                    <img src="{{ Auth::user()->avatar ?? 'https://bootstrapdemos.wrappixel.com/materialM/dist/assets/images/profile/user-1.jpg' }}"
                                        class="rounded-circle" width="35" height="35" alt="malbaligaleria" />
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="{{ Auth::user()->avatar ?? 'https://bootstrapdemos.wrappixel.com/materialM/dist/assets/images/profile/user-1.jpg' }}"
                                            class="rounded-circle" width="80" height="80" alt="malbaligaleria" />
                                        <div class="ms-3">
                                            <h5 class="mb-0 fs-4">{{ ucwords(Auth::user()->role) }}</h5>
                                            {{-- <span class="mb-1 d-block">Administrator</span> --}}
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i>
                                                {{ Auth::user()->email ?? 'admin@gmail.com' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        {{-- <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/page-user-profile.html"
                                            class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                                                <iconify-icon icon="solar:wallet-2-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                                                <span class="fs-2 d-block text-body-secondary">Account
                                                    Settings</span>
                                            </div>
                                        </a>
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/app-email.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                                                <iconify-icon icon="solar:inbox-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                                                <span class="fs-2 d-block text-body-secondary">Messages &
                                                    Emails</span>
                                            </div>
                                        </a>
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/app-notes.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                                                <iconify-icon icon="solar:checklist-minimalistic-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                                                <span class="fs-2 d-block text-body-secondary">To-do and
                                                    Daily Tasks</span>
                                            </div>
                                        </a> --}}
                                    </div>
                                    <div class="d-grid py-4 px-7 pt-8">
                                        {{-- <a href="javascript:void(0)"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                                            class="btn btn-primary">Log Out</a> --}}
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                                            class="btn btn-primary">Log Out</a>

                                        <form id="logout-form" method="POST" action="{{ route('logout') }}"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>
        <!-- ---------------------------------- -->
        <!-- End Vertical Layout Header -->
        <!-- ---------------------------------- -->
    </div>
    <div class="app-header with-horizontal">
        <nav class="navbar navbar-expand-xl container-fluid p-0">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler ms-n3" id="sidebarCollapse" href="javascript:void(0)">
                        <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                    </a>
                </li>
                <li class="nav-item d-none d-xl-block">
                    <a href="{{ url('/') }}" class="text-nowrap nav-link">
                        <span class="fw-bold fs-5 text-dark"
                            style="font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">Mal
                            Bali Galeria</span>
                    </a>
                </li>

            </ul>
            <div class="d-block d-xl-none">
                <a href="{{ url('/') }}" class="text-nowrap nav-link">
                    <span class="fw-bold fs-5 text-dark"
                        style="font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">Mal
                        Bali Galeria</span>
                </a>
            </div>
            <a class="navbar-toggler nav-icon-hover p-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="p-2">
                    <i class="ti ti-dots fs-7"></i>
                </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                        <li class="nav-item nav-icon-hover">
                            <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                            </a>
                            <a class="nav-link sun light-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                            </a>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- start notification Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item nav-icon-hover dropdown">
                            <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                <div class="notification text-bg-danger rounded-circle fs-1">5</div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop2">
                                <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                    <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5
                                        new</span>
                                </div>
                                <div class="message-body" data-simplebar>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                            <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                <span class="d-block fs-2">9:30 AM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">Just see the
                                                my new admin!</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Event today</h6>
                                                <span class="d-block fs-2">9:15 AM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">Just a
                                                reminder that you have event</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Settings</h6>
                                                <span class="d-block fs-2">4:36 PM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">You can
                                                customize this template as you want</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                                            <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                <span class="d-block fs-2">9:30 AM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">Just see the
                                                my new admin!</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Event today</h6>
                                                <span class="d-block fs-2">9:15 AM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">Just a
                                                reminder that you have event</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                        <span
                                            class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-1 fw-semibold">Settings</h6>
                                                <span class="d-block fs-2">4:36 PM</span>
                                            </div>
                                            <span class="d-block text-truncate text-truncate">You can
                                                customize this template as you want</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="py-6 px-7 mb-1">
                                    <button class="btn btn-outline-primary w-100">See All
                                        Notifications</button>
                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end notification Dropdown -->
                        <!-- ------------------------------- -->

                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <div class="d-flex align-items-center gap-2 lh-base">
                                    <img src="https://bootstrapdemos.wrappixel.com/materialM/dist/assets/images/profile/user-1.jpg"
                                        class="rounded-circle" width="35" height="35" alt="malbaligaleria" />
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="https://bootstrapdemos.wrappixel.com/materialM/dist/assets/images/profile/user-1.jpg"
                                            class="rounded-circle" width="80" height="80"
                                            alt="malbaligaleria" />
                                        <div class="ms-3">
                                            <h5 class="mb-0 fs-4">Jonathan Deo</h5>
                                            <span class="mb-1 d-block">Admin</span>
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> info@MaterialM.com
                                            </p>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/page-user-profile.html"
                                            class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                                                <iconify-icon icon="solar:wallet-2-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                                                <span class="fs-2 d-block text-body-secondary">Account
                                                    Settings</span>
                                            </div>
                                        </a>
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/app-email.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                                                <iconify-icon icon="solar:inbox-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                                                <span class="fs-2 d-block text-body-secondary">Messages &
                                                    Emails</span>
                                            </div>
                                        </a>
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/app-notes.html"
                                            class="py-8 px-7 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                                                <iconify-icon icon="solar:checklist-minimalistic-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <div class="w-75 v-middle ps-3">
                                                <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                                                <span class="fs-2 d-block text-body-secondary">To-do and
                                                    Daily Tasks</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-grid py-4 px-7 pt-8">
                                        <a href="https://bootstrapdemos.wrappixel.com/materialM/dist/default-sidebar/authentication-login.html"
                                            class="btn btn-primary">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
