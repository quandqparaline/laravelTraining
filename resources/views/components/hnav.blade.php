<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('teams/*') ? 'active' : '' }}"
                       id="navbarDropdown"
                       data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>Team management</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item  {{ request()->is('teams/searchTeam')||request()->is('teams/search/*')||request()->is('teams/create') ||request()->is('teams/edit')||request()->is('teams/delete*') ? 'active' : '' }}"
                           href="{{ route('team.searchTeam') }}">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item  {{ request()->is('teams/createTeam')||request()->is('teams/createConfirm')? 'active' : '' }}"
                           href="{{ route('team.createTeam') }}">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle  {{ request()->is('employees/*') ? 'active' : '' }}"
                       id="navbarDropdown"
                       data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>Employees management</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item  {{ request()->is('employees/searchEmployee')||request()->is('employees/search/*')||request()->is('employees/create')||request()->is('employees/edit')||request()->is('employees/delete*') ? 'active' : '' }}"
                           href="{{ route('employee.searchEmployee') }}">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item  {{ request()->is('employees/createEmployee')||request()->is('employees/createConfirm') ? 'active' : '' }}"
                           href="{{ route('employee.createEmployee') }}">Create</a>
                    </div>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login-page') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register-user') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>
