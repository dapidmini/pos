<div class="form-group">
  <label for="fKeywordKategori" class="text-light font-weight-normal">Kategori</label>
  <input type="text" class="form-control" id="fKeywordKategori" placeholder="Kategori"
    name="fKeywordKategori" value="{{ request('fKeywordKategori') }}">
</div>
<div class="form-group pr-3 d-flex justify-content-between align-items-bottom">
  <div class="form-group">
    <label for="fKeywordStokMin" class="text-light font-weight-normal">Stok Min</label>
    <input type="number" class="form-control" id="fKeywordStokMin" placeholder="Stok Minimal"
      name="fKeywordStokMin" value="{{ request('fKeywordStokMin') }}">
  </div>
  <div class="form-group">
    <label for="fKeywordStokMax" class="text-light font-weight-normal">Stok Max</label>
    <input type="number" class="form-control" id="fKeywordStokMax" placeholder="Stok Maksimal"
      name="fKeywordStokMax" value="{{ request('fKeywordStokMax') }}">
  </div>
</div>
<div class="form-group pr-3 d-flex justify-content-between align-items-bottom">
  <div class="form-group">
    <label for="fKeywordHargaBeliStart" class="text-light font-weight-normal">Harga Beli Start</label>
    <input type="number" class="form-control" id="fKeywordHargaBeliStart" placeholder="Harga Beli Minimal"
      name="fKeywordHargaBeliStart" value="{{ request('fKeywordHargaBeliStart') }}">
  </div>
  <div class="form-group">
    <label for="fKeywordHargaBeliEnd" class="text-light font-weight-normal">Harga Beli End</label>
    <input type="number" class="form-control" id="fKeywordHargaBeliEnd" placeholder="Harga Beli Maksimal"
      name="fKeywordHargaBeliEnd" value="{{ request('fKeywordHargaBeliEnd') }}">
  </div>
</div>
<div class="form-group pr-3 d-flex justify-content-between align-items-bottom">
  <div class="form-group">
    <label for="fKeywordHargaJualStart" class="text-light font-weight-normal">Harga Jual Start</label>
    <input type="number" class="form-control" id="fKeywordHargaJualStart" placeholder="Harga Jual Minimal"
      name="fKeywordHargaJualStart" value="{{ request('fKeywordHargaJualStart') }}">
  </div>
  <div class="form-group">
    <label for="fKeywordHargaJualEnd" class="text-light font-weight-normal">Harga Jual End</label>
    <input type="number" class="form-control" id="fKeywordHargaJualEnd" placeholder="Harga Jual Maksimal"
      name="fKeywordHargaJualEnd" value="{{ request('fKeywordHargaJualEnd') }}">
  </div>
</div>