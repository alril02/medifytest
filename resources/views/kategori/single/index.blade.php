@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex gap-2 mb-2">
                <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
                <a href="{{ route('kategori.export', ['id' => $data->id]) }}" class="btn btn-danger">Download PDF</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Item</th>
                            <td>:</td>
                            <td>{{ $items->count() }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h5>Daftar Item dalam Kategori</h5>

                    @if($items->isEmpty())
                        <div class="alert alert-secondary">Belum ada item pada kategori ini.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Supplier</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->harga_beli }}</td>
                                            <td>{{ round($item->harga_beli + $item->harga_beli * $item->laba / 100) }}</td>
                                            <td>{{ $item->supplier }}</td>
                                            <td>
                                                <a href="{{ url('master-items/view/'.$item->kode) }}" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
