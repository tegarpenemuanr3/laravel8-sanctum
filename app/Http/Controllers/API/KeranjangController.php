<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KeranjangResource;
use Illuminate\Http\Request;
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Keranjang::latest()->get();
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
        $data = Keranjang::where('user_id', $request->user_id)
            ->where('menu_id', $request->menu_id)
            ->get();

        if (count($data) == 0) {
            $program = Keranjang::create([
                'user_id' => $request->user_id,
                'menu_id' => $request->menu_id,
                'menu_nama' => $request->menu_nama,
                'menu_foto' => $request->menu_foto,
                'menu_harga' => $request->menu_harga,
                'produk_jumlah' => $request->produk_jumlah,
                'total' => $request->menu_harga
            ]);


            return response()->json([
                'message' => 'Program created successfully.',
                'data' => new KeranjangResource($program)
            ]);
        } else {
            if ($data[0]->menu_id == $request->menu_id && $data[0]->user_id == $request->user_id) {
                Keranjang::where('user_id', $request->user_id)->where('menu_id', $request->menu_id)
                    ->update([
                        'produk_jumlah' => $data[0]->produk_jumlah + 1,
                    ]);

                return response()->json([
                    'message' => 'Program created successfully.',
                    // 'data' => Keranjang::where('user_id', $request->user_id)->where('menu_id', $request->menu_id)
                    //     ->get()
                ]);
            }
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
        //id disini yaitu user_id
        $data = Keranjang::where('user_id', $id)->get();
        return response()->json([
            'message' => 'Berhasil Menampilkan Data',
            'data' => $data
        ]);
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
        try {
            Keranjang::where('menu_id', $id)->where('user_id', $request->user_id)
                ->update([
                    'produk_jumlah' => $request->produk_jumlah,
                    'total' => $request->total
                ]);
            return response()->json([
                'message' => 'Data Berhasil Diedit',
                'data' => Keranjang::where('menu_id', $id)->where('user_id', $request->user_id)->get()
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
            $menu = Keranjang::find($id);
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
