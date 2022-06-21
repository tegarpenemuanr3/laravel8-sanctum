<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\umkm;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
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
        $validator = Validator::make($request->all(), [
            'admin_nama' => 'required|string|max:255|min:3',
            'admin_phone' => 'required|string|max:14|min:11',
            'admin_email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'message' => $validator->errors()
            ], 404);
        }

        $fileName = "";
        if ($request->admin_foto) {
            //Proses
            $image = $request->admin_foto->getClientOriginalName(); //ambil nama file asli
            $image = str_replace(' ', '', $image); //hapus spasi
            $image = date('Hs') . rand(1, 999) . "_" . $image; //add date & random angka
            $fileName = $image;
            $request->admin_foto->move('uploads/admin/', $image);   //simpan ke folder ditentukan
        } else {
            $fileName = null;
        }

        $admin = admin::create([
            'admin_nama' => $request->admin_nama,
            'admin_phone' => $request->admin_phone,
            'admin_email' => $request->admin_email,
            'admin_foto' => $fileName,
            'password' => Hash::make($request->password)
        ]);

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $admin,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'message' => $validator->errors()
            ], 404);
        }


        $user = admin::where('admin_email', $request['admin_email'])->firstOrFail();
        $password = Hash::check($request->password, $user->password);

        if ($password == $user->password) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Hi ' . $user->name . ', welcome to home',
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

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ]);
    }

    public function update_profile(Request $request, $id)
    {
        $admin = admin::find($id);

        if ($request->hasFile('admin_foto')) {
            $destination = 'uploads/admin/' . $admin->admin_foto;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('admin_foto');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/admin/', $filename);
        } else {
            $filename = $admin->menu_foto;
        }

        try {
            admin::where('id', $id)
                ->update([
                    'admin_nama' => $request->admin_nama,
                    'admin_phone' => $request->admin_phone,
                    'admin_foto' => $filename,
                ]);
            return response()->json([
                'message' => 'Data Berhasil Diedit',
                'data' => admin::find($id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function ganti_password(Request $request, $id)
    {
        $admin = admin::find($id);

        if (Hash::check($request->password, $admin->password)) {
            try {
                $password = $request->password_baru;
                admin::where('id', $id)
                    ->update([
                        'password' => Hash::make($password)
                    ]);
                return response()->json([
                    'message' => 'Data Berhasil Diedit',
                    'data' => admin::find($id)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error',
                    'errors' => $e->getMessage()
                ], 422);
            }
        } else {
            return response()->json([
                'message' => 'Gagal Update Password',
            ]);
        }
    }

    public function all_umkm()
    {
        //tampil semua umkm dengan penjelasan status di sampingnya
        $umkm = umkm::all();
        if ($umkm->count() > 0) {
            return response()->json([
                'data' => $umkm
            ]);
        } else {
            return response()->json([
                'message' => 'Data Kosong'
            ]);
        }
    }

    public function umkm_register()
    {
        //tampil semua data umkm yang sedang daftar => Review
        $umkm = umkm::where('umkm_status', 'review')->get();
        if ($umkm->count() > 0) {
            return response()->json($umkm);
        } else {
            return response()->json([
                'message' => 'Data Kosong'
            ]);
        }
    }

    public function umkm_banned()
    {
        //tampil semua data umkm yang di banned => banned
        $umkm = umkm::where('umkm_status', 'blacklist')->get();
        if ($umkm->count() > 0) {
            return response()->json($umkm);
        } else {
            return response()->json([
                'message' => 'Data Kosong'
            ]);
        }
    }

    public function umkm_aktif()
    {
        //tampil semua data umkm yang aktif => aktif
        $umkm = umkm::where('umkm_status', 'aktif')->get();
        if ($umkm->count() > 0) {
            return response()->json($umkm);
        } else {
            return response()->json([
                'message' => 'Data Kosong'
            ]);
        }
    }

    public function update_status_umkm(Request $request, $id)
    {
        //id UMKM
        try {
            if ($request->umkm_status == "aktif") {
                umkm::where('id', $id)
                    ->update([
                        'umkm_status' => $request->umkm_status,
                        'umkm_buka_sejak' => new DateTime('now')
                    ]);
            } else {
                umkm::where('id', $id)
                    ->update([
                        'umkm_status' => $request->umkm_status,
                        'umkm_buka_sejak' => null
                    ]);
            }

            return response()->json([
                'message' => 'Data Berhasil Diedit',
                'data' => umkm::where('id', $id)->get()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ], 422);
        }
    }
}
