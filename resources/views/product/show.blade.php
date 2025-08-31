@extends('layouts.master')

@section('title', '- Detail Produk')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="mr-3">Detail Produk <span id="productID">{{ $product->id }}</span></h3>
      </div>
      <div class="col-sm-6 d-flex flex-column align-items-end">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
          <li class="breadcrumb-item active">Detail Produk</li>
        </ol>
      </div>
    </div>

    @if ($product)
    <div class="row">
      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Nama Produk</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->nama }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Stok</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->stok }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Satuan</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->satuan }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Harga Beli</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-2 d-flex justify-content-between">
        <span>Rp</span><span>{{ number_format($product->harga_beli, 0, ',', '.') }}</span>
      </div>
      <div class="col-sm-8"></div>

      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Harga Jual</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-2 d-flex justify-content-between">
            <span>Rp</span><span>{{ number_format($product->harga_jual, 0, ',', '.') }}</span>
          </div>
          <div class="col-sm-8"></div>
        </div>
      </div>
      <!-- <p>&nbsp;</p> -->

      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Kategori</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-10">{{ $product->category->nama }}</div>
        </div>
      </div>
      <!-- <p>&nbsp;</p> -->
       
      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Supplier</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-10">
            <a href="{{ route('suppliers.show', $product->supplier->id) }}">{{ $product->supplier->nama }}</a>
          </div>
        </div>
      </div>

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