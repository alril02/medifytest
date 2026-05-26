<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori as KategoriModel;
use App\Models\MasterItem;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Kategori extends Controller
{
    public function index()
    {
        return view('kategori.index');
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $data_search = KategoriModel::query();

        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        }

        $data_search = $data_search->select('id', 'nama', 'kode')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method === 'new') {
            $kategori = [];
        } else {
            $kategori = KategoriModel::find($id);
        }

        return view('kategori.form.index', [
            'kategori' => $kategori,
            'method' => $method,
        ]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
        ];

        if ($method === 'new') {
            $rules['kode'] .= '|unique:kategoris,kode';
        } else {
            $rules['kode'] .= '|unique:kategoris,kode,' . $id;
        }

        $request->validate($rules);

        if ($method === 'new') {
            $kategori = new KategoriModel();
        } else {
            $kategori = KategoriModel::findOrFail($id);
        }

        $kategori->nama = $request->nama;
        $kategori->kode = $request->kode;
        $kategori->save();

        return redirect()->route('kategori')->with('success', 'Kategori berhasil disimpan.');
    }

    public function singleView($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        $items = MasterItem::whereHas('kategoris', function ($q) use ($id) {
            $q->where('kategoris.id', $id);
        })->orderBy('id')->get();

        return view('kategori.single.index', [
            'data' => $kategori,
            'items' => $items,
        ]);
    }

    public function exportPdf($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        $items = MasterItem::whereHas('kategoris', function ($q) use ($id) {
            $q->where('kategoris.id', $id);
        })->orderBy('id')->get();

        $generatedAt = now();

        $pdf = PDF::loadView('kategori.single.pdf', [
            'data' => $kategori,
            'items' => $items,
            'generatedAt' => $generatedAt,
        ]);

        $filename = 'kategori_' . ($kategori->kode ?? $kategori->id) . '_' . $generatedAt->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
