<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Kategori - {{ $data->nama }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0 20px 40px 20px; }
        .card { border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; margin-top: 10px; }
        .card-header { background: #f8f9fa; padding: 12px 16px; font-weight: 600; }
        .card-body { padding: 12px 16px; }
        .detail-table { width: 100%; margin-bottom: 12px; }
        .detail-table th { text-align: left; padding-right: 8px; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background: #f2f2f2; }
        .footer { position: fixed; bottom: 10px; left: 20px; right: 20px; text-align: right; font-size: 10px; color: #666; }
        h2 { margin: 0 0 6px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Detail Kategori</div>
        <div class="card-body">
            <h2>{{ $data->nama }} ({{ $data->kode }})</h2>
            <p style="margin:0 0 8px 0; color:#555;">Jumlah Item: {{ $items->count() }}</p>

            <table class="detail-table no-border">
                <tr>
                    <th style="width:120px">Nama</th>
                    <td>{{ $data->nama }}</td>
                </tr>
                <tr>
                    <th>Kode</th>
                    <td>{{ $data->kode }}</td>
                </tr>
            </table>

            <h4 style="margin-top:12px;">Daftar Item</h4>
            @if($items->isEmpty())
                <div style="padding:8px;border:1px dashed #ccc;color:#666;">Belum ada item pada kategori ini.</div>
            @else
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:40px">No</th>
                            <th>Nama Item</th>
                            <th>Supplier</th>
                            <th style="width:90px">Harga</th>
                            <th style="width:60px">Laba</th>
                            <th style="width:90px">Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $item->laba }}%</td>
                                <td>{{ number_format(round($item->harga_beli + $item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="footer">
        Printed: {{ $generatedAt->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
