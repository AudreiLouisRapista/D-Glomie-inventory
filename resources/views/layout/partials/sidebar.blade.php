<!-- Main Sidebar Container -->
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<aside class="main-sidebar sidebar-light-danger elevation-2">


    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/LOME-logo.jpg') }}" alt="LOME Logo" class="brand-image img-circle elevation-5"
            style="opacity: .8 ">
        <span class="brand-text  font-weight-bold">LOME SHOP MART</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" style="height: 100%">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ asset('dist/img/avatar4.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info ms-3">
                <span class="d-block text-solid-black-50 small fw-bold text-uppercase"
                    style="letter-spacing: 1px; font-size: 10px;">
                    Access Level
                </span>
                <a href="#" class="d-block user-role-link font-weight-bold text-black">
                    {{ session('user_role') }}
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline d-flex">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 flex-column">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">



                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard

                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('product', 'product_archive') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('product', 'product_archive') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Products
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('product') }}"
                                class="nav-link {{ request()->routeIs('product') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product_archive') }}"
                                class="nav-link {{ request()->routeIs('product_archive') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Archives</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li
                    class="nav-item {{ request()->routeIs('inventory', 'inventory_archive', 'inventory_sales_history') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('inventory', 'inventory_archive', 'inventory_sales_history') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Inventory
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory') }}"
                                class="nav-link {{ request()->routeIs('inventory') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventory Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory_archive') }}"
                                class="nav-link {{ request()->routeIs('inventory_archive') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventory Archives</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory_sales_history') }}"
                                class="nav-link {{ request()->routeIs('inventory_sales_history') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventory Sales History</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('finance', 'DailySalesReport') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('finance', 'DailySalesReport') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>

                        <p>
                            Finance
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('finance') }}"
                                class="nav-link {{ request()->routeIs('finance') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Finance Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('DailySalesReport') }}"
                                class="nav-link {{ request()->routeIs('DailySalesReport') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Daily Sales Report</p>
                            </a>
                        </li>
                    </ul>
                </li>





                <li class="nav-header font-weight-bold">Supplier & Invoice</li>
                <li class="nav-item">
                    <a href="{{ route('invoiceEncoder') }}"
                        class="nav-link {{ request()->routeIs('invoiceEncoder') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-card-heading"></i>
                        <p>
                            Stock in
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('supplierList') }}"
                        class="nav-link {{ request()->routeIs('supplierList') ? 'active' : '' }}">
                        <i class="nav-icon
                        bi bi-person-fill"></i>
                        <p>
                            Supplier list
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('paymentTracker') }}"
                        class="nav-link {{ request()->routeIs('paymentTracker') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-credit-card-fill"></i>
                        <p>
                            Payment Tracker
                        </p>
                    </a>
                </li>




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
