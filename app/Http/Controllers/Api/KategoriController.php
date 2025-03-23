<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//copy
use App\Models\Kategori;
//import resource
use App\Http\Resources\KategoriRes;
//import validasi create
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    //
    //read
    public function index()
    {
        //mengambil seluruh data berdasarkan 5 data
        $kategori = Kategori::latest()->paginate(5);

        //mene
        return new KategoriRes(true, 'Daftar Data Kategori', $kategori);
    }

    //create
    public function store(Request $request)
    {
        //definisi aturan
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kode' => 'required',
            'keterangan' => 'required',
        ]);

        //cek validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //membuat kategori
        $kategori = Kategori::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'keterangan' => $request->keterangan,
        ]);

        //memberikan repspon
        return new KategoriRes(true, 'berhasil menambahkan kategori baru', $kategori);
    }


    //menampilkan daata berdasarkan id
    public function show($id)
    {
        //mencari data berdasarkan id roputes
        $kategori = Kategori::find($id);

        //kalau data nda ada

        if (!$kategori) {
            return response()->json([
                'succes' => false,
                'message' => 'kategori$kategori tidak ditemukan'
            ], 404);
        }

        //kalau data ada
        return new KategoriRes(true, 'Detail Data Kategori', $kategori);
    }

    //update data
    public function update(Request $request, $id)
    {
        //mendefiniskan aturan validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kode' => 'required',
            'keterangan' => 'required'
        ]);

        //cek validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //mencari data berdasarkan id
        $kategori = Kategori::find($id);

        //kalau kategori$kategori tidak ditemukan
        if (!$kategori) {
            return response()->json(['message' => 'kategori tidak ada'], 404);
        }

        //cek kalau ada file foto baru
        $kategori->update([
            'nama'          => $request->nama,
            'kode'          => $request->kode,
            'keterangan'    => $request->keterangan
        ]);

        //memberikan reposnse setelah update
        return new KategoriRes(true, 'Data Berhasil di Update', $kategori);
    }

    //delete kategori
    public function destroy($id)
    {
        //Mencari Kategori berdasarkan id
        $kategori = Kategori::find($id);

        //cek kalau kategori tidak ditemukan
        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        //hapus dari tabel kategori
        $kategori->delete();

        //kasih response buat bantu frontend
        return new KategoriRes(true, 'Data berhasil dihapus', null);
    }
}
