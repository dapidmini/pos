@extends('layouts.master')

@section('title', '- Detail Transaksi')

@section('content')
<div class="container-fluid">
  <!-- <div class="d-flex justify-content-start align-items-center mb-3 mt-2"> -->
  <div class="row align-items-center">
    <div class="col-lg-6 mb-1 mt-1">
      <div class="d-flex align-items-center">
        <h2 class="mr-3">Detail Penjualan <span id="detailNoInvoice">{{ $data->kode_invoice }}</span></h2>
      </div>
    </div>

    <div class="col-lg-6 mb-1 mt-1">
      <div class="row">
        <div class="col-12 my-input-group text-right text-lg-right">
          <input type="text" id="filterKeyword" class="form-control-sm mr-2" placeholder="Masukkan nama produk...">
          <button id="btnFilter" class="btn btn-outline-primary btn-sm">Filter</button>
        </div>
      </div>
    </div>

  </div>

  @if ($data->isEmpty())
  <p>No data</p>
  @else
  <div class="card" id="transaksiContainer">
    <p>Daftar produk:</p>
  </div>
  <!-- /.card -->

  <!-- Modal Bootstrap -->
  <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customerModalLabel">Detail Customer</h5>
          <button type="button" class="btn btn-outline-secondary btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <div class="modal-body">
          <!-- Konten nama customer akan diisi lewat JavaScript -->
          <p id="modalCustomerName"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Bootstrap -->
  <div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-labelledby="detailTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailTransaksiModalLabel">
            Detail Transaksi <span id="detailTransaksiNoInvoice"></span>
          </h5>
          <button type="button" class="btn btn-outline-secondary btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <div class="modal-body">
          <!-- Konten nama customer akan diisi lewat JavaScript -->
          <p id="detailTransaksiBody"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="text-center">
        {{ $data->links() }}
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/transaksi-index.js') }}"></script>
@endpush