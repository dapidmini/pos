@extends('layouts.master')

@section('content')
  <div class="container-fluid ms-3 mt-3 pr-3">
    <div class="d-flex justify-content-start align-items-center">
      <h1 class="mr-3">Daftar Barang</h1>
      <a href="{{ route('products.create') }}" class="btn btn-primary">Buat Baru</a>

      <div class="input-group input-group-sm" style="width: 150px; margin-left: auto">
        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

        <div class="input-group-append">
          <button type="submit" class="btn btn-default">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>

    </div>

    @if ($data->isEmpty())
      <p>No data</p>
    @else
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th class="col-min-width">ID</th>
                    <th class="col-min-width">Nama Barang</th>
                    <th class="col-min-width">Stok</th>
                    <th class="col-min-width">Harga Beli</th>
                    <th class="col-min-width">Harga Jual</th>
                    <th class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $row)
                    <tr>
                      <td>{{ $row->id }}</td>
                      <td>{{ $row->nama }}</td>
                      <td>{{ number_format($row->stok, 0, ',', '.') }}</td>
                      <td>Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                      <td>Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                      <td class="text-center">
                        <a href="{{ route('products.edit', $row->id) }}" class="btn btn-sm btn-info mr-2">Edit</a>
                        <form action="{{ route('products.destroy', $row->id) }}" method="POST" style="display:inline;">
                          @csrf {{-- Laravel CSRF protection --}}
                          @method('DELETE') {{-- Method spoofing untuk DELETE request --}}
                          <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Delete</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    @endif
  </div>
@endsection