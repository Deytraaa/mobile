<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//copy
use App\Models\Produk;
//import resource
use App\Http\Resources\ProdukRes;
//import validasi create
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    //
    //read
    public function index()
    {
        //mengambil seluruh data berdasarkan 5 data
        $produk = Produk::latest()->paginate(5);

        //mene
        return new ProdukRes(true, 'Daftar Data Produk', $produk);
    }

    //create
    public function store(Request $request)
    {
        //definisi aturan
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        //cek validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //membuat produk
        $produk = Produk::create([
            'nama' => $request->nama,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        //memberikan repspon
        return new ProdukRes(true, 'berhasil menambahkan produk baru', $produk);
    }

    //////////////////////////////////////////////////////////////////////////////////

    //menampilkan daata berdasarkan id
    public function show($id)
    {
        //mencari data berdasarkan id roputes
        $produk = Produk::find($id);

        //kalau data nda ada

        if (!$produk) {
            return response()->json([
                'succes' => false,
                'message' => 'produk$produk tidak ditemukan'
            ], 404);
        }

        //kalau data ada
        return new ProdukRes(true, 'Detail Data Produk', $produk);
    }

    //update data
    public function update(Request $request, $id)
    {
        //mendefiniskan aturan validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required'
        ]);

        //cek validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //mencari data berdasarkan id
        $produk = Produk::find($id);

        //kalau produk$produk tidak ditemukan
        if (!$produk) {
            return response()->json(['message' => 'produk tidak ada'], 404);
        }

        //cek kalau ada file foto baru
        $produk->update([
            'nama'          => $request->nama,
            'stok'          => $request->stok,
            'harga'          => $request->harga,
            'deskripsi'    => $request->deskripsi
        ]);

        //memberikan reposnse setelah update
        return new ProdukRes(true, 'Data Berhasil di Update', $produk);
    }

    //delete produk
    public function destroy($id)
    {
        //Mencari Produk berdasarkan id
        $produk = Produk::find($id);

        //cek kalau produk tidak ditemukan
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        //hapus dari tabel produk
        $produk->delete();

        //kasih response buat bantu frontend
        return new ProdukRes(true, 'Data berhasil dihapus', null);
    }
}
