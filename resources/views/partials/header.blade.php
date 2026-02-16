<header class="header sticky-bar">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo">
                    <a class="d-flex" href="{{ url('/') }}">
                        <img alt="Albatross Recruitment" src="{{ asset('assets/imgs/template/logo-3.png') }}">
                    </a>
                </div>
            </div>
            <div class="header-nav">
                <nav class="nav-main-menu">
                    <ul class="main-menu">
                        <li><a class="active" href="{{ url('/') }}">Home</a></li>
                        <li><a href="#">Find a Job</a></li>
                        <li><a href="#">Recruiters</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>
                <div class="burger-icon burger-icon-white">
                    <span class="burger-icon-top"></span>
                    <span class="burger-icon-mid"></span>
                    <span class="burger-icon-bottom"></span>
                </div>
            </div>
<div class="header-right">
    @guest
        <div class="block-signin">
            <a class="text-link-bd-btom hover-up" href="{{ route('register') }}">Register</a>
            <a class="btn btn-default btn-shadow ml-40 hover-up" href="/login">Sign in</a>
        </div>
    @endguest

    @auth
        <div class="block-signin d-flex align-items-center">
            <span class="mr-15 font-sm color-text-paragraph-2">
                Hello, <strong>{{ Auth::user()->name }}</strong>
            </span>
            
            <a class="text-link-bd-btom hover-up ml-15" href="/profile">Profile</a>
            
            <a class="btn btn-default btn-shadow ml-20 hover-up" href="{{ route('userlogout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sign Out
            </a>

            <form id="logout-form" action="{{ route('userlogout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    @endauth
</div>
        </div>
    </div>
</header>