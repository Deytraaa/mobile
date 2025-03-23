<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
//import resource
use App\Http\Resources\StaffRes;
//import validasi create
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    //read
    public function index()
    {
        //mengambil seluruh data berdasarkan 5 data
        $staff = Staff::latest()->paginate(5);

        //mene
        return new StaffRes(true, 'Daftar Data Staff', $staff);
    }

    //create
    public function store(Request $request)
    {
        //definisi aturan
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
            'shift' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required',
        ]);

        //cek validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //membuat staff
        $staff = Staff::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'shift' => $request->shift,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
        ]);

        //memberikan repspon
        return new StaffRes(true, 'berhasil menambahkan staff baru', $staff);
    }

    //////////////////////////////////////////////////////////////////////////////////

    //menampilkan daata berdasarkan id
    public function show($id)
    {
        //mencari data berdasarkan id roputes
        $staff = Staff::find($id);

        //kalau data nda ada

        if (!$staff) {
            return response()->json([
                'succes' => false,
                'message' => 'staff$staff tidak ditemukan'
            ], 404);
        }

        //kalau data ada
        return new StaffRes(true, 'Detail Data Staff', $staff);
    }

    //update data
    public function update(Request $request, $id)
    {
        //mendefiniskan aturan validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
            'shift' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required'
        ]);

        //cek validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //mencari data berdasarkan id
        $staff = Staff::find($id);

        //kalau staff$staff tidak ditemukan
        if (!$staff) {
            return response()->json(['message' => 'staff tidak ada'], 404);
        }

        //cek kalau ada file foto baru
        $staff->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'shift' => $request->shift,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email
        ]);

        //memberikan reposnse setelah update
        return new StaffRes(true, 'Data Berhasil di Update', $staff);
    }

    //delete staff
    public function destroy($id)
    {
        //Mencari Staff berdasarkan id
        $staff = Staff::find($id);

        //cek kalau staff tidak ditemukan
        if (!$staff) {
            return response()->json(['message' => 'Staff tidak ditemukan'], 404);
        }

        //hapus dari tabel staff
        $staff->delete();

        //kasih response buat bantu frontend
        return new StaffRes(true, 'Data berhasil dihapus', null);
    }
}
