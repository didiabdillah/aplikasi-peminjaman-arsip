<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700"
         rel="stylesheet" />
      <!-- Icons -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrapicons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
      <!-- Scripts -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
   </head>
   <body>
      <div class="d-flex">
         <!-- Sidebar -->
         <nav class="sidebar d-flex flex-column p-3" style="width: 250px;">
            <a href="{{ route('dashboard') }}" class="navbar-brand text-white
               text-decoration-none mb-4">
            <i class="bi bi-archive-fill me-2"></i>
            <span>Arsip Lembaga</span>
            </a>
            <ul class="nav nav-pills flex-column mb-auto">
               <li class="nav-item">
                  <a href="{{ route('dashboard') }}" class="nav-link {{
                     request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                  </a>
               </li>
               <li class="nav-item">
                  <a href="{{ route('archives.index') }}" class="nav-link
                     {{ request()->routeIs('archives.*') ? 'active' : '' }}">
                  <i class="bi bi-archive me-2"></i>
                  Manajemen Arsip
                  </a>
               </li>
               <li class="nav-item">
                  <a href="{{ route('loans.index') }}" class="nav-link {{
                     request()->routeIs('loans.*') ? 'active' : '' }}">
                  <i class="bi bi-journal-bookmark me-2"></i>
                  Peminjaman
                  </a>
               </li>
               @if(auth()->user()->isAdmin())
               <li class="nav-item">
                  <a href="#" class="nav-link">
                  <i class="bi bi-people me-2"></i>
                  Manajemen User
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link">
                  <i class="bi bi-bar-chart me-2"></i>
                  Laporan
                  </a>
               </li>
               @endif
            </ul>
            <hr class="text-white-50">
            <div class="dropdown">
               <a href="#" class="d-flex align-items-center text-white textdecoration-none dropdown-toggle" data-bs-toggle="dropdown">
               <i class="bi bi-person-circle me-2"></i>
               <span>{{ auth()->user()->name }}</span>
               </a>
               <ul class="dropdown-menu dropdown-menu-dark text-small
                  shadow">
                  <li><a class="dropdown-item" href="{{
                     route('profile.edit') }}">
                     <i class="bi bi-gear me-2"></i>Pengaturan
                     </a>
                  </li>
                  <li>
                     <hr class="dropdown-divider">
                  </li>
                  <li>
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                        <i class="bi bi-box-arrow-right me-2">
                        </i>Logout
                        </button>
                     </form>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- Main Content -->
         <div class="flex-grow-1">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white borderbottom">
               <div class="container-fluid">
                  <h1 class="navbar-brand mb-0 h1">@yield('page-title',
                     'Dashboard')
                  </h1>
                  <div class="d-flex align-items-center">
                     <span class="badge bg-primary me-3">{{
                     ucfirst(auth()->user()->role) }}</span>
                     <div class="dropdown">
                        <button class="btn btn-outline-secondary
                           dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger roundedpill">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                           <li>
                              <h6 class="dropdownheader">Notifikasi</h6>
                           </li>
                           <li><a class="dropdown-item" href="#">Arsip
                              ARS-001 akan jatuh tempo</a>
                           </li>
                           <li><a class="dropdown-item"
                              href="#">Peminjaman baru menunggu persetujuan</a></li>
                           <li><a class="dropdown-item" href="#">Arsip
                              ARS-003 telah dikembalikan</a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </nav>
            <!-- Page Content -->
            <main class="main-content">
               <!-- Alerts -->
               @if(session('success'))
               <div class="alert alert-success alert-dismissible fade show"
                  role="alert">
                  <i class="bi bi-check-circle me-2"></i>
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bsdismiss="alert"></button>
               </div>
               @endif
               @if(session('error'))
               <div class="alert alert-danger alert-dismissible fade show"
                  role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bsdismiss="alert"></button>
               </div>
               @endif
               @if($errors->any())
               <div class="alert alert-danger alert-dismissible fade show"
                  role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  <strong>Terjadi kesalahan:</strong>
                  <ul class="mb-0 mt-2">
                     @foreach($errors->all() as $error)
                     <li>{{ $error }}</li>
                     @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bsdismiss="alert"></button>
               </div>
               @endif
               @yield('content')
            </main>
         </div>
      </div>
      <!-- Loading overlay -->
      <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100
         d-none" style="background: rgba(0,0,0,0.5); z-index: 9999;">
         <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-light" role="status">
               <span class="visually-hidden">Loading...</span>
            </div>
         </div>
      </div>
      @stack('scripts')
   </body>
</html>