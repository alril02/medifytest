<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php
        $selected = $item->jenis ?? '';
        $selectedKategoriIds = old('kategori_ids', $selectedKategori ?? []);
    @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <optio @if($selected == 'Umum') selected @endif>Umum</option>
            <optio @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <div class="border rounded p-3">
            @forelse($kategoris as $kategori)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="kategori_ids[]" id="kategori_{{ $kategori->id }}" value="{{ $kategori->id }}" {{ in_array($kategori->id, $selectedKategoriIds) ? 'checked' : '' }}>
                    <label class="form-check-label" for="kategori_{{ $kategori->id }}">
                        {{ $kategori->nama }} ({{ $kategori->kode }})
                    </label>
                </div>
            @empty
                <div class="text-muted">Belum ada kategori.</div>
            @endforelse
        </div>
        @error('kategori_ids')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        @error('kategori_ids.*')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

     <div class="form-group">
        <label>Foto Produk</label>
        <input type="file" class="form-control" name="foto" accept="image/*">
        @if(!empty($item->foto_path))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$item->foto_path) }}" alt="Foto" style="max-width:200px; max-height:200px;">
            </div>
        @endif
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
