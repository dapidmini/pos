@extends('layouts.master')

@section('title', '- Transaksi')

@section('content')
  <div class="container-fluid ms-3 mt-3 pr-3">
    <div class="d-flex justify-content-start align-items-center">
      <h1 class="mr-3">Daftar Transaksi</h1>
      <a href="{{ route('transaksis.create') }}" class="btn btn-primary">Buat Baru</a>

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
                    <th class="col-min-width">No.</th>
                    <th class="col-min-width">Tanggal</th>
                    <th class="col-min-width">Nama Customer</th>
                    <th class="col-min-width">Total</th>
                    <th class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $i => $row)
                    <tr>
                      <td>{{ $i+1 }}.</td>
                      <td>{{ $row->tanggal->format('l, d M Y H:i:s') }}</td>
                      <td>{{ $row->nama_customer }}</td>
                      <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                      <td class="text-center">
                        <a href="{{ route('transaksis.edit', $row->id) }}" class="btn btn-sm btn-info mr-2">Edit</a>
                        <form action="{{ route('transaksis.destroy', $row->id) }}" method="POST" style="display:inline;">
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