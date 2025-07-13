@extends('layouts.app')
@section('title', 'Manajemen Arsip')
@section('page-title', 'Manajemen Arsip')
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between alignitems-center">
            <h5 class="mb-0">Daftar Arsip</h5>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('archives.create') }}" class="btn btnprimary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Arsip
            </a>
            @endif
         </div>
         <div class="card-body">
            <!-- Filter dan Search -->
            <div class="row mb-4">
               <div class="col-md-4">
                  <form method="GET" action="{{ route('archives.index')
                     }}">
                     <div class="input-group">
                        <input type="text" class="form-control"
                           name="search"
                           placeholder="Cari arsip..." value="{{
                           request('search') }}">
                        <button class="btn btn-outline-secondary"
                           type="submit">
                        <i class="bi bi-search"></i>
                        </button>
                     </div>
                  </form>
               </div>
               <div class="col-md-3">
                  <form method="GET" action="{{ route('archives.index')
                     }}">
                     <select class="form-select" name="category"
                        onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category }}" {{
                        request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                        </option>
                        @endforeach
                     </select>
                  </form>
               </div>
               <div class="col-md-3">
                  <form method="GET" action="{{ route('archives.index')
                     }}">
                     <select class="form-select" name="status"
                        onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{
                        request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="maintenance" {{
                        request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                     </select>
                  </form>
               </div>
               <div class="col-md-2">
                  <a href="{{ route('archives.index') }}" class="btn
                     btn-outline-secondary w-100">
                  <i class="bi bi-arrow-clockwise"></i> Reset
                  </a>
               </div>
            </div>
            <!-- Tabel Arsip -->
            <div class="table-responsive">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($archives as $archive)
                     <tr>
                        <td>
                           <strong>{{ $archive->code }}</strong>
                        </td>
                        <td>
                           <div>
                              <strong>{{ $archive->title }}
                              </strong>
                              @if($archive->description)
                              <br><small class="text-muted">{{
                              Str::limit($archive->description, 50) }}</small>
                              @endif
                           </div>
                        </td>
                        <td>
                           @if($archive->category)
                           <span class="badge bg-secondary">{{
                           $archive->category }}</span>
                           @else
                           <span class="text-muted">-</span>
                           @endif
                        </td>
                        <td>
                           {{ $archive->location }}
                           @if($archive->shelf_number)
                           <br><small class="text-muted">Rak: {{
                           $archive->shelf_number }}</small>
                           @endif
                        </td>
                        <td>{{ $archive->year ?? '-' }}</td>
                        <td>
                           @switch($archive->status)
                           @case('available')
                           <span class="badge statusavailable">Tersedia</span>
                           @break
                           @case('borrowed')
                           <span class="badge statusborrowed">Dipinjam</span>
                           @break
                           @case('maintenance')
                           <span class="badge statusmaintenance">Maintenance</span>
                           @break
                           @endswitch
                        </td>
                        <td>
                           @switch($archive->condition)
                           @case('good')
                           <span class="badge bgsuccess">Baik</span>
                           @break
                           @case('fair')
                           <span class="badge bgwarning">Cukup</span>
                           @break
                           @case('poor')
                           <span class="badge bgdanger">Buruk</span>
                           @break
                           @endswitch
                        </td>
                        <td>
                           <div class="btn-group" role="group">
                              <a href="{{ route('archives.show',
                                 $archive) }}"
                                 class="btn btn-sm btn-outlineinfo"
                                 data-bs-toggle="tooltip"
                                 title="Lihat Detail">
                              <i class="bi bi-eye"></i>
                              </a>
                              @if(auth()->user()->isAdmin())
                              <a href="{{ route('archives.edit',
                                 $archive) }}"
                                 class="btn btn-sm btn-outlinewarning"
                                 data-bs-toggle="tooltip"
                                 title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <form method="POST" action="{{
                                 route('archives.destroy', $archive) }}" class="d-inline">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn
                                    btn-sm btn-outline-danger btn-delete"
                                    data-bs-toggle="tooltip"
                                    title="Hapus">
                                 <i class="bi bi-trash"></i>
                                 </button>
                              </form>
                              @endif
                           </div>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="8" class="text-center py-4">
                           <i class="bi bi-inbox display-4 textmuted"></i>
                           <p class="mt-2 text-muted">Tidak ada data arsip ditemukan</p>
                        </td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
            <!-- Pagination -->
            @if($archives->hasPages())
            <div class="d-flex justify-content-center mt-4">
               {{ $archives->links() }}
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection