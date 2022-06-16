<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
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
                'user_id' => $request->user_id,
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
}
