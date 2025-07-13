@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
@if(auth()->user()->isAdmin())
<!-- Admin Dashboard -->
<div class="row mb-4">
   <div class="col-lg-3 col-md-6 mb-4">
      <div class="card bg-primary text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['total_archives'] }}</h4>
                  <p class="mb-0">Total Arsip</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-archive display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-3 col-md-6 mb-4">
      <div class="card bg-success text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['available_archives'] }}</h4>
                  <p class="mb-0">Arsip Tersedia</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-check-circle display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-3 col-md-6 mb-4">
      <div class="card bg-warning text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['active_loans'] }}</h4>
                  <p class="mb-0">Sedang Dipinjam</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-journal-bookmark display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-3 col-md-6 mb-4">
      <div class="card bg-danger text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['overdue_loans'] }}</h4>
                  <p class="mb-0">Terlambat</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-exclamation-triangle display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h5 class="mb-0">Peminjaman Terbaru</h5>
         </div>
         <div class="card-body">
            @if($data['recent_loans']->count() > 0)
            <div class="table-responsive">
               <table class="table table-sm">
                  <thead>
                     <tr>
                        <th>Peminjam</th>
                        <th>Arsip</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($data['recent_loans'] as $loan)
                     <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->archive->code }}</td>
                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td>
                           @if($loan->isOverdue())
                           <span class="badge bgdanger">Terlambat</span>
                           @elseif($loan->status == 'borrowed')
                           <span class="badge bg warning">Dipinjam</span>
                           @else
                           <span class="badge bgsuccess">Dikembalikan</span>
                           @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            @else
            <p class="text-muted text-center py-3">Belum ada
               peminjaman
            </p>
            @endif
         </div>
      </div>
   </div>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-header">
            <h5 class="mb-0">Statistik Cepat</h5>
         </div>
         <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
               <span>Total Pengguna:</span>
               <strong>{{ $data['total_users'] }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-3">
               <span>Total Peminjaman:</span>
               <strong>{{ $data['total_loans'] }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-3">
               <span>Arsip Dipinjam:</span>
               <strong>{{ $data['borrowed_archives'] }}</strong>
            </div>
            <hr>
            <div class="text-center">
               <a href="{{ route('archives.index') }}" class="btn btnprimary btn-sm me-2">
               <i class="bi bi-archive me-1"></i>
               Kelola Arsip
               </a>
               <a href="{{ route('loans.index') }}" class="btn btnsuccess btn-sm">
               <i class="bi bi-journal-bookmark me-1"></i>
               Kelola Peminjaman
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
@else
<!-- Peminjam Dashboard -->
<div class="row mb-4">
   <div class="col-lg-4 col-md-6 mb-4">
      <div class="card bg-info text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['my_active_loans'] }}</h4>
                  <p class="mb-0">Sedang Dipinjam</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-journal-bookmark display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-4 col-md-6 mb-4">
      <div class="card bg-success text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['my_total_loans'] }}</h4>
                  <p class="mb-0">Total Peminjaman</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-clock-history display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-4 col-md-6 mb-4">
      <div class="card bg-danger text-white">
         <div class="card-body">
            <div class="d-flex justify-content-between">
               <div>
                  <h4 class="mb-0">{{ $data['my_overdue_loans'] }}</h4>
                  <p class="mb-0">Terlambat</p>
               </div>
               <div class="align-self-center">
                  <i class="bi bi-exclamation-triangle display-4"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between alignitems-center">
            <h5 class="mb-0">Riwayat Peminjaman Saya</h5>
            <a href="{{ route('loans.create') }}" class="btn btn-primary
               btn-sm">
            <i class="bi bi-plus-circle me-1"></i>
            Pinjam Arsip
            </a>
         </div>
         <div class="card-body">
            @if($data['recent_loans']->count() > 0)
            <div class="table-responsive">
               <table class="table table-sm">
                  <thead>
                     <tr>
                        <th>Arsip</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($data['recent_loans'] as $loan)
                     <tr class="{{ $loan->isOverdue() ? 'tablewarning' : '' }}">
                        <td>
                           <strong>{{ $loan->archive->code }}
                           </strong>
                           <br><small class="text-muted">{{
                           Str::limit($loan->archive->title, 30) }}</small>
                        </td>
                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td>
                           {{ $loan->due_date->format('d/m/Y') }}
                           @if($loan->isOverdue())
                           <br><small class="textdanger">Terlambat</small>
                           @endif
                        </td>
                        <td>
                           @if($loan->isOverdue())
                           <span class="badge bgdanger">Terlambat</span>
                           @elseif($loan->status == 'borrowed')
                           <span class="badge bgwarning">Dipinjam</span>
                           @else
                           <span class="badge bgsuccess">Dikembalikan</span>
                           @endif
                        </td>
                        <td>
                           <a href="{{ route('loans.show', $loan)
                              }}" class="btn btn-sm btn-outline-info">
                           <i class="bi bi-eye"></i>
                           </a>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            @else
            <div class="text-center py-4">
               <i class="bi bi-inbox display-4 text-muted"></i>
               <p class="mt-2 text-muted">Anda belum memiliki riwayat
                  peminjaman
               </p>
               <a href="{{ route('loans.create') }}" class="btn btnprimary">
               <i class="bi bi-plus-circle me-1"></i>
               Mulai Pinjam Arsip
               </a>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endif
@endsection