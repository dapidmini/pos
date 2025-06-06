@extends('layouts.master') @section('title', '- Transaksi - Create')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Tambah Transaksi</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="{{ route('transaksis.index') }}">Transaksi</a>
            </li>
            <li class="breadcrumb-item active">Tambah Transaksi</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- form start -->
      <form action="{{ route('transaksis.store') }}" method="POST" class="form-horizontal">
        @csrf
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-body">
            <div class="form-group d-flex justify-content-start">
              <label class="form-label mr-2">Tanggal Transaksi:</label>
              <label id="display-tanggal">{{ now()->format('l, d M Y H:i') }}</label>
              <input type="hidden" name="tanggal" id="tanggal" value="{{ now()->format('Y-m-d H:i:s') }}">
              <input type="hidden" id="formattedTanggal" value="{{ now()->format('Ymd') }}">
              {{-- <input type="datetime-local" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}"> --}}
              @error('tanggal')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="row d-flex justify-content-start align-items-top mb-2">
                  <div class="col-sm-2">
                    <label for="nama_customer">
                      Nama Customer
                    </label>
                  </div>
                  <div class="col-sm-10 d-flex justify-content-start">
                    <label class="text-danger mr-2">*</label>
                    <input type="text" class="form-control @error('nama_customer') is-invalid @enderror"
                      id="nama_customer" name="nama_customer" placeholder="Masukkan Nama Customer (maks.50 karakter)"
                      value="{{ old('nama_customer') }}" required autofocus />
                    @error('nama_customer')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="row d-flex justify-content-start align-items-top mb-2">
                  <div class="col-sm-2">
                    <label for="meja">
                      Meja
                    </label>
                  </div>
                  <div class="col-sm-10 d-flex justify-content-start">
                    <label class="text-danger mr-2">*</label>
                    <input type="text" class="form-control @error('meja') is-invalid @enderror" id="meja"
                      name="meja" placeholder="Masukkan No. Meja" value="{{ old('meja') }}" required />
                    @error('meja')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="row d-flex justify-content-start align-items-top mb-2">
                  <div class="col-sm-2">
                    <label for="diskon">
                      Diskon
                    </label>
                  </div>
                  <div class="col-sm-10 d-flex justify-content-start">
                    <label class="text-danger mr-2">*</label>
                    <input type="text" class="form-control @error('diskon') is-invalid @enderror" id="diskon"
                      name="diskon" placeholder="Masukkan Diskon" value="{{ old('diskon') }}" required />
                    @error('diskon')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-3 text-right">
                    <label for="keterangan">Keterangan</label>
                  </div>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Masukkan Alamat Supplier"
                      required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            Detail Transaksi
            <button type="button" class="btn btn-success btn-sm float-right" id="add-detail-item" data-toggle="modal"
              data-target="#modalSearchProduct">
              Tambah Item
            </button>
          </div>
          <div class="card-body"></div>
        </div>

        <button type="submit" class="btn btn-primary">
          Submit
        </button>
      </form>
    </div>
    <!-- /.container-fluid -->

    {{-- MODAL PENCARIAN PRODUK --}}
    <div class="modal fade" id="modalSearchProduct" tabindex="-1" role="dialog"
      aria-labelledby="modalSearchProductLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalSearchProductLabel">Cari & Tambah Produk</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <input type="text" id="modalProduct-FilterNama" class="form-control"
                placeholder="Cari produk berdasarkan nama, kategori, atau supplier...">
            </div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
              <table class="table table-bordered table-striped" id="modalProduct-Table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($products)
                    @foreach ($products as $i => $product)
                      <tr>
                        <td>{{ $i+1 }}.</td>
                        <td>{{ $product->nama }}</td>
                        <td>{{ $product->category->nama }}</td>
                        <td>{{ $product->supplier->nama }}</td>
                        <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ number_format($product->stok, 0, ',', '.') . ' ' . $product->satuan }}</td>
                        <td>
                          <button class="btn btn-info" id="btnPilih-{{ $product->id }}" 
                            data-id="{{ $product->id }}"
                            data-nama="{{ $product->nama }}"
                            data-id_kategori="{{ $product->id_kategori }}"
                            data-nama_kategori="{{ $product->category->nama }}"
                            data-id_supplier="{{ $product->id_supplier }}"
                            data-nama_supplier="{{ $product->supplier->nama }}"
                            data-harga_jual="{{ $product->harga_jual }}"
                            data-stok="{{ $product->stok }}"
                          >Pilih</button></td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Template untuk item detail baru (sembunyikan dari tampilan awal) --}}
    {{-- <template id="detail-item-template">
      @include('transaksi._detail_item', [
          'index' => '__INDEX__',
          'products' => $products,
          'detail' => null,
      ])
    </template> --}}
  </section>
  <!-- /.content -->
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      const modalSearchProduct = $('#modalSearchProduct');

      modalSearchProduct.on('click', 'button[id^="btnPilih-"]', function() {
        const btnPilih = $(this);
        const productId = btnPilih.data('id');
        const pilih = { // data produk yang barusan dipilih di popup modal
          id : btnPilih.data('id'),
          nama : btnPilih.data('nama'),
          id_kategori : btnPilih.data('id_kategori'),
          nama_kategori : btnPilih.data('nama_kategori'),
          id_supplier : btnPilih.data('id_supplier'),
          nama_supplier : btnPilih.data('nama_supplier'),
          harga_jual : btnPilih.data('harga_jual'),
          stok : btnPilih.data('stok'),
        }
        console.log('pilih', pilih);
        
        modalSearchProduct.modal('hide');
        console.log('barang yang dipilih', pilih);
      });
      return;
      const invoiceLabel = document.querySelector('#display-kode-invoice');
      let counter = 1;
      let kodeInvoice = 'INV'; // INV2025060600001

      let detailIndex = {{ old('details') ? count(old('details')) : 1 }};

      const detailsContainer = document.getElementById('details-container');
      const addDetailButton = document.getElementById('add-detail');
      const detailItemTemplate = document.getElementById('detail-item-template');

      function calculateSubtotal(detailItem) {
        const inputJumlah = detailItem.find()
      }
    });
  </script>
@endpush
