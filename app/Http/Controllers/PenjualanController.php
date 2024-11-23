<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\DetailProduk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with('detailPenjualan')
        ->orderBy('id_penjualan','desc')
        ->has('detailPenjualan')
        ->get()
        ->map(function($penjualan){
            $penjualan->total_item = $penjualan->detailPenjualan->sum('jumlah');
            $penjualan->total_harga = $penjualan->detailPenjualan->sum(function ($detail){
                return $detail->jumlah * $detail->harga_jual_produk;
            });
        return $penjualan;
        });
        

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_harga);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {

        $penjualan = new Penjualan();
        $penjualan->id_user = auth()->id(); // Kasir yang melakukan transaksi
        $penjualan->id_kasir = auth()->id();
        $penjualan->tanggal_penjualan = now(); // Menyimpan waktu transaksi
        $penjualan->nomor_invoice = 'INV-' . str_pad(Penjualan::max('id_penjualan') + 1, 6, '0', STR_PAD_LEFT); // Membuat nomor invoice
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->update();
            $detailProduk = DetailProduk::where('id_produk', $item->id_produk)->first();
            $detailProduk->stok_produk -= $item->jumlah;
            $detailProduk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {   
        $penjualan = Penjualan::find($id);
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

         return datatables()
             ->of($detail)
             ->addIndexColumn()
             ->addColumn('kode_produk', function ($detail) {
                 return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
             })
             ->addColumn('nama_produk', function ($detail) {
                 return $detail->produk->nama_produk;
             })
             ->addColumn('harga_jual', function ($detail) {
                 return 'Rp. '. format_uang($detail->harga_jual_produk);
             })
             ->addColumn('jumlah', function ($detail) {
                 return format_uang($detail->jumlah);
             })
             ->addColumn('subtotal', function ($detail) {
                return format_uang($detail->jumlah * $detail->harga_jual_produk);
            })
             ->rawColumns(['kode_produk'])
             ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::with('detailProduk')->find($item->id_produk);
            if ($produk) {
                $produk->detailProduk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
