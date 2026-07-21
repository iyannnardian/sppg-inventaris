@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'profile') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
        @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="user-image img-circle elevation-1 mr-2"
                 alt="{{ Auth::user()->nama ?? Auth::user()->name }}">
        @else
            <i class="fas fa-user-circle fa-lg mr-2 text-primary"></i>
        @endif
        <span class="font-weight-bold d-none d-md-inline">
            {{ Auth::user()->nama ?? Auth::user()->name }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0 rounded-lg">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
            <li class="user-header {{ config('adminlte.usermenu_header_class', 'bg-primary') }} h-auto py-4 text-center rounded-top">
                @if(config('adminlte.usermenu_image'))
                    <img src="{{ Auth::user()->adminlte_image() }}"
                         class="img-circle elevation-2 mb-2"
                         alt="{{ Auth::user()->nama ?? Auth::user()->name }}"
                         style="width: 70px; height: 70px; object-fit: cover;">
                @else
                    <i class="fas fa-user-circle fa-4x text-white mb-2"></i>
                @endif
                <p class="mt-0 mb-0 font-weight-bold text-white text-lg">
                    {{ Auth::user()->nama ?? Auth::user()->name }}
                </p>
                <div class="mt-1">
                    <span class="badge badge-light text-primary font-weight-bold text-uppercase px-3 py-1 shadow-sm">
                        <i class="fas fa-shield-alt mr-1"></i> {{ Auth::user()->role ?? 'User' }}
                    </span>
                </div>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer d-flex justify-content-between align-items-center bg-light p-3 rounded-bottom">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-outline-primary btn-sm font-weight-bold px-3">
                    <i class="fas fa-user-cog mr-1"></i> Profil
                </a>
            @endif
            <a class="btn btn-danger btn-sm font-weight-bold px-3 @if(!$profile_url) btn-block @endif"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt mr-1"></i> Log Out
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>
