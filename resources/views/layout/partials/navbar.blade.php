 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>AdminLTE 3 | Navbar & Tabs</title>

     <!-- Google Font: Source Sans Pro -->
     <link rel="stylesheet"
         href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


     <!-- Google Font: Source Sans Pro -->
     <link rel="stylesheet"
         href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

     <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

     <!-- AdminLTE CSS - THIS WAS MISSING! -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">



 </head>

 <body class="hold-transition sidebar-mini">
     <div class="wrapper">
         <!-- Navbar -->
         <nav class="main-header navbar navbar-expand navbar-light sticky-top ">
             <!-- Left navbar links -->
             <ul class="navbar-nav">
                 <li class="nav-item">
                     <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                             class="fas fa-bars"></i></a>
                 </li>
                 <li class="nav-item d-none d-sm-inline-block">
                     <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                 </li>
                 <li class="nav-item d-none d-sm-inline-block">
                     <a href="#" class="nav-link">Contact</a>
                 </li>
             </ul>

             <!-- Right navbar links -->
             <ul class="navbar-nav ml-auto">
                 <!-- Navbar Search -->
                 <li class="nav-item">
                     <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                         <i class="fas fa-search"></i>
                     </a>
                     <div class="navbar-search-block">
                         <form class="form-inline">
                             <div class="input-group input-group-sm">
                                 <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                     aria-label="Search">
                                 <div class="input-group-append">
                                     <button class="btn btn-navbar" type="submit">
                                         <i class="fas fa-search"></i>
                                     </button>
                                     <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                         <i class="fas fa-times"></i>
                                     </button>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </li>

                 <!-- Notifications Dropdown Menu -->
                 <li class="nav-item dropdown">
                     <a class="nav-link" data-toggle="dropdown" href="#">
                         <i class="far fa-bell"></i>
                         <span class="badge badge-warning navbar-badge">15</span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                         <span class="dropdown-item dropdown-header">15 Notifications</span>
                         <div class="dropdown-divider"></div>
                         <a href="#" class="dropdown-item">
                             <i class="fas fa-envelope mr-2"></i> 4 new messages
                             <span class="float-right text-muted text-sm">3 mins</span>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a href="#" class="dropdown-item">
                             <i class="fas fa-users mr-2"></i> 8 friend requests
                             <span class="float-right text-muted text-sm">12 hours</span>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a href="#" class="dropdown-item">
                             <i class="fas fa-file mr-2"></i> 3 new reports
                             <span class="float-right text-muted text-sm">2 days</span>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                     </div>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                         <i class="fas fa-expand-arrows-alt"></i>
                     </a>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link" data-toggle="dropdown" href="#"><i class="far fa-user-circle"></i>
                         admin <i class="fas fa-caret-down"></i></a>
                     <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                         <span class="dropdown-item dropdown-header">Account Management</span>
                         <div class="dropdown-divider"></div>
                         <a href="{{ route('admin_profile') }}" class="dropdown-item">
                             <i class="bi bi-person-fill-gear"></i> Profile
                         </a>
                         <div class="dropdown-divider"></div>
                         <a href="{{ route('logout') }}" class="dropdown-item">
                             <i class="fas fa-sign-out-alt mr-2"></i> Sign-out
                         </a>
                     </div>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"
                         role="button">
                         <i class="fas fa-th-large"></i>
                     </a>
                 </li>
             </ul>
         </nav>
