<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\umkm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'umkm_nama' => 'required|string|max:255|min:3',
                'umkm_phone' => 'required|string|max:14|min:11',
                'umkm_email' => 'required|string|email|max:255|unique:umkms',
                'umkm_latitude' => 'required',
                'umkm_longtitude' => 'required',
                'umkm_deskripsi' => 'required',
                'umkm_operasional' => 'required',
                'umkm_foto' => 'required',
                'umkm_pemilik_nama' => 'required',
                'umkm_pemilik_phone' => 'required',
                'password'  => 'required|string|min:8'
            ],
            [
                'umkm_nama.required' => 'Nama UMKM tidak boleh kosong',
                'umkm_deskripsi.required' => 'Deskripsi UMKM tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'message' => $validator->errors()
            ], 404);
        }

        $fileName = "";
        if ($request->umkm_foto) {
            //Proses
            $image = $request->umkm_foto->getClientOriginalName(); //ambil nama file asli
            $image = str_replace(' ', '', $image); //hapus spasi
            $image = date('Hs') . rand(1, 999) . "_" . $image; //add date & random angka
            $fileName = $image;
            $request->umkm_foto->move('uploads/umkms/', $image);   //simpan ke folder ditentukan
        } else {
            $fileName = null;
        }

        $umkm = umkm::create([
            'umkm_nama' => $request->umkm_nama,
            'umkm_phone' => $request->umkm_phone,
            'umkm_email' => $request->umkm_email,
            'umkm_alamat' => $request->umkm_alamat,
            'umkm_latitude' => $request->umkm_latitude,
            'umkm_longtitude' => $request->umkm_longtitude,
            'umkm_deskripsi' => $request->umkm_deskripsi,
            'umkm_foto' => $fileName,
            'umkm_operasional' => $request->umkm_operasional,
            'umkm_pemilik_nama' => $request->umkm_pemilik_nama,
            'umkm_pemilik_phone' => $request->umkm_pemilik_phone,
            'password' => Hash::make($request->password)
        ]);

        $token = $umkm->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $umkm,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'umkm_email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'message' => $validator->errors()
            ], 404);
        }


        $user = umkm::where('umkm_email', $request['umkm_email'])->firstOrFail();
        $password = Hash::check($request->password, $user->password);

        if ($password == $user->password) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Hi UMKM ' . $user->umkm_nama . ', welcome to home',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'message' => 'Login Gagal, Pastikan Email dan Password benar!',
            ]);
        }
    }
}
