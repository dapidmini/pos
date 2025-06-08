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
                    <label class="text-danger mr-2" style="opacity: 0">*</label>
                    <input type="text" class="form-control @error('diskon') is-invalid @enderror" id="diskon"
                      name="diskon" placeholder="Masukkan Diskon" value="{{ old('diskon') }}" />
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
                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Masukkan Alamat Supplier">{{ old('keterangan') }}</textarea>
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
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap" id="details-table">
              <thead>
                <tr>
                  <th class="">Nama Barang</th>
                  <th class="">Kategori</th>
                  <th class="col-min-width">Catatan</th>
                  <th class="text-center col-min-width">Jumlah</th>
                  <th class="text-center col-min-width">Satuan</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center col-min-width">Diskon</th>
                  <th class="text-center">Subtotal</th>
                  <th class="text-center ">Actions</th>
                </tr>
              </thead>
              <tbody id="details-container">
              </tbody>
            </table>

          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="text-right">
              <label class="fw-bold mr-2">Grand Total</label>
              <br>
              <h2 name="total">0</h2>
            </div>
          </div>
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
                        <td>{{ $i + 1 }}.</td>
                        <td>{{ $product->nama }}</td>
                        <td>{{ $product->category->nama }}</td>
                        <td>{{ $product->supplier->nama }}</td>
                        <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ number_format($product->stok, 0, ',', '.') . ' ' . $product->satuan }}</td>
                        <td>
                          <button class="btn btn-info" id="btnPilih-{{ $product->id }}"
                            data-id="{{ $product->id }}" data-nama="{{ $product->nama }}"
                            data-id_kategori="{{ $product->id_kategori }}"
                            data-nama_kategori="{{ $product->category->nama }}"
                            data-id_supplier="{{ $product->id_supplier }}"
                            data-nama_supplier="{{ $product->supplier->nama }}"
                            data-harga_jual="{{ $product->harga_jual }}" data-satuan="{{ $product->satuan }}"
                            data-stok="{{ $product->stok }}">Pilih</button>
                        </td>
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
      const detailsContainer = document.querySelector('#details-container');
      let listDetail = [];

      modalSearchProduct.on('click', 'button[id^="btnPilih-"]', function() {
        const btnPilih = $(this);
        const productId = btnPilih.data('id');
        let found = false;
        listDetail.forEach(detail => {
          if (detail.id === btnPilih.data('id')) {
            found = true;
            return;
          }
        });

        if (found) {
          alert('Barang ini sudah ada di Detail Transaksi');
          return false;
        }

        const pilih = { // data produk yang barusan dipilih di popup modal
          id: btnPilih.data('id'),
          nama: btnPilih.data('nama'),
          id_kategori: btnPilih.data('id_kategori'),
          nama_kategori: btnPilih.data('nama_kategori'),
          id_supplier: btnPilih.data('id_supplier'),
          nama_supplier: btnPilih.data('nama_supplier'),
          harga_jual: btnPilih.data('harga_jual'),
          satuan: btnPilih.data('satuan'),
          stok: btnPilih.data('stok'),
        }
        listDetail.push(pilih);

        modalSearchProduct.modal('hide');
        /*
        <th class="col-min-width">Nama Barang</th>
        <th class="col-min-width">Kategori</th>
        <th class="col-min-width">Jumlah</th>
        <th class="col-min-width">Satuan</th>
        <th class="col-min-width">Harga</th>
        <th class="col-min-width">Diskon</th>
        <th class="col-min-width">Subtotal</th>
        <th class="text-center">Actions</th>
        */
        let existingRowsElem = detailsContainer.querySelectorAll('tr');
        let existingRows = existingRowsElem.length;
        const elemRow = document.createElement('tr');
        elemRow.setAttribute('data-index', existingRows + 1);

        for (let index = 0; index < 9; index++) {
          const elemCell = document.createElement('td');
          let elemHidden;

          if (index == 0) { // nama product
            elemCell.innerHTML = `
              <span class="display-nama">${pilih.nama}</span>
            `;
            elemHidden = setupProductHiddenElem('product_id', pilih.id, existingRows + 1);
            elemCell.appendChild(elemHidden);
          } else if (index == 1) { // kategori
            elemCell.textContent = pilih.nama_kategori;
          } else if (index == 2) { // catatan
            elemCell.innerHTML = `
              <input type="text" class="form-control" name="catatan[]" placeholder="Catatan tambahan">
            `;
          } else if (index == 3) { // jumlah
            elemCell.innerHTML = `
              <button type="button" class="btn btn-sm btn-secondary ml-2 mr-2 btn-minus">
                <i class="fa-solid fa-minus"></i>
              </button>
              <span class="display-jumlah">1</span>
              <button type="button" class="btn btn-sm btn-secondary ml-2 mr-2 btn-plus">
                <i class="fa-solid fa-plus"></i>
              </button>
            `;
            elemCell.classList.add('text-center');
            elemHidden = setupProductHiddenElem('jumlah', 0), existingRows + 1;
            elemCell.appendChild(elemHidden);
          } else if (index == 4) { // satuan
            elemCell.classList.add('text-center');
            elemCell.textContent = pilih.satuan;
          } else if (index == 5) { // harga jual
            elemCell.classList.add('text-right');
            elemHidden = setupProductHiddenElem('harga', 0, existingRows + 1);
            elemCell.appendChild(elemHidden);
            elemCell.innerHTML = `
              <input type="hidden" name="harga[]" value="${pilih.harga_jual}">
              <span class="display-harga">${pilih.harga_jual.toLocaleString()}</span>
            `;
          } else if (index == 6) { // diskon
            elemHidden = setupProductHiddenElem('diskon', 0, existingRows + 1);
            elemCell.appendChild(elemHidden);
            elemCell.innerHTML = `
              <input type="text" class="form-control" name="diskon[]" placeholder="Diskon per item">
            `;
          } else if (index == 7) { // subtotal
            elemCell.classList.add('text-right');
            elemHidden = setupProductHiddenElem('catatan', 0, existingRows + 1);
            elemCell.appendChild(elemHidden);
            elemCell.innerHTML = `
              <span class="display-subtotal">0</span>
            `;
          } else if (index == 8) { // actions
            const elemBtnDeleteRow = document.createElement('button');
            elemBtnDeleteRow.classList.add('button', 'btn', 'btn-danger', 'btn-delete-row');
            elemBtnDeleteRow.innerHTML = 'HAPUS';
            elemCell.classList.add('text-center');
            elemCell.appendChild(elemBtnDeleteRow);
          }

          elemRow.appendChild(elemCell);
        }
        // end for

        let subtotal = hitungSubtotal(elemRow);
        let elemSubtotal = elemRow.querySelector('.display-subtotal');
        elemSubtotal.textContent = subtotal.toLocaleString();

        detailsContainer.appendChild(elemRow);

        // hitung grand total
        const subtotals = detailsContainer.querySelectorAll('.display-subtotal');
        let grandTotal = 0;
        subtotals.forEach(item => {
          grandTotal += parseInt(item.textContent.replace(/[^0-9]/g, ''));
        });
        const displayGrandTotal = document.querySelector('[name="total"]');
        displayGrandTotal.innerHTML = grandTotal.toLocaleString();
      });
      // end click #btnPilih-*

      detailsContainer.addEventListener('click', function(event) {
        const targetButton = event.target.closest('button'); // Elemen yang sebenarnya diklik
        const dataRow = event.target.closest('tr'); // Dapatkan baris tempat tombol berada
        const displayJumlah = dataRow.querySelector('.display-jumlah');
        let angkaJumlah = displayJumlah.textContent.replace(/[^0-9]/g, '');
        angkaJumlah = parseInt(angkaJumlah); // Pastikan ini integer

        // Pastikan yang diklik adalah button
        if (targetButton && targetButton.classList.contains('btn')) {
          // --- Logika untuk Tombol Plus (+) ---
          if (targetButton.classList.contains('btn-plus')) {
            angkaJumlah++;
            displayJumlah.textContent = angkaJumlah.toLocaleString();

            let subtotal = hitungSubtotal(dataRow);
            let elemSubtotal = dataRow.querySelector('.display-subtotal');
            elemSubtotal.textContent = subtotal.toLocaleString();
          }
          // --- Logika untuk Tombol Minus (-) ---
          else if (targetButton.classList.contains('btn-minus')) {
            if (angkaJumlah > 0) { // Jangan sampai kurang dari 0
              angkaJumlah -= 1;
              displayJumlah.textContent = angkaJumlah;
            }

            let subtotal = hitungSubtotal(dataRow);
            let elemSubtotal = dataRow.querySelector('.display-subtotal');
            elemSubtotal.textContent = subtotal.toLocaleString();
          }
          // --- Logika untuk Tombol Hapus ---
          else if (targetButton.classList.contains('btn-delete-row')) {
            if (dataRow) {
              const barangID = dataRow.querySelector('[name^="product_id"]');
              const namaBarang = dataRow.querySelector('.display-nama').textContent;
              console.log('debug', barangID.value, listDetail);
              if (confirm(`Data barang ${namaBarang} akan dihapus dari Detail Transaksi. Lanjutkan?`)) {
                dataRow.remove();

                listDetail = listDetail.filter(detail => detail.id.toString() !== barangID.value.toString());

                console.log('Baris dihapus.', listDetail);
              }
            }
          }

          // hitung grand total
          const subtotals = detailsContainer.querySelectorAll('.display-subtotal');
          let grandTotal = 0;
          subtotals.forEach(item => {
            grandTotal += parseInt(item.textContent.replace(/[^0-9]/g, ''));
          });
          const displayGrandTotal = document.querySelector('[name="total"]');
          displayGrandTotal.innerHTML = grandTotal.toLocaleString();
        }
      });

      // function initListeners(namaElem='')
      // {
      //   if (namaElem === '' || namaElem === 'btnPlus') {
      //     const btnPlus = document.querySelectorAll('.btn-plus');
      //     btnPlus.forEach(button => {
      //       const dataRow = button.closest('tr');
      //       const displayJumlah = dataRow.querySelector('.display-jumlah');
      //       let jumlah = displayJumlah.textContent.replace(/[^0-9]/g, '');
      //       jumlah = parseInt(jumlah) + 1;
      //       displayJumlah.textContent = jumlah;
      //     });
      //   }

      //   if (namaElem === '' || namaElem === 'btnMinus') {
      //     const btnMinus = document.querySelectorAll('.btn-minus');
      //     btnMinus.forEach(button => {
      //       const dataRow = button.closest('tr');
      //       const displayJumlah = dataRow.querySelector('.display-jumlah');
      //       let jumlah = displayJumlah.textContent.replace(/[^0-9]/g, '');
      //       jumlah = parseInt(jumlah) - 1;
      //       displayJumlah.textContent = jumlah;
      //     });
      //   }
      // }
      // function addDeleteRowDetailBtnListeners(btnElem) {
      //   const deleteButtons = document.querySelectorAll('.delete-detail-row');
      //   deleteButtons.foreEach(button => {
      //     button.onclick = function() {
      //       const rowElem = btnElem.closest('tr');
      //       rowElem.remove();
      //     }
      //   });
      // }

      function setupProductHiddenElem(colName, colValue, rowNum) {
        const elemHidden = document.createElement('input');
        let elemName = colName + '[]';
        elemHidden.setAttribute('type', 'hidden');
        elemHidden.setAttribute('name', elemName);
        // elemHidden.setAttribute('id', colName+'-'+rowNum.toString());
        elemHidden.setAttribute('value', colValue);

        return elemHidden;
      }

      function hitungSubtotal(elemRow) {
        let jumlah = elemRow.querySelector('.display-jumlah').innerHTML || 0;
        jumlah = jumlah.replace(/[^0-9]/g, '');
        let harga = elemRow.querySelector('[name^="harga"]').value || 0;
        harga = harga.replace(/[^0-9]/g, '');
        let subtotal = jumlah * harga;

        return subtotal;
      }
      return;
      // const invoiceLabel = document.querySelector('#display-kode-invoice');
      // let counter = 1;
      // let kodeInvoice = 'INV'; // INV2025060600001

      // let detailIndex = {{ old('details') ? count(old('details')) : 1 }};

      // const detailsContainer = document.getElementById('details-container');
      // const addDetailButton = document.getElementById('add-detail');
      // const detailItemTemplate = document.getElementById('detail-item-template');

      // function calculateSubtotal(detailItem) {
      //   const inputJumlah = detailItem.find()
      // }
    });
  </script>
@endpush
