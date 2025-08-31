@extends('layouts.master')

@section('title', '- Detail Supplier')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="mr-3">Detail Supplier [<span id="productID">{{ $supplier->nama }}</span>]</h3>
      </div>
      <div class="col-sm-6 d-flex flex-column align-items-end">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Supplier</a></li>
          <li class="breadcrumb-item active">Detail Supplier</li>
        </ol>
      </div>
    </div>

    @if ($supplier)
    <div class="row">
      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Nama Supplier</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $supplier->nama }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Telepon</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $supplier->telepon }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Alamat</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $supplier->alamat }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Email</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $supplier->email }}</div>
    </div>
    @else
    <p>Data produk tidak ditemukan.</p>
    @endif
  </div><!-- /.container-fluid -->
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/transaksi-index.js') }}"></script>
@endpush