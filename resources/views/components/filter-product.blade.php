<li class="nav-item">
  <form action="">
    <div class="form-group">
      <label for="fKeywordNamaProduk" class="text-secondary font-weight-normal">Nama</label>
      <input type="text" class="form-control" id="fKeywordNamaProduk" placeholder="Nama Produk"
        name="fKeywordNamaProduk" value="{{ request('fKeywordNamaProduk') }}">
    </div>
    <div class="form-group">
      <label for="fKeywordNamaSupplier" class="text-secondary font-weight-normal">Nama</label>
      <input type="text" class="form-control" id="fKeywordNamaSupplier" placeholder="Nama Supplier"
        name="fKeywordNamaSupplier" value="{{ request('fKeywordNamaSupplier') }}">
    </div>
  </form>
</li>