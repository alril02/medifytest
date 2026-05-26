<form method="POST" action="{{ url('kategori/form/' . $method . '/' . data_get($kategori, 'id', '')) }}">
    @csrf

    @if($method === 'edit')
        <div class="form-group mb-3">
            <label>ID Kategori</label>
            <input type="text" class="form-control" value="{{ data_get($kategori, 'id', '') }}" readonly>
        </div>
    @endif

    <div class="form-group mb-3">
        <label>Kode</label>
        <input type="text" class="form-control" name="kode" required value="{{ old('kode', data_get($kategori, 'kode', '')) }}">
        @error('kode')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ old('nama', data_get($kategori, 'nama', '')) }}">
        @error('nama')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>



    <button class="btn btn-primary mt-2">Simpan</button>
</form>
