@extends('layouts.master')

@section('title', '- Transaksi')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-start align-items-center mb-3 mt-2">
    <div class="d-flex align-items-center">
      <h2 class="mr-3">Daftar Penjualan</h2>
      <button type="button" id="btnRefreshTransaksi" class="btn btn-outline-success mr-2">Refresh</button>
      <a href="{{ route('transaksis.create') }}" class="btn btn-primary">Buat Baru</a>
    </div>

    <div class="ml-auto">
      <div class="input-group input-group-sm" style="width: 150px; margin-left: auto">
        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

        <div class="input-group-append">
          <button type="submit" class="btn btn-default">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>

  </div>

  @if ($data->isEmpty())
  <p>No data</p>
  @else
  <div class="card" id="transaksiContainer">
    @include('transaksi.index-data-container')
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