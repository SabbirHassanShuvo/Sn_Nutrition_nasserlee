<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e($settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png')); ?>"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e($settings->logo ? asset($settings->logo) : asset('assets/images/logo-dark.png')); ?>"
                    alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e($settings->mini_logo ? asset($settings->mini_logo) : asset('assets/images/logo-sm.png')); ?>"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e($settings->logo ? asset($settings->logo) : asset('assets/images/logo-light.png')); ?>"
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
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.dashboard.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.dashboard.index')); ?>">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>

               <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super_admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link <?php echo e(getPageStatus(['backend.role.*', 'backend.system-user.*'], 'collapsed active')); ?>"
                            href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarLanding">
                            <i class="ri-admin-line"></i> <span data-key="t-pages">Admin & Roles</span>
                        </a>
                        <div class="collapse menu-dropdown <?php echo e(getPageStatus(['backend.system-user.*', 'backend.role.*'], 'show')); ?>"
                            id="sidebarLanding">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(route('backend.system-user.index')); ?>"
                                        class="nav-link <?php echo e(getPageStatus('backend.system-user.*')); ?>"
                                        data-key="t-starter"> System Admins </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('backend.role.index')); ?>"
                                        class="nav-link <?php echo e(getPageStatus('backend.role.*')); ?>" data-key="t-profile">
                                        Permissions </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_management')): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.app-user.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.app-user.index')); ?>">
                        <i class="ri-group-line"></i> <span>User Management</span>
                    </a>
                </li>
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['categories_manage', 'category_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.category.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.category.index')); ?>">
                        <i class="ri-stack-line"></i> <span>Categories</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['brands_manage', 'brand_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.brand.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.brand.index')); ?>">
                        <i class="ri-medal-line"></i> <span>Brands</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['products_manage', 'product_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.product.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.product.index')); ?>">
                        <i class="ri-store-2-line"></i> <span>Products</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['orders_manage', 'order_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.order.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.order.index')); ?>">
                        <i class="ri-shopping-cart-2-line"></i> <span>Orders</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['promo_codes_manage', 'promo_code_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.promo-code.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.promo-code.index')); ?>">
                        <i class="ri-ticket-line"></i> <span>Promo Codes</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['offers_manage', 'offer_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.offer.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.offer.index')); ?>">
                        <i class="ri-percent-line"></i> <span>Offers & Campaigns</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['specialists_manage', 'specialist_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.specialist.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.specialist.index')); ?>">
                        <i class="ri-user-star-line"></i> <span>Specialists</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.consultation-booking.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.consultation-booking.index')); ?>">
                        <i class="ri-video-chat-line"></i> <span>Consultation Bookings</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['gyms_manage', 'gym_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.gym.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.gym.index')); ?>">
                        <i class="ri-map-pin-user-line"></i> <span>Nearby Gyms</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['pharmacies_manage', 'pharmacy_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.pharmacies.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.pharmacies.index')); ?>">
                        <i class="ri-capsule-line"></i> <span>Pharmacies</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['contact_submissions_manage', 'contact_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.contact-submissions.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.contact-submissions.index')); ?>">
                        <i class="ri-mail-line"></i> 
                        <span>Contact Info</span>
                        <span id="sidebar-contact-badge" class="badge badge-pill bg-danger ms-auto" style="<?php echo e($unreadContactCount > 0 ? '' : 'display: none;'); ?>"><?php echo e($unreadContactCount); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['subscribers_manage', 'subscriber_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.subscribers.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.subscribers.index')); ?>">
                        <i class="ri-notification-badge-line"></i> 
                        <span>Subscribers</span>
                        <span id="sidebar-subscribers-badge" class="badge badge-pill bg-danger ms-auto" style="<?php echo e($unreadSubscriberCount > 0 ? '' : 'display: none;'); ?>"><?php echo e($unreadSubscriberCount); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['onboarding_options_manage', 'onboarding_manage'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.onboarding-option.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.onboarding-option.index')); ?>">
                        <i class="ri-user-settings-line"></i> <span>Onboarding Options</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(request()->routeIs('backend.onboarding-question.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('backend.onboarding-question.index')); ?>">
                       <i class="ri-question-answer-line"></i> <span>Onboarding Questions</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['setting_profile', 'setting_system', 'setting_mail'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(getPageStatus('backend.settings.*')); ?>" href="#sidebarMultilevel"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown <?php echo e(getPageStatus('backend.settings.*', 'show')); ?>"
                        id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_profile')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.settings.profile.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.settings.profile.*')); ?>"
                                    data-key="t-level-1.1"> Profile Settings </a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_system')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.settings.system.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.settings.system.*')); ?>"
                                    data-key="t-level-1.1"> System Settings </a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_system')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.settings.web-setting.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.settings.web-setting.*')); ?>"
                                    data-key="t-level-1.2"> Web Settings </a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_mail')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.settings.mail.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.settings.mail.*')); ?>"
                                    data-key="t-level-1.1"> Mail Settings</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?> 

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['cms_banner', 'cms_pages', 'cms_faq', 'cms_home_page', 'cms_about_page', 'cms_contact_page', 'cms_how_it_works'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(getPageStatus(['backend.banner-section.*', 'backend.page.*', 'backend.feature.faq.*', 'backend.home-page.*', 'backend.about-us.*', 'backend.how-it-works.*', 'backend.contact-us.*'], 'collapsed active')); ?>" href="#sidebarCMS"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarCMS">
                        <i class="ri-pages-line"></i> <span data-key="t-cms">CMS</span>
                    </a>
                    <div class="collapse menu-dropdown <?php echo e(getPageStatus(['backend.banner-section.*', 'backend.page.*', 'backend.feature.faq.*', 'backend.home-page.*', 'backend.about-us.*', 'backend.how-it-works.*', 'backend.contact-us.*'], 'show')); ?>"
                        id="sidebarCMS">
                        <ul class="nav nav-sm flex-column">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_banner')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.banner-section.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.banner-section.*')); ?>"
                                    data-key="t-banner-sections"> Banner Section </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_home_page')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.home-page.quality-control.edit')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.home-page.*')); ?>"
                                    data-key="t-home-page"> Quality Control </a>
                            </li>
                            <?php endif; ?>

                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_about_page')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.about-us.edit')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.about-us.*')); ?>"
                                    data-key="t-about-us"> About Us </a>
                            </li>
                            <?php endif; ?>

                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_how_it_works')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.how-it-works.edit')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.how-it-works.*')); ?>"
                                    data-key="t-how-it-works"> How It Works </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_contact_page')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.contact-us.edit')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.contact-us.*')); ?>"
                                    data-key="t-contact-us"> Contact Us </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog_manage')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.blog.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.blog.*')); ?>"
                                    data-key="t-blogs"> Blogs </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_pages')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.page.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.page.*')); ?>"
                                    data-key="t-pages"> Pages </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['cms_faq'])): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link <?php echo e(getPageStatus(['backend.feature.faq.*'], 'collapsed active')); ?>" href="#sidebarFaq"
                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="sidebarFaq">
                        <i class="ri-question-answer-line"></i> <span data-key="t-faq">FAQ</span>
                    </a>
                    <div class="collapse menu-dropdown <?php echo e(getPageStatus(['backend.feature.faq.*'], 'show')); ?>"
                        id="sidebarFaq">
                        <ul class="nav nav-sm flex-column">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_faq')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.faq-category.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.faq-category.*')); ?>"
                                    data-key="t-faq-categories"> FAQ Categories </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms_faq')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('backend.feature.faq.index')); ?>"
                                    class="nav-link <?php echo e(getPageStatus('backend.feature.faq.*')); ?>"
                                    data-key="t-faqs"> FAQs </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/partials/sidebar.blade.php ENDPATH**/ ?>