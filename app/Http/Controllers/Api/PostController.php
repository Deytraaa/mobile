<?php

namespace App\Http\Controllers\Api;

//panggil model
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//import resource
use App\Http\Resources\PostRes;

//import validasi create
use Illuminate\Support\Facades\Validator;

//import library storage
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    //read
    public function index()
    {
        //mengambil seluruh data berdasarkan 5 data
        $posts = Post::latest()->paginate(5);

        //mene
        return new PostRes(true, 'Daftar Data Post', $posts);
    }

    //create
    public function store(Request $request)
    {
        //definisi aturan
        $validator = Validator::make($request->all(), [
            'pic' => 'required|image|mimes:png,jpg,svg,gif,jpeg|max:2048',
            'nama' => 'required',
            'keterangan' => 'required',
        ]);

        //cek validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //upload gambar
        $pic = $request->file('pic');
        $pic->storeAS('public/posts', $pic->hashName());

        //membuat post
        $post = Post::create([
            'pic' => $pic->hashName(),
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        //memberikan repspon
        return new PostRes(true, 'berhasil menambahkan post', $post);
    }

    //menampilkan daata berdasarkan id
    public function show($id)
    {
        //mencari data berdasarkan id roputes
        $post = Post::find($id);

        //kalau data nda ada

        if (!$post) {
            return response()->json([
                'succes' => false,
                'message' => 'post tidak ditemukan'
            ], 404);
        }

        //kalau data ada
        return new PostRes(true, 'Detail Data Post', $post);
    }

    //update data
    public function update(Request $request, $id)
    {
        //mendefiniskan aturan validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'keterangan' => 'required'
        ]);

        //cek validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        //mencari data berdasarkan id
        $post = Post::find($id);
        //kalau post tidak ditemukan
        if (!$post) {
            return response()->json(['message' => 'post tidak ada'], 404);
        }

        //cek kalau ada file foto baru
        if ($request->hasFile('pic')) {
            //mengupload file
            $pic = $request->file('pic');
            $pic->storeAs('public/posts', $pic->hashName());

            //menghapus gambar yang lama kalau ada
            if ($post->pic) {
                Storage::delete('public/posts/' . basename($post->pic));
            }

            //update post dengan gambar yang baru
            $post->update([
                'pic'           => $pic->hashName(),
                'nama'          => $request->nama,
                'keterangan'    => $request->keterangan
            ]);
        } else {
            //update post kalau gambar tidak diisi
            $post->update([
                'nama'          => $request->nama,
                'keterangan'    => $request->keterangan
            ]);
        }

        //memberikan reposnse setelah update
        return new PostRes(true, 'Data Berhasil di Update', $post);
    }

    //delete post
    public function destroy($id)
    {
        //Mencari Post berdasarkan id
        $post = Post::find($id);

        //cek kalau post tidak ditemukan
        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        //hpus gambar
        Storage::delete('public/posts/' . basename($post->pic));

        //hapus dari tabel post
        $post->delete();

        //kasih response buat bantu frontend
        return new PostRes(true, 'Data berhasil dihapus', null);
    }
}
