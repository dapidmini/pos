@extends('layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="mb-3">
      <h1>Halaman Dashboard POS</h1>
    </div>
    <a href="{{ route('transaksis.index') }}" class="btn btn-primary">
      Daftar Transaksi
    </a>
  </div>
</section>

<!-- <section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Proyek Berhasil Disiapkan</h3>
      </div>
      <div class="card-body">
        <p>AdminLTE 3 sekarang terintegrasi dengan Laravel 11 Anda.</p>
        <button class="btn btn-primary">Tombol Bootstrap</button>
      </div>
    </div>
  </div>
</section> -->
@endsection