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

                @role('super_admin')
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
                @endrole

                @can('user_management')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.app-user.*') ? 'active' : '' }}"
                        href="{{ route('backend.app-user.index') }}">
                        <i class="ri-group-line"></i> <span>User Management</span>
                    </a>
                </li>
                @endcan

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

                @canany(['categories_manage', 'category_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.category.*') ? 'active' : '' }}"
                        href="{{ route('backend.category.index') }}">
                        <i class="ri-stack-line"></i> <span>Categories</span>
                    </a>
                </li>
                @endcanany

                @canany(['brands_manage', 'brand_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.brand.*') ? 'active' : '' }}"
                        href="{{ route('backend.brand.index') }}">
                        <i class="ri-medal-line"></i> <span>Brands</span>
                    </a>
                </li>
                @endcanany

                @canany(['products_manage', 'product_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.product.*') ? 'active' : '' }}"
                        href="{{ route('backend.product.index') }}">
                        <i class="ri-store-2-line"></i> <span>Products</span>
                    </a>
                </li>
                @endcanany

                @canany(['orders_manage', 'order_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.order.*') ? 'active' : '' }}"
                        href="{{ route('backend.order.index') }}">
                        <i class="ri-shopping-cart-2-line"></i> <span>Orders</span>
                    </a>
                </li>
                @endcanany

                @canany(['promo_codes_manage', 'promo_code_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.promo-code.*') ? 'active' : '' }}"
                        href="{{ route('backend.promo-code.index') }}">
                        <i class="ri-ticket-line"></i> <span>Promo Codes</span>
                    </a>
                </li>
                @endcanany

                @canany(['onboarding_options_manage', 'onboarding_manage'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('backend.onboarding-option.*') ? 'active' : '' }}"
                        href="{{ route('backend.onboarding-option.index') }}">
                        <i class="ri-user-settings-line"></i> <span>Onboarding Options</span>
                    </a>
                </li>
                @endcanany

                @canany(['setting_profile', 'setting_system', 'setting_mail'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus('backend.settings.*') }}" href="#sidebarMultilevel"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus('backend.settings.*', 'show') }}"
                        id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            @can('setting_profile')
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.profile.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.profile.*') }}"
                                    data-key="t-level-1.1"> Profile Settings </a>
                            </li>
                            @endcan
                            @can('setting_system')
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.system.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.system.*') }}"
                                    data-key="t-level-1.1"> System Settings </a>
                            </li>
                            @endcan
                            @can('setting_mail')
                            <li class="nav-item">
                                <a href="{{ route('backend.settings.mail.index') }}"
                                    class="nav-link {{ getPageStatus('backend.settings.mail.*') }}"
                                    data-key="t-level-1.1"> Mail Settings</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany


                {{-- Cms --}}
                @canany(['cms_banner', 'cms_pages', 'cms_faq', 'cms_home_page'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus(['backend.banner-section.*', 'backend.page.*', 'backend.feature.faq.*', 'backend.home-page.*'], 'collapsed active') }}" href="#sidebarCMS"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarCMS">
                        <i class="ri-pages-line"></i> <span data-key="t-cms">CMS</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus(['backend.banner-section.*', 'backend.page.*', 'backend.feature.faq.*', 'backend.home-page.*'], 'show') }}"
                        id="sidebarCMS">
                        <ul class="nav nav-sm flex-column">

                            @can('cms_banner')
                            <li class="nav-item">
                                <a href="{{ route('backend.banner-section.index') }}"
                                    class="nav-link {{ getPageStatus('backend.banner-section.*') }}"
                                    data-key="t-banner-sections"> Banner Section </a>
                            </li>
                            @endcan

                            @can('cms_home_page')
                            <li class="nav-item">
                                <a href="{{ route('backend.home-page.quality-control.edit') }}"
                                    class="nav-link {{ getPageStatus('backend.home-page.*') }}"
                                    data-key="t-home-page"> Quality Control </a>
                            </li>
                            @endcan

                             @can('cms_about_page')
                            <li class="nav-item">
                                <a href="{{ route('backend.about-us.edit') }}"
                                    class="nav-link {{ getPageStatus('backend.about-us.*') }}"
                                    data-key="t-about-us"> About Us </a>
                            </li>
                            @endcan

                            @can('cms_pages')
                            <li class="nav-item">
                                <a href="{{ route('backend.page.index') }}"
                                    class="nav-link {{ getPageStatus('backend.page.*') }}"
                                    data-key="t-pages"> Pages </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany

                {{-- Faq --}}
                @canany(['cms_faq'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ getPageStatus(['backend.feature.faq.*'], 'collapsed active') }}" href="#sidebarFaq"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarFaq">
                        <i class="ri-question-answer-line"></i> <span data-key="t-faq">FAQ</span>
                    </a>
                    <div class="collapse menu-dropdown {{ getPageStatus(['backend.feature.faq.*'], 'show') }}"
                        id="sidebarFaq">
                        <ul class="nav nav-sm flex-column">

                            @can('cms_faq')
                            <li class="nav-item">
                                <a href="{{ route('backend.faq-category.index') }}"
                                    class="nav-link {{ getPageStatus('backend.faq-category.*') }}"
                                    data-key="t-faq-categories"> FAQ Categories </a>
                            </li>
                            @endcan

                            @can('cms_faq')
                            <li class="nav-item">
                                <a href="{{ route('backend.feature.faq.index') }}"
                                    class="nav-link {{ getPageStatus('backend.feature.faq.*') }}"
                                    data-key="t-faqs"> FAQs </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
