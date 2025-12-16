<style>
    .comp-logo {
        justify-content: center;
        align-items: center;
        display: flex;
        padding-left: 20px;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link mr-5">
        <div class="comp-logo">
            <img src="{{ asset('staffing_company/dist/img/staffing_company_logos.png') }}" alt="Logo"
                class="brand-image img-fluid" style="opacity:.7">
            <span class="brand-text font-weight-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="{{ route('home') }}" class="d-block">{{ _user()->name ?? '' }}</a>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fa fa-tachometer-alt"></i>
                        <p>{{ __('Staffing_Company/side_bar.dashboard') }}</p>
                    </a>
                </li>

                <!-- Profile -->
                <li class="nav-item">
                    <a href="{{ route('user-profile') }}" class="nav-link">
                        <i class="nav-icon fa fa-user"></i>
                        <p>{{ __('Staffing_Company/side_bar.user_profile') }}</p>
                    </a>
                </li>

                <!-- Staff Function -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Staffing_Company/side_bar.staff_function') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (_isUserAdmin() || _isUserRightToSee(1))
                            <li class="nav-item">
                                <a href="{{ route('personnels.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.staff') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserRightToSee(2))
                            <li class="nav-item">
                                <a href="{{ route('employee_functions.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.staff_function') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Container Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Staffing_Company/side_bar.container_management') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (_isUserAdmin() || _isUserRightToSee(9))
                            <li class="nav-item">
                                <a href="{{ route('container-supplier.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.container_supplier') }}</p>
                                </a>
                            </li>
                        @endif
                        {{-- Quotations (Parent) --}}
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>{{ __('Staffing_Company/side_bar.quotations') }} <i
                                        class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('quotations.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Staffing_Company/side_bar.container_qoutation') }}</p>
                                    </a>
                                </li>
                                {{-- Handover list --}}
                                <li class="nav-item">
                                    <a href="{{ route('offers.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Staffing_Company/offers/index.handover_cleaning') }}</p>
                                    </a>
                                </li>

                                {{-- Retail list --}}
                                <li class="nav-item">
                                    <a href="{{ route('retail-offers.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Staffing_Company/offers/index.retail_bouw') }}</p>
                                    </a>
                                </li>

                                {{-- Site/Hut list --}}
                                <li class="nav-item">
                                    <a href="{{ route('site-offers.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Staffing_Company/offers/index.site_maintenance') }}</p>
                                    </a>
                                </li>

                                {{-- MultiService list if you add one later --}}
                                <li class="nav-item">
                                    <a href="{{ route('multiservice-offers.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Staffing_Company/side_bar.multi_service') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        @if (_isUserAdmin() || _isUserRightToSee(10))
                            <li class="nav-item">
                                <a href="{{ route('order-waste-container.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.order_waste_container') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Customer Heading -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Staffing_Company/side_bar.customer_heading') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (_isUserAdmin() || _isUserMarketing() || _isUserRightToSee(3))
                            <li class="nav-item">
                                <a href="{{ route('customers.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.customer') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserRightToSee(4))
                            <li class="nav-item">
                                <a href="{{ route('departments.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-industry"></i>
                                    <p>{{ __('Staffing_Company/side_bar.department') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserMarketing() || _isUserRightToSee(5))
                            <li class="nav-item">
                                <a href="{{ route('staffing_projects.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-briefcase"></i>
                                    <p>{{ __('Staffing_Company/side_bar.project') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserMarketing() || _isUserRightToSee(5))
                            <li class="nav-item">
                                <a href="{{ route('camp-maintenance.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-briefcase"></i>
                                    <p>{{ __('Staffing_Company/side_bar.camp_maintenance') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserRightToSee(6))
                            <li class="nav-item">
                                <a href="{{ route('project_plannings.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-calendar"></i>
                                    <p>{{ __('Staffing_Company/side_bar.project_planning') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserMarketing() || _isUserRightToSee(7))
                            <li class="nav-item">
                                <a href="{{ route('contacts.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-phone-square"></i>
                                    <p>{{ __('Staffing_Company/side_bar.contact') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Management Functions -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Staffing_Company/side_bar.mgmt_functions') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @if (_isUserAdmin() || _isUserRightToSee(8))
                            <li class="nav-item">
                                <a href="{{ route('request_personnels.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>{{ __('Staffing_Company/side_bar.request_staff') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserRightToSee(11))
                            <li class="nav-item">
                                <a href="{{ route('week-state.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-book"></i>
                                    <p>{{ __('Staffing_Company/side_bar.week_statement') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('week-weekly-state') }}" class="nav-link">
                                    <i class="nav-icon fa fa-book"></i>
                                    <p>{{ __('Staffing_Company/side_bar.week_weekly_statement') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (_isUserAdmin() || _isUserRightToSee(12))
                            <li class="nav-item">
                                <a href="{{ route('comments.index') }}" class="nav-link">
                                    <i class="nav-icon fa fa-comment"></i>
                                    <p>{{ __('Staffing_Company/side_bar.comment') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Standalone -->
                <li class="nav-item">
                    <a href="{{ route('employment-agency-overview.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-building"></i>
                        <p>{{ __('Staffing_Company/side_bar.employment_agency_overview') }}</p>
                    </a>
                </li>

                @if (_isUserAdmin() || _isUserRightToSee(13))
                    <li class="nav-item">
                        <a href="{{ route('employment-agencies.index') }}" class="nav-link">
                            <i class="nav-icon fa fa-building"></i>
                            <p>{{ __('Staffing_Company/side_bar.employment_agency') }}</p>
                        </a>
                    </li>
                @endif

                @if (_isUserAdmin() || _isUserRightToSee(14))
                    <li class="nav-item">
                        <a href="{{ route('user-options.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Staffing_Company/side_bar.right_modules') }}</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('activity-logs') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Staffing_Company/side_bar.activity_log') }}</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
