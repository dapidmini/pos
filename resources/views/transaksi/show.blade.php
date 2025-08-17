@extends('layouts.master')

@section('title', '- Detail Transaksi')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="mr-3">Detail Penjualan <span id="detailNoInvoice">{{ $transaksi->kode_invoice }}</span></h3>
      </div>
      <div class="col-sm-6 d-flex flex-column align-items-end">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="{{ route('transaksis.index') }}">Penjualan</a></li>
          <li class="breadcrumb-item active">Detail Penjualan</li>
        </ol>
        <div class="row w-100 justify-content-end">
          <div class="col-12 my-input-group text-right text-lg-right">
            <input type="text" id="filterKeyword" class="form-control-sm mr-2" placeholder="Masukkan nama produk...">
            <button id="btnFilter" class="btn btn-outline-primary btn-sm">Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<div class="container-fluid">
  <div class="row">
    <div class="col-4">
      <div>Tanggal Transaksi: {{ $transaksi->tanggal->format('d F Y, H:i') }}</div>
      <div>Nama Customer: {{ $transaksi->nama_customer }}</div>
      <div>Meja: {{ $transaksi->meja }}</div>
      <div>Total: Rp{{ number_format($transaksi->total, 0, ',', '.') }}</div>
    </div>
    <div class="col-8">
      <div>keterangan: {{ $transaksi->keterangan }}</div>
    </div>
  </div>

  @if ($transaksi->details->isEmpty())
  <p>No data</p>
  @else
  <div class="card" id="transaksiContainer">

  </div>
  <!-- /.card -->

  <!-- Modal Bootstrap untuk menampilkan detail produk -->
  <div class="modal fade" id="detailProdukModal" tabindex="-1" aria-labelledby="detailProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailProdukModalLabel">
            Detail Produk <span id="detailProdukNama"></span>
          </h5>
          <button type="button" class="btn btn-outline-secondary btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <div class="modal-body">
          <!-- Konten detail produk akan diisi lewat JavaScript -->
          <p id="detailProdukBody"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.modal detail produk -->
  @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/transaksi-index.js') }}"></script>
@endpush