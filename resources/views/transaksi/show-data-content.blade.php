<table border="1" cellpadding="6" id="dataContainer">
  <thead>
    <tr>
      <th class="text-center">Nama Produk</th>
      <th class="text-center">Stok</th>
      <th class="text-center">Satuan</th>
      <th class="text-center col-min-width">Harga Beli</th>
      <th class="text-center col-min-width">Harga Jual</th>
    </tr>
  </thead>
  <tbody>
    @foreach($transaksi->details as $detail)
    <tr class="rowProduk">
      <td class="product-name">{{ $detail->product->nama }}</td>
      <td class="text-right col-min-width">{{ $detail->product->stok }}</td>
      <td class="text-center col-min-width">{{ $detail->product->satuan }}</td>
      <td class="text-right col-min-width">{{ number_format($detail->product->harga_beli, 0, ',', '.') }}</td>
      <td class="text-right col-min-width">{{ number_format($detail->product->harga_jual, 0, ',', '.') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>