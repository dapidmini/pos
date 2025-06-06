{{-- Ini adalah partial view yang akan di-include di create.blade.php dan edit.blade.php --}}
{{-- Variabel: $index, $products, $detail (object TransaksiDetail atau null) --}}

<div class="row mb-3 align-items-end border-bottom pb-3 detail-item">
    <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id ?? '' }}">

    <div class="col-md-4">
        <label for="product_id_{{ $index }}" class="form-label">Produk</label>
        <select name="details[{{ $index }}][product_id]" id="product_id_{{ $index }}" class="form-select @error('details.' . $index . '.product_id') is-invalid @enderror">
            <option value="">Pilih Produk</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    {{ (old('details.' . $index . '.product_id', $detail->product_id ?? '') == $product->id) ? 'selected' : '' }}>
                    {{ $product->nama }} (Rp {{ number_format($product->harga_jual, 0, ',', '.') }})
                </option>
            @endforeach
        </select>
        @error('details.' . $index . '.product_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2">
        <label for="jumlah_{{ $index }}" class="form-label">Jumlah</label>
        <input type="number" class="form-control @error('details.' . $index . '.jumlah') is-invalid @enderror"
               id="jumlah_{{ $index }}" name="details[{{ $index }}][jumlah]"
               value="{{ old('details.' . $index . '.jumlah', $detail->jumlah ?? '') }}" min="1">
        @error('details.' . $index . '.jumlah')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="harga_{{ $index }}" class="form-label">Harga Satuan</label>
        <input type="number" class="form-control @error('details.' . $index . '.harga') is-invalid @enderror"
               id="harga_{{ $index }}" name="details[{{ $index }}][harga]" step="0.01"
               value="{{ old('details.' . $index . '.harga', $detail->harga ?? '') }}" min="0">
        @error('details.' . $index . '.harga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-2">
        <label for="subtotal_display_{{ $index }}" class="form-label">Subtotal</label>
        {{-- Input ini hanya untuk tampilan, subtotal dihitung di backend --}}
        <input type="text" class="form-control" id="subtotal_display_{{ $index }}" value="" readonly>
    </div>

    <div class="col-md-1 d-grid">
        <button type="button" class="btn btn-danger remove-detail">Hapus</button>
    </div>

    <div class="col-md-12 mt-2">
        <label for="detail_catatan_{{ $index }}" class="form-label visually-hidden">Catatan Detail</label>
        <input type="text" class="form-control @error('details.' . $index . '.catatan') is-invalid @enderror"
               id="detail_catatan_{{ $index }}" name="details[{{ $index }}][catatan]"
               value="{{ old('details.' . $index . '.catatan', $detail->catatan ?? '') }}" placeholder="Catatan per item (opsional)">
        @error('details.' . $index . '.catatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>