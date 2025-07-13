@extends('layouts.app')
@section('title', 'Manajemen Peminjaman')
@section('page-title', 'Manajemen Peminjaman')
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between alignitems-center">
            <h5 class="mb-0">Daftar Peminjaman</h5>
            <a href="{{ route('loans.create') }}" class="btn btnprimary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Peminjaman
            </a>
         </div>
         <div class="card-body">
            <!-- Filter -->
            <div class="row mb-4">
               <div class="col-md-3">
                  <form method="GET" action="{{ route('loans.index')
                     }}">
                     <select class="form-select" name="status"
                        onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="borrowed" {{ request('status')
                        == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="returned" {{ request('status')
                        == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="overdue" {{ request('status')
                        == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                     </select>
                  </form>
               </div>
               @if(auth()->user()->isAdmin())
               <div class="col-md-3">
                  <form method="GET" action="{{ route('loans.index')
                     }}">
                     <select class="form-select" name="user_id"
                        onchange="this.form.submit()">
                        <option value="">Semua Peminjam</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{
                        request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                        </option>
                        @endforeach
                     </select>
                  </form>
               </div>
               @endif
               <div class="col-md-3">
                  <form method="GET" action="{{ route('loans.index')
                     }}">
                     <div class="form-check">
                        <input class="form-check-input"
                        type="checkbox" name="overdue" value="1"
                        {{ request('overdue') ? 'checked' : ''
                        }} onchange="this.form.submit()">
                        <label class="form-check-label">
                        Hanya yang terlambat
                        </label>
                     </div>
                  </form>
               </div>
               <div class="col-md-3">
                  <a href="{{ route('loans.index') }}" class="btn btnoutline-secondary w-100">
                  <i class="bi bi-arrow-clockwise"></i> Reset
                  </a>
               </div>
            </div>
            <!-- Tabel Peminjaman -->
            <div class="table-responsive">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Peminjam</th>
                        <th>Arsip</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($loans as $loan)
                     <tr class="{{ $loan->isOverdue() ? 'tablewarning' : '' }}">
                        <td>
                           <div>
                              <strong>{{ $loan->user->name }}
                              </strong>
                              <br><small class="text-muted">{{
                              $loan->user->email }}</small>
                           </div>
                        </td>
                        <td>
                           <div>
                              <strong>{{ $loan->archive->code }}
                              </strong>
                              <br><small class="text-muted">{{
                              Str::limit($loan->archive->title, 30) }}</small>
                           </div>
                        </td>
                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td>
                           {{ $loan->due_date->format('d/m/Y') }}
                           @if($loan->isOverdue())
                           <br><small class="text-danger">
                           <i class="bi bi-exclamationtriangle"></i>
                           Terlambat {{ $loan->due_date-
                           >diffInDays(now()) }} hari
                           </small>
                           @endif
                        </td>
                        <td>
                           {{ $loan->return_date ? $loan-
                           >return_date->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                           @switch($loan->status)
                           @case('borrowed')
                           @if($loan->isOverdue())
                           <span class="badge statusoverdue">Terlambat</span>
                           @else
                           <span class="badge statusborrowed">Dipinjam</span>
                           @endif
                           @break
                           @case('returned')
                           <span class="badge status returned">Dikembalikan</span>
                           @break
                           @endswitch
                        </td>
                        <td>
                           <div class="btn-group" role="group">
                              <a href="{{ route('loans.show',
                                 $loan) }}"
                                 class="btn btn-sm btn-outlineinfo"
                                 data-bs-toggle="tooltip"
                                 title="Lihat Detail">
                              <i class="bi bi-eye"></i>
                              </a>
                              @if(!$loan->isReturned())
                              <a href="{{ route('loans.edit',
                                 $loan) }}"
                                 class="btn btn-sm btn-outlinewarning"
                                 data-bs-toggle="tooltip"
                                 title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <button type="button" class="btn btnsm btn-outline-success"
                                 data-bs-toggle="modal" databs-target="#returnModal{{ $loan->id }}"
                                 data-bs-toggle="tooltip"
                                 title="Kembalikan">
                              <i class="bi bi-arrow-returnleft"></i>
                              </button>
                              @endif
                              @if(auth()->user()->isAdmin() &&
                              $loan->isReturned())
                              <form method="POST" action="{{
                                 route('loans.destroy', $loan) }}" class="d-inline">
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
                     <!-- Return Modal -->
                     @if(!$loan->isReturned())
                     <div class="modal fade" id="returnModal{{ $loan-
                        >id }}" tabindex="-1">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modaltitle">Kembalikan Arsip</h5>
                                 <button type="button" class="btnclose" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST" action="{{
                                 route('loans.return', $loan) }}">
                                 @csrf
                                 @method('PATCH')
                                 <div class="modal-body">
                                    <p>Apakah Anda yakin ingin
                                       mengembalikan arsip <strong>{{ $loan->archive->code }}</strong>?
                                    </p>
                                    <div class="mb-3">
                                       <label
                                          for="return_notes{{ $loan->id }}" class="form-label">Catatan
                                       Pengembalian</label>
                                       <textarea class="formcontrol" id="return_notes{{ $loan->id }}"
                                          name="return_notes" rows="3"
                                          placeholder="Kondisi arsip saat dikembalikan, dll."></textarea>
                                    </div>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button"
                                       class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit"
                                       class="btn btn-success">
                                    <i class="bi bi-checkcircle me-1"></i>
                                    Kembalikan
                                    </button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     @endif
                     @empty
                     <tr>
                        <td colspan="7" class="text-center py-4">
                           <i class="bi bi-inbox display-4 textmuted"></i>
                           <p class="mt-2 text-muted">Tidak ada data
                              peminjaman ditemukan
                           </p>
                        </td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
            <!-- Pagination -->
            @if($loans->hasPages())
            <div class="d-flex justify-content-center mt-4">
               {{ $loans->links() }}
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection