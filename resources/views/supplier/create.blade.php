@extends('layouts.master')

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Tambah Supplier</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Supplier</a></li>
            <li class="breadcrumb-item active">Tambah Supplier</li>
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
            <form action="{{ route('suppliers.store') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="nama">Nama Supplier</label>
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                    name="nama" placeholder="Masukkan Nama Supplier (maks.50 karakter)" value="{{ old('nama') }}"
                    required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="telepon">No. Telepon</label>
                  <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon"
                    name="telepon" placeholder="Masukkan No. Telepon Supplier" value="{{ old('telepon') }}"
                    required>
                  @error('telepon')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" placeholder="Masukkan No. Telepon Supplier" value="{{ old('email') }}"
                    required>
                  @error('email')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3" placeholder="Masukkan alamat Supplier"></textarea>
                  @error('alamat')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
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
