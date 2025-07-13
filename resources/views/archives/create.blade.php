@extends('layouts.app')
@section('title', 'Tambah Arsip')
@section('page-title', 'Tambah Arsip Baru')
@section('content')
<div class="row justify-content-center">
   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h5 class="mb-0">Form Tambah Arsip</h5>
         </div>
         <div class="card-body">
            <form method="POST" action="{{ route('archives.store') }}">
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="code" class="form-label">Kode
                        Arsip <span class="text-danger">*</span></label>
                        <input type="text" class="form-control
                           @error('code') is-invalid @enderror"
                           id="code" name="code" value="{{
                           old('code') }}" required>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="year" class="formlabel">Tahun</label>
                        <input type="number" class="form-control
                           @error('year') is-invalid @enderror"
                           id="year" name="year" value="{{
                           old('year', date('Y')) }}"
                           min="1900" max="{{ date('Y') }}">
                        @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
               </div>
               <div class="mb-3">
                  <label for="title" class="form-label">Judul Arsip
                  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control
                     @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title')
                     }}" required>
                  @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="mb-3">
                  <label for="description" class="formlabel">Deskripsi</label>
                  <textarea class="form-control @error('description')
                     is-invalid @enderror"
                     id="description" name="description" rows="3">{{ old('description') }}</textarea>
                  @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="category" class="formlabel">Kategori</label>
                        <input type="text" class="form-control
                           @error('category') is-invalid @enderror"
                           id="category" name="category" value="
                           {{ old('category') }}"
                           list="category-list">
                        <datalist id="category-list">
                           <option value="Administrasi">
                           <option value="Keuangan">
                           <option value="Kebijakan">
                           <option value="Surat Menyurat">
                           <option value="Laporan">
                        </datalist>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}
                        </div>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-6">
                  <div class="mb-3">
                  <label for="condition" class="formlabel">Kondisi <span class="text-danger">*</span></label>
                  <select class="form-select
                     @error('condition') is-invalid @enderror"
                     id="condition" name="condition"
                     required>
                  <option value="">Pilih Kondisi</option>
                  <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                  <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Cukup</option>
                  <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Buruk</option>
                  </select>
                  @error('condition')
                  <div class="invalid-feedback">{{ $message }}
                  </div>
                  @enderror
                  </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="location" class="formlabel">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control
                           @error('location') is-invalid @enderror"
                           id="location" name="location" value="
                           {{ old('location') }}" required>
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="shelf_number" class="formlabel">Nomor Rak</label>
                        <input type="text" class="form-control
                           @error('shelf_number') is-invalid @enderror"
                           id="shelf_number" name="shelf_number"
                           value="{{ old('shelf_number') }}">
                        @error('shelf_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="box_number" class="formlabel">Nomor Kotak</label>
                        <input type="text" class="form-control
                           @error('box_number') is-invalid @enderror"
                           id="box_number" name="box_number"
                           value="{{ old('box_number') }}">
                        @error('box_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>
               </div>
               <div class="d-flex justify-content-between">
                  <a href="{{ route('archives.index') }}" class="btn
                     btn-secondary">
                  <i class="bi bi-arrow-left me-1"></i>
                  Kembali
                  </a>
                  <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i>
                  Simpan Arsip
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection