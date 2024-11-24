<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\ProdukDetail;
use App\Models\Kategori;
use PDF;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index()
    {
        // Mengambil data produk dengan relasi detail_produk dan kategori
        $data = Produk::with(['produkDetail', 'kategori'])->get();
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori'); // Mengambil nama_kategori dengan id_kategori sebagai key
        return view('produk.index', compact('data', 'kategori'));
    }

    /**
     * Data untuk DataTables.
     */
public function data()
{
    $produk = Produk::join('produk_detail', 'produk.kode_produk', '=', 'produk_detail.kode_produk')
        ->leftJoin('kategori', 'kategori.id_kategori', '=', 'produk.id_kategori')
        ->select(
            'produk.kode_produk',
            'produk.nama_produk',
            'produk.harga_jual',
            'produk_detail.stok',
            'produk_detail.merk',
            'produk_detail.harga_beli',
            'kategori.nama_kategori'
        )->get();


        return datatables()
        ->of($produk)
        ->addIndexColumn()
        ->addColumn('select_all', function ($produk) {
            return '
                <input type="checkbox" name="kode_produk[]" value="'. $produk->kode_produk .'">
            ';
        })
        ->addColumn('kode_produk', function ($produk) {
            return '<span class="label label-success">'. $produk->kode_produk .'</span>';
        })
        ->addColumn('harga_beli', function ($produk) {
            return format_uang($produk->harga_beli_produk);
        })
        ->addColumn('harga_jual', function ($produk) {
            return format_uang($produk->harga_jual);
        })
        ->addColumn('stok', function ($produk) {
            return format_uang($produk->stok_produk);
        })
        ->addColumn('aksi', function ($produk) {
            return '
            <div class="btn-group">
                <button type="button" onclick="editForm('. route('produk.update', $produk->kode_produk) .')" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData('. route('produk.destroy', $produk->kode_produk) .')" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'kode_produk', 'select_all'])
        ->make(true);
}


    /**
     * Menyimpan data baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required|string',
            'harga_jual' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok_produk' => 'required|numeric',
            'merk' => 'nullable|string',
            'harga_beli_produk' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validatedData) {
            $produk = Produk::create([
                'kode_produk' => 'P' . str_pad(Produk::max('kode_produk') + 1, 6, '0', STR_PAD_LEFT),
                'nama_produk' => $validatedData['nama_produk'],
                'harga_jual' => $validatedData['harga_jual'],
                'id_kategori' => $validatedData['id_kategori'],
            ]);

            ProdukDetail::create([
                'kode_produk' => $produk->kode_produk,
                'stok' => $validatedData['stok_produk'],
                'merk' => $validatedData['merk'],
                'harga_beli' => $validatedData['harga_beli_produk'],
            ]);
        });

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Menampilkan data produk tertentu.
     */
    public function show($id)
    {
        $produk = Produk::where('kode_produk', $id)->first();
        $detailproduk = DetailProduk::where('kode_produk', $id)->first();
    
        
        // Gabungkan atribut detail produk ke dalam objek produk
        foreach ($detailproduk->getAttributes() as $key => $value) {
            $produk->setAttribute($key, $value);
        }
    
        return response()->json($produk);
    }

    /**
     * Memperbarui data produk.
     */
    public function create()
{
    $kategori = Kategori::pluck('nama_kategori', 'id_kategori'); // Ambil data kategori
    return view('produk.form', compact('kategori'));
}

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required|string',
            'harga_jual' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok_produk' => 'required|numeric',
            'merk' => 'nullable|string',
            'harga_beli_produk' => 'required|numeric',
        ]);

        $produk = Produk::findOrFail($id);
        $produkDetail = ProdukDetail::where('kode_produk', $id)->firstOrFail();

        DB::transaction(function () use ($produk, $produkDetail, $validatedData) {
            $produk->update([
                'nama_produk' => $validatedData['nama_produk'],
                'harga_jual' => $validatedData['harga_jual'],
                'id_kategori' => $validatedData['id_kategori'],
            ]);

            $produkDetail->update([
                'stok' => $validatedData['stok_produk'],
                'merk' => $validatedData['merk'],
                'harga_beli' => $validatedData['harga_beli_produk'],
            ]);
        });

        return response()->json('Data berhasil diperbarui', 200);
    }

    /**
     * Menghapus data produk.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return response(null, 204);
    }

    /**
     * Menghapus beberapa data produk.
     */
    public function deleteSelected(Request $request)
    {
        Produk::whereIn('kode_produk', $request->kode_produk)->delete();
        return response(null, 204);
    }

    /**
     * Cetak barcode.
     */
    public function cetakBarcode(Request $request)
    {
        $produk = Produk::whereIn('kode_produk', $request->kode_produk)->get();
        $pdf = PDF::loadView('produk.barcode', compact('produk'));
        return $pdf->stream('barcode.pdf');
    }
}
