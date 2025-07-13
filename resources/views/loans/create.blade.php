@extends('layouts.app')
@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman Baru')
@section('content')
<div class="row justify-content-center">
   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h5 class="mb-0">Form Tambah Peminjaman</h5>
         </div>
         <div class="card-body">
            <form method="POST" action="{{ route('loans.store') }}">
               @csrf
               @if(auth()->user()->isAdmin())
               <div class="mb-3">
                  <label for="user_id" class="form-label">Peminjam
                  <span class="text-danger">*</span></label>
                  <select class="form-select @error('user_id') isinvalid @enderror"
                     id="user_id" name="user_id" required>
                     <option value="">Pilih Peminjam</option>
                     @foreach($users as $user)
                     <option value="{{ $user->id }}" {{ old('user_id')
                     == $user->id ? 'selected' : '' }}>
                     {{ $user->name }} ({{ $user->email }})
                     </option>
                     @endforeach
                  </select>
                  @error('user_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               @else
               <input type="hidden" name="user_id" value="{{ auth()-
                  >id() }}">
               <div class="mb-3">
                  <label class="form-label">Peminjam</label>
                  <input type="text" class="form-control" value="{{
                     auth()->user()->name }}" readonly>
               </div>
               @endif
               <div class="mb-3">
                  <label for="archive-search" class="form-label">Arsip
                  <span class="text-danger">*</span></label>
                  <div class="position-relative">
                     <input type="text" class="form-control
                        @error('archive_id') is-invalid @enderror"
                        id="archive-search" placeholder="Ketik
                        untuk mencari arsip..." autocomplete="off">
                     <input type="hidden" id="archive_id"
                        name="archive_id" value="{{ old('archive_id') }}">
                     <div id="search-results" class="position-absolute
                        w-100 bg-white border rounded-bottom shadow-sm"
                        style="top: 100%; z-index: 1000; display:
                        none; max-height: 200px; overflow-y: auto;"></div>
                  </div>
                  @error('archive_id')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                  <small class="form-text text-muted">Mulai ketik kode
                  atau judul arsip untuk mencari</small>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="loan_date" class="formlabel">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control
                           @error('loan_date') is-invalid @enderror"
                           id="loan_date" name="loan_date"
                           value="{{ old('loan_date', date('Y-m-d')) }}" required>
                        @error('loan_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="due_date" class="formlabel">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" class="form-control
                           @error('due_date') is-invalid @enderror"
                           id="due_date" name="due_date" value="
                           {{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                        @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
               </div>
               <div class="mb-3">
                  <label for="purpose" class="form-label">Tujuan Peminjaman</label>
                  <textarea class="form-control @error('purpose') isinvalid @enderror"
                     id="purpose" name="purpose" rows="3"
                     placeholder="Jelaskan tujuan peminjaman arsip ini...">{{ old('purpose') }}</textarea>
                  @error('purpose')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="mb-3">
                  <label for="notes" class="form-label">Catatan</label>
                  <textarea class="form-control @error('notes') isinvalid @enderror"
                     id="notes" name="notes" rows="2"
                     placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                  @error('notes')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="d-flex justify-content-between">
                  <a href="{{ route('loans.index') }}" class="btn btnsecondary">
                  <i class="bi bi-arrow-left me-1"></i>
                  Kembali
                  </a>
                  <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i>
                  Simpan Peminjaman
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script>
   $(document).ready(function() {
        // Set minimum date for due_date based on loan_date
        $('#loan_date').on('change', function() {
            var loanDate = new Date($(this).val());
            var nextDay = new Date(loanDate);
            nextDay.setDate(nextDay.getDate() + 1);
            var minDate = nextDay.toISOString().split('T')[0];
            $('#due_date').attr('min', minDate);

            // If current due_date is before loan_date, update it
            var currentDueDate = new Date($('#due_date').val());
            if (currentDueDate <= loanDate) {
                var suggestedDate = new Date(loanDate);
                suggestedDate.setDate(suggestedDate.getDate() + 7);
                $('#due_date').val(suggestedDate.toISOString().split('T')[0]);
            }
        });

        // Trigger change event on page load
        $('#loan_date').trigger('change');
   });
</script>
@endpush