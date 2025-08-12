@extends('layouts.master')

@section('title', '- Customer')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-start align-items-center">
    <h1 class="mr-3">Daftar Customer</h1>

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
                <th class="col-min-width">Nama</th>
                <th class="col-min-width">Email</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $row)
              <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->email }}</td>
                <td class="text-center">
                  <a href="{{ route('customers.edit', $row->id) }}" class="btn btn-sm btn-info mr-2">Edit</a>
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