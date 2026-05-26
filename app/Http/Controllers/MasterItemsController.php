<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }

        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        $hasMin = $hargamin !== null && $hargamin !== '';
        $hasMax = $hargamax !== null && $hargamax !== '';

        if ($hasMin && $hasMax) {
            $data_search = $data_search->where('harga_beli', '>=', $hargamin)
                ->where('harga_beli', '<=', $hargamax);
        } elseif ($hasMin) {
            $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        } elseif ($hasMax) {
            $data_search = $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem();
            $selectedKategori = [];
        } else {
            $item = MasterItem::with('kategoris')->findOrFail($id);
            $selectedKategori = $item->kategoris->pluck('id')->toArray();
        }

        return view('master_items.form.index', [
            'item' => $item,
            'method' => $method,
            'kategoris' => Kategori::orderBy('nama')->get(),
            'selectedKategori' => $selectedKategori,
        ]);
    }

    public function exportCsv()
    {
        $items = MasterItem::with('kategoris')->orderBy('id')->get();

        $filename = 'master_items_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['no', 'nama kategori', 'nama items', 'nama suplier', 'harga', 'laba', 'harga jual']);
            $no = 1;
            foreach ($items as $item) {
                $kategoriNames = $item->kategoris->pluck('nama')->implode('; ');
                $hargaJual = round($item->harga_beli + $item->harga_beli * $item->laba / 100);
                fputcsv($handle, [$no, $kategoriNames, $item->nama, $item->supplier, $item->harga_beli, $item->laba, $hargaJual]);
                $no++;
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function singleView($kode)
    {
        $item = MasterItem::with('kategoris')->where('kode', $kode)->firstOrFail();
        if ($item && $item->foto_path) {
            $item->foto_url = url('storage/' . $item->foto_path);
        } else {
            $item->foto_url = null;
        }
        $data['data'] = $item;
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|integer|min:0',
            'laba' => 'required|integer|min:0',
            'supplier' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'kategori_ids' => 'array',
            'kategori_ids.*' => 'exists:kategoris,id',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($request->hasFile('foto')) {
            $oldPath = $data_item->foto_path ?? null;
            $path = $request->file('foto')->store('master_items', 'public');
            $data_item->foto_path = $path;
            if ($method != 'new' && $oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $data_item->save();

        $kategoriIds = $request->input('kategori_ids', []);
        $data_item->kategoris()->sync($kategoriIds);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
