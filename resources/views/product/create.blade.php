@extends('layouts.master')

@section('title', '- Barang - Create')

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Tambah Barang</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Barang</a></li>
            <li class="breadcrumb-item active">Tambah Barang</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="card card-primary">
            <!-- form start -->
            <form action="{{ route('products.store') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="nama">Nama Barang</label>
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                    name="nama" placeholder="Masukkan Nama Kategori (maks.50 karakter)" value="{{ old('nama') }}"
                    required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_kategori">Kategori Barang</label>
                  <select name="id_kategori" id="id_kategori" class="form-control">
                    <option value="">Pilih salah satu kategori</option>
                    @foreach ($categories as $id => $nama)
                      <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                  </select>
                  @error('id_kategori')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_supplier">Supplier</label>
                  <select name="id_supplier" id="id_supplier" class="form-control">
                    <option value="">Pilih salah satu supplier</option>
                    @foreach ($suppliers as $supplier)
                      <option value="{{ $supplier->id }}">{{ $supplier->nama . ' (' . $supplier->telepon . ')' }}</option>
                    @endforeach
                  </select>
                  @error('id_supplier')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="stok">Jumlah</label>
                  <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok"
                    name="stok" placeholder="Masukkan Jumlah Barang" value="{{ old('stok') }}" required>
                  @error('stok')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan</label>
                  <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                    name="satuan" placeholder="Masukkan Satuan Barang" value="{{ old('satuan') }}" required>
                  @error('satuan')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli</label>
                  <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli"
                    name="harga_beli" placeholder="Harga Beli Barang" value="{{ old('harga_beli') }}" required>
                  @error('harga_beli')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual</label>
                  <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual"
                    name="harga_jual" placeholder="Harga Jual Barang" value="{{ old('harga_jual') }}" required>
                  @error('harga_jual')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>Status Barang</label>
                  <div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="statusAktif" value="1"
                        checked>
                      <label class="form-check-label" for="statusAktif">Aktif</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="statusTidakAktif" value="0">
                      <label class="form-check-label" for="statusTidakAktif">Tidak Aktif</label>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
