<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RsiaMasterMenuFiletrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::guard('user-aes')->user();
        $menu = \App\Models\RsiaMasterMenuFiletrack::whereHas('setMenu', function ($query) use ($user) {
            $query->where('nik', $user->id_user);
        })->orderBy('urutan', 'asc')->get()->groupBy('group');

        return new \App\Http\Resources\User\Menu\MenuCollection($menu);

        // $menu = $menu->map(function ($item) {
        //     return $item->map(function ($menu) {
        //         return [
        //             'label' => $menu->label,
        //             'icon' => $menu->icon,
        //             'to' => $menu->url
        //         ];
        //     });
        // });

        // // Ubah array asosiatif menjadi array biasa
        // $menu = $menu->values()->toArray();

        // return response()->json([
        //     'status' => 'success',
        //     'data' => $menu
        // ]);
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
}
