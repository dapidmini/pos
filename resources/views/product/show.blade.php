@extends('layouts.master')

@section('title', '- Detail Produk')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="mr-3">Detail Produk</h3>
      </div>
      <div class="col-sm-6 d-flex flex-column align-items-end">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
          <li class="breadcrumb-item active">Detail Produk</li>
        </ol>
      </div>
    </div>

    @if ($product)
    <div class="d-flex justify-content-start align-items-top mb-5">
      <div class="product-img mr-3">
        <img src="{{ $product->galleryImages->isNotEmpty() ? $product->main_image_url : asset('img/placeholder-no-image.jpg') }}" 
          alt="{{ $product->nama }}" style="width: 300px;" class="mb-3" data-toggle="modal" data-target="#productGalleryModal">
      </div>
      <div class="product-qr-code">
        {!! QrCode::size(300)->generate($product->kode_barang) !!}
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Nama Produk</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->nama }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Stok</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->stok }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Satuan</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-10">{{ $product->satuan }}</div>

      <div class="col-sm-2 d-flex justify-content-between">
        <strong style="white-space: nowrap;">Harga Beli</strong><span class="text-right">:</span>
      </div>
      <div class="col-sm-2 d-flex justify-content-between">
        <span>Rp</span><span>{{ number_format($product->harga_beli, 0, ',', '.') }}</span>
      </div>
      <div class="col-sm-8"></div>

      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Harga Jual</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-2 d-flex justify-content-between">
            <span>Rp</span><span>{{ number_format($product->harga_jual, 0, ',', '.') }}</span>
          </div>
          <div class="col-sm-8"></div>
        </div>
      </div>
      <!-- <p>&nbsp;</p> -->

      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Kategori</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-10">{{ $product->category->nama }}</div>
        </div>
      </div>
      <!-- <p>&nbsp;</p> -->

      <div class="mb-3 col-12">
        <div class="row">
          <div class="col-sm-2 d-flex justify-content-between">
            <strong style="white-space: nowrap;">Supplier</strong><span class="text-right">:</span>
          </div>
          <div class="col-sm-10">
            <a href="{{ route('suppliers.show', $product->supplier->id) }}">{{ $product->supplier->nama }}</a>
          </div>
        </div>
      </div>

      <div class="col-12">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
          <i class="fa fa-pencil pr-2"></i>Edit Data
        </a>
      </div>

    </div>
    @else
    <p>Data produk tidak ditemukan.</p>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="productGalleryModal" tabindex="-1" role="dialog" aria-labelledby="productGalleryLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="productGalleryLabel">Product Gallery</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div id="carouselProductGallery" class="carousel slide" data-ride="carousel">
              @if($product->galleryImages->isNotEmpty())
                <ol class="carousel-indicators bulat">
                  @foreach ($product->galleryImages as $index => $gallery)
                    <li data-target="#carouselProductGallery" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                  @endforeach
                  <li data-target="#carouselProductGallery" data-slide-to="{{ count($product->galleryImages) }}"></li>
                </ol>
              @endif
              </ol>
              <div class="carousel-inner">
                @if($product->galleryImages->isNotEmpty())
                  @foreach ($product->galleryImages as $index => $gallery)
                    @php
                      $path = public_path($gallery->file_path);
                      $imgUrl = file_exists($path) ? asset($gallery->file_path) : asset('img/placeholder-no-image.jpg');
                    @endphp
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                      <img src="{{ $imgUrl }}" class="d-block mx-auto img-carousel" alt="Product Image {{ $index + 1 }}">
                    </div>
                  @endforeach
                @else
                  <div class="carousel-item active">
                    <img src="{{ asset('img/placeholder-no-image.jpg') }}" class="d-block mx-auto img-carousel" alt="No Image Available">
                  </div>
                @endif

                {{-- ✅ Tambahkan slide terakhir untuk QR Code --}}
                <div class="carousel-item">
                    <div class="d-flex justify-content-center align-items-center" style="height: 400px; background: #f9f9f9;">
                        <div class="text-center">
                            {!! QrCode::size(300)->generate($product->kode_barang ?? 'UNKNOWN') !!}
                            <p class="mt-3 text-muted">Kode Produk: {{ $product->kode_barang ?? '-' }}</p>
                        </div>
                    </div>
                </div>
              </div>
              <a class="carousel-control-prev" href="#carouselProductGallery" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselProductGallery" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div> <!-- End Modal #productGalleryModal -->
  </div><!-- /.container-fluid -->
</section>
@endsection
