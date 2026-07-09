<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ $settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png') }}"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $settings->logo ? asset($settings->logo) : asset('assets/images/logo-dark.png') }}"
                    alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ $settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png') }}"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $settings->logo ? asset($settings->logo) : asset('assets/images/logo-light.png') }}"
                    alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.dashboard.*') ? 'active' : '' }}"
                        href="{{ route('backend.dashboard.index') }}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>

                @canany(['role_management', 'user_management'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ getPageStatus(['backend.role.*', 'backend.system-user.*'], 'collapsed active') }}"
                            href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarLanding">
                            <i class="ri-admin-line"></i> <span data-key="t-pages">Admin & Roles</span>
                        </a>
                        <div class="collapse menu-dropdown {{ getPageStatus(['backend.system-user.*', 'backend.role.*'], 'show') }}"
                            id="sidebarLanding">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('backend.system-user.index') }}"
                                        class="nav-link {{ getPageStatus('backend.system-user.*') }}"
                                        data-key="t-starter"> System Admins </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('backend.role.index') }}"
                                        class="nav-link {{ getPageStatus('backend.role.*') }}" data-key="t-profile">
                                        Permissions </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcanany

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.app-user.*') ? 'active' : '' }}"
                        href="{{ route('backend.app-user.index') }}">
                        <i class="ri-group-line"></i> <span>User Management</span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link  {{getPageStatus('backend.dashboard.*', 'collapsed active')}}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                    <div class="collapse menu-dropdown {{getPageStatus('backend.dashboard.*', 'show')}}" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('backend.dashboard.index')}}" class="nav-link {{getPageStatus('backend.dashboard.index')}}" data-key="t-ecommerce"> Home </a>
                            </li>
                        </ul>
                    </div>
                </li>  --}}
                <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.category.*') ? 'active' : '' }}"
                        href="{{ route('backend.category.index') }}">
                        <i class="ri-stack-line"></i> <span>Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.brand.*') ? 'active' : '' }}"
                        href="{{ route('backend.brand.index') }}">
                        <i class="ri-medal-line"></i> <span>Brands</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.product.*') ? 'active' : '' }}"
                        href="{{ route('backend.product.index') }}">
                        <i class="ri-store-2-line"></i> <span>Products</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.order.*') ? 'active' : '' }}"
                        href="{{ route('backend.order.index') }}">
                        <i class="ri-shopping-cart-2-line"></i> <span>Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.promo-code.*') ? 'active' : '' }}"
                        href="{{ route('backend.promo-code.index') }}">
                        <i class="ri-ticket-line"></i> <span>Promo Codes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.onboarding-option.*') ? 'active' : '' }}"
                        href="{{ route('backend.onboarding-option.index') }}">
                        <i class="ri-user-settings-line"></i> <span>Onboarding Options</span>
                    </a>
                </li>




                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus('backend.settings.*') }}" href="#sidebarMultilevel"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus('backend.settings.*', 'show') }}"
                        id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.profile.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.profile.*') }}"
                                    data-key="t-level-1.1"> Profile Settings </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.system.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.system.*') }}"
                                    data-key="t-level-1.1"> System Settings </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.mail.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.mail.*') }}"
                                    data-key="t-level-1.1"> Mail Settings</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
