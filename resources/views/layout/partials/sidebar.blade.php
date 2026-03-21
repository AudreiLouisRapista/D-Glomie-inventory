 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="{{ route('admin.dashboard') }}" class="brand-link">
         <img src="{{ asset('dist/img/LOME-logo.jpg') }}" alt="LOME Logo" class="brand-image img-circle elevation-5"
             style="opacity: .8 ">
         <span class="brand-text  font-weight-bold">LOME SHOP MART</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="{{ route('admin_profile') }}" class="d-block">{{ session('name') }}</a>
             </div>
         </div>

         <!-- SidebarSearch Form -->
         <div class="form-inline">
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
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">


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
                     <a href="{{ route('view_section') }}"
                         class="nav-link {{ request()->routeIs('view_section') ? 'active' : '' }}">
                         <i class="nav-icon fas fa-box"></i>
                         <p>
                             Product Management

                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('view_schedule') }}"
                         class="nav-link {{ request()->routeIs('view_schedule') ? 'active' : '' }}">

                         <i class="nav-icon fas fa-boxes"></i>
                         <p>
                             Inventory Management

                         </p>
                     </a>
                 </li>


                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-tree"></i>
                         <p>
                             UI Elements
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="pages/UI/general.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>General</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/icons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Icons</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/buttons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Buttons</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/sliders.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Sliders</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/modals.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Modals & Alerts</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/navbar.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Navbar & Tabs</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/timeline.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Timeline</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="pages/UI/ribbons.html" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Ribbons</p>
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
                 <li class="nav-header">EXAMPLES</li>
                 <li class="nav-item">
                     <a href="pages/calendar.html" class="nav-link">
                         <i class="nav-icon fas fa-calendar-alt"></i>
                         <p>
                             Calendar
                             <span class="badge badge-info right">2</span>
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

                 <div class="w-100 border-top border-secondary pt-2 pb-2">
                     <ul class="nav nav-sidebar">
                         <li class="nav-item">
                             <a href="{{ route('logout') }}" class="nav-link">
                                 <i class="nav-icon fas fa-sign-out-alt"></i>
                                 <p>Log out</p>
                             </a>
                         </li>
                     </ul>
                 </div>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>
