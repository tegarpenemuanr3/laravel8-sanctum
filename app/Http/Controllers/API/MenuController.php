<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\umkm;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Menu::latest()->get();
        return response()->json([
            'message' => 'Berhasil Menampilkan Data',
            'data' => $data
        ]);
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
        $umkm = umkm::where('id', $request->umkm_id)->get();

        if ($umkm[0]->umkm_status === "aktif") {
            if ($request->hasFile('menu_foto')) {
                $file = $request->file('menu_foto');
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extention;
                $file->move('uploads/menus/', $filename);
            } else {
                $filename = null;
            }

            try {
                $response = Menu::create([
                    'umkm_id' => $request->umkm_id,
                    'menu_nama' => $request->menu_nama,
                    'menu_foto' => $filename,
                    'menu_harga' => $request->menu_harga,
                    'menu_ketersediaan' => $request->menu_ketersediaan,
                    'menu_deskripsi' => $request->menu_deskripsi,
                ]);
                return response()->json([
                    'message' => 'Data Berhasil Ditambahkan',
                    'data' => $response
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error',
                    'errors' => $e->getMessage()
                ], 422);
            }
        } else if ($umkm[0]->umkm_status === "review") {
            return response()->json([
                'message' => 'Maaf Status UMKM Anda Masih review',
            ], 422);
        } else if ($umkm[0]->umkm_status === "blacklist") {
            return response()->json([
                'message' => 'Maaf Status UMKM Anda dalam status blacklist',
            ], 422);
        } else if ($umkm[0]->umkm_status === "non aktif") {
            return response()->json([
                'message' => 'Maaf Status UMKM Anda dalam status non aktif',
            ], 422);
        } else if ($umkm[0]->umkm_status === "tolak") {
            return response()->json([
                'message' => 'Mohon maaf pengajuan UMKM Anda sementara kami tolak',
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::find($id);
        if (is_null($menu)) {
            return response()->json('Data not found', 404);
        }
        return response()->json(
            new MenuResource($menu)
        );
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
        // $menu = Menu::where('user_id', 1)->get();
        // return $menu;

        $menu = Menu::find($id);

        if ($request->hasFile('menu_foto')) {
            $destination = 'uploads/menus/' . $menu->menu_foto;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('menu_foto');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/menus/', $filename);
        } else {
            $filename = $menu->menu_foto;
        }

        try {
            Menu::where('id', $id)
                ->update([
                    'umkm_id' => $request->umkm_id,
                    'menu_nama' => $request->menu_nama,
                    'menu_foto' => $filename,
                    'menu_harga' => $request->menu_harga,
                    'menu_ketersediaan' => $request->menu_ketersediaan,
                    'menu_deskripsi' => $request->menu_deskripsi,
                ]);
            return response()->json([
                'message' => 'Data Berhasil Diedit',
                'data' => Menu::find($id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $menu = Menu::find($id);
            $destination = 'uploads/menus/' . $menu->menu_foto;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $menu->delete();

            return response()->json([
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([ //nilai balik
                'message' => 'Error',
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function showMenuMe($id)
    {
        $menu = Menu::where('umkm_id', $id)->get();

        if (is_null($menu)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([ //nilai balik
            'message' => 'Data Berhasil Ditampilkan',
            'errors' => $menu
        ]);
    }

    public function searchMenu(Request $request)
    {
        $Menu = Menu::when($request->keyword, function ($query) use ($request) {
            $query->where('menu_nama', 'like', "%{$request->keyword}%");
        })->orderBy('menu_nama')->get();


        if ($Menu->count() > 0) {
            return response()->json([
                'data' => $Menu
            ]);
        } else {
            return response()->json([
                'message' => 'Pencarian Tidak Ditemukan'
            ]);
        }
    }
}
