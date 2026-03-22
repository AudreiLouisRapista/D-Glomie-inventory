 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">

     <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

     <!-- Brand Logo -->
     <a href="{{ route('admin.dashboard') }}" class="brand-link">
         <img src="{{ asset('dist/img/LOME-logo.jpg') }}" alt="LOME Logo" class="brand-image img-circle elevation-5"
             style="opacity: .8 ">
         <span class="brand-text  font-weight-bold">LOME SHOP MART</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar d-flex flex-column" style="height: 100%">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3">
             <div class="image">
                 <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="{{ route('admin_profile') }}" class="d-block">{{ session('name') }}</a>
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
                     <a href="{{ route('admin.dashboard') }}"
                         class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                         <i class="nav-icon fas fa-th"></i>
                         <p>
                             Dashboard

                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('product') }}"
                         class="nav-link {{ request()->routeIs('product') ? 'active' : '' }}">
                         <i class="nav-icon fas fa-box"></i>
                         <p>
                             Product

                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('inventory') }}"
                         class="nav-link {{ request()->routeIs('inventory') ? 'active' : '' }}">

                         <i class="nav-icon fas fa-boxes"></i>
                         <p>
                             Inventory

                         </p>
                     </a>
                 </li>

                 <li class="nav-header">Reports</li>
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="bi bi-clipboard-data-fill"></i>
                         <p>
                             Inventory Report
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/UI/timeline.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Stock Report</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/ribbons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Inventory Sales Report</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-edit"></i>
                         <p>
                             Forms
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/forms/general.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>General Elements</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/forms/advanced.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Advanced Elements</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/forms/editors.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Editors</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/forms/validation.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Validation</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-table"></i>
                         <p>
                             Tables
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/tables/simple.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Simple Tables</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/tables/data.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>DataTables</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/tables/jsgrid.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>jsGrid</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-header">Invoice</li>
                 <li class="nav-item">
                     <a href="{{ route('invoiceEncoder') }}"
                         class="nav-link {{ request()->routeIs('invoiceEncoder') ? 'active' : '' }}">
                         <i class="nav-icon bi bi-card-heading"></i>
                         <p>
                             Invoice Encoder
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="nav-icon far fa-image"></i>
                         <p>
                             Gallery
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/kanban.html" class="nav-link">
                         <i class="nav-icon fas fa-columns"></i>
                         <p>
                             Kanban Board
                         </p>
                     </a>
                 </li>
                 <li class="nav-header">Imports</li>
                 <li class="nav-item">
                     <a href="pages/kanban.html" class="nav-link">
                         <i class="nav-icon fas fa-columns"></i>
                         <p>
                             Import History
                         </p>
                     </a>
                 </li>

             </ul>

         </nav>
         <div class="user-panel mt-auto border-top border-secondary">
             <ul class="nav nav-pills nav-sidebar flex-column">
                 <li class="nav-item">
                     <a href="{{ route('logout') }}" class="nav-link">
                         <i class="nav-icon fas fa-sign-out-alt"></i>
                         <p>Log out</p>
                     </a>
                 </li>
             </ul>
         </div>
         <!-- /.sidebar-menu -->
     </div>

     <!-- /.sidebar -->
 </aside>
