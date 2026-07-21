@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto align-items-center">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        @if(Auth::user())
            {{-- Profile Link --}}
            <li class="nav-item">
                <a class="nav-link text-dark d-flex align-items-center" href="{{ route('profile.edit') }}" title="Profil User">
                    <i class="fas fa-user-circle fa-lg text-primary mr-1"></i>
                    <span class="d-none d-md-inline font-weight-bold">{{ Auth::user()->nama ?? Auth::user()->name }}</span>
                </a>
            </li>

            {{-- Logout Button --}}
            <li class="nav-item ml-2">
                <a class="nav-link btn btn-outline-danger btn-sm text-danger font-weight-bold px-2 py-1" href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();" 
                   title="Log Out">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
