<header class="header sticky-bar">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo">
                    <a class="d-flex" href="{{ route('admin.dashboard') }}">
                        <img alt="Albatross" src="{{ asset('backend/imgs/page/dashboard/logo.svg') }}">
                    </a>
                </div>
                <span class="btn btn-grey-small ml-10">Admin area</span>
            </div>
            <div class="header-search">
                <div class="box-search">
                    <form action="">
                        <input class="form-control input-search" type="text" name="keyword" placeholder="Search">
                    </form>
                </div>
            </div>
            <div class="header-menu d-none d-md-block">
                <ul>
                    <li><a href="{{ url('/') }}" target="_blank">View Site</a></li>
                </ul>
            </div>
            <div class="header-right">
                <div class="block-signin">
                    <div class="member-login">
                        <img alt="" src="{{ asset('backend/imgs/page/dashboard/profile.png') }}">
                        <div class="info-member"> 
                            <strong class="color-brand-1">{{ Auth::user()->name }}</strong>
                            <div class="dropdown">
                                <a class="font-xs color-text-paragraph-2 icon-down" id="dropdownProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">Super Admin</a>
                                <ul class="dropdown-menu dropdown-menu-light dropdown-menu-end" aria-labelledby="dropdownProfile">
                                    <li><a class="dropdown-item" href="#">Profiles</a></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>