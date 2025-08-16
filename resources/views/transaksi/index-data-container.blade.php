    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th class="col-min-width">Tanggal</th>
            <th class="col-min-width">Invoice</th>
            <th class="col-min-width">Nama Customer</th>
            <th class="col-min-width">Total</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $i => $row)
          <tr>
            <td>{{ $row->tanggal->format('l, d M Y H:i') }}</td>
            <td>{{ $row->kode_invoice }}</td>
            <td>
              <a href="#" data-toggle="modal" data-target="#customerModal" data-customer-id="{{ $row->nama_customer }}">{{ $row->nama_customer }}</a>
            </td>
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