<div class="nav">
    <a class="btn btn-expanded"></a>
    <nav class="nav-main-menu">
        <ul class="main-menu">
            <li> 
                <a class="dashboard2 {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('backend/imgs/page/dashboard/dashboard.svg') }}" alt="jobBox">
                    <span class="name">Dashboard</span>
                </a>
            </li>
            
			<li> 
                <a class="dashboard2 {{ Route::is('admin.cms.*') ? 'active' : '' }}" href="{{ route('admin.cms.index') }}">
                    <img src="{{ asset('backend/imgs/page/dashboard/authentication.svg') }}" alt="jobBox">
                    <span class="name">Static Pages</span>
                </a>
            </li>
			
            <li> 
                <a class="dashboard2" href="#">
                    <img src="{{ asset('backend/imgs/page/dashboard/jobs.svg') }}" alt="jobBox">
                    <span class="name">Vacancies</span>
                </a>
            </li>
            <li> 
                <a class="dashboard2" href="#">
                    <img src="{{ asset('backend/imgs/page/dashboard/candidates.svg') }}" alt="jobBox">
                    <span class="name">Candidates</span>
                </a>
            </li>
            <li> 
                <a class="dashboard2" href="#">
                    <img src="{{ asset('backend/imgs/page/dashboard/cv-manage.svg') }}" alt="jobBox">
                    <span class="name">CV Manage</span>
                </a>
            </li>
            <li> 
                <a class="dashboard2" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('backend/imgs/page/dashboard/logout.svg') }}" alt="jobBox">
                    <span class="name">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="border-bottom mb-20 mt-20"></div>
    <div class="sidebar-border-bg mt-50">
        <span class="text-grey">SYSTEM</span>
        <span class="text-hiring">v 1.0</span>
        <p class="font-xxs color-text-paragraph mt-5">Albatross Recruitment Platform Admin Panel</p>
    </div>
</div>