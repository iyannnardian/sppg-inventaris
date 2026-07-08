@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
        /* Global premium sidebar styles */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', 'Source Sans Pro', sans-serif !important;
        }

        /* Sidebar Styling - Sleek dark navy gradient */
        .main-sidebar {
            background: linear-gradient(180deg, #1d213a 0%, #111322 100%) !important;
        }
        .brand-link {
            background: #1d213a !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            padding: 18px 15px !important;
            height: auto !important;
        }
        .brand-link .brand-text {
            color: #ffffff !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            font-size: 18px !important;
        }
        
        .user-panel {
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            padding: 20px 15px !important;
        }
        .user-role-badge {
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Sidebar Navigation active state styling */
        .nav-sidebar .nav-item > .nav-link {
            color: #a2a6be !important;
            font-weight: 500;
            border-radius: 8px !important;
            margin: 4px 10px !important;
            padding: 10px 14px !important;
            transition: all 0.2s ease;
        }
        .nav-sidebar .nav-item > .nav-link:hover {
            background-color: rgba(255,255,255,0.05) !important;
            color: #ffffff !important;
        }
        .nav-sidebar .nav-item > .nav-link.active {
            background-color: rgba(255,255,255,0.1) !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: none !important;
        }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    @if(Auth::check())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dinamis memperbarui sidebar panel user profile
            const userPanel = document.querySelector('.sidebar .user-panel');
            if (userPanel) {
                const userName = "{{ Auth::user()->name }}";
                const rawRole = "{{ Auth::user()->role }}";
                let roleName = "User";
                let badgeColor = "#ffb300"; // Gold
                let textColor = "#1a1e36";

                if (rawRole === 'admin') {
                    roleName = "Admin";
                    badgeColor = "#ffb300";
                    textColor = "#1a1e36";
                } else if (rawRole === 'ahli gizi') {
                    roleName = "Ahli Gizi";
                    badgeColor = "#28a745";
                    textColor = "#ffffff";
                } else if (rawRole === 'kepala dapur') {
                    roleName = "Kepala Dapur";
                    badgeColor = "#007bff";
                    textColor = "#ffffff";
                }

                userPanel.innerHTML = `
                    <div class="d-flex align-items-center w-100" style="padding: 5px 0;">
                        <div class="mr-3" style="font-size: 24px; color: #a2a6be;">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="info p-0 d-flex flex-column">
                            <span class="font-weight-bold text-white" style="font-size: 14px; letter-spacing: 0.3px;">${userName}</span>
                            <span class="user-role-badge mt-1 align-self-start" style="background-color: ${badgeColor} !important; color: ${textColor} !important;">${roleName}</span>
                        </div>
                    </div>
                `;
            }
        });
    </script>
    @endif
@stop
