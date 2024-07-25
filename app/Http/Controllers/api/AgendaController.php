<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RsiaPenerimaUndangan;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ageda is all data from penerima undangan  where has whereHas  relatedModel and group by no_surat
        $agenda = RsiaPenerimaUndangan::select(['no_surat', 'tipe', 'model'])
            ->whereBetweenDate($request->input('start'), $request->input('end'))
            ->with('relatedModel')->get();
        
        // get only unique no_surat from agenda and eliminate duplicate
        $agenda = $agenda->unique('no_surat');

        $mappedAgendaa = $agenda->map(function($item) {
            $arrayItem = $item->toArray();
            return [
                'id'          => $arrayItem['no_surat']['no_surat'],
                'no_surat'    => $arrayItem['no_surat']['no_surat'],
                'tipe'        => $arrayItem['tipe'],

                'title'       => $arrayItem['no_surat']['perihal'],
                'location'    => $arrayItem['no_surat']['tempat'] ?? '-',
                'description' => $arrayItem['no_surat']['catatan'] ?? '-',

                'start'       => explode(' ', $arrayItem['no_surat']['tanggal'] ?? $arrayItem['no_surat']['tgl_terbit'])[0],
                'end'         => explode(' ', $arrayItem['no_surat']['tanggal'] ?? $arrayItem['no_surat']['tgl_terbit'])[0],

                'calendarId'  => $arrayItem['tipe'],
            ];
        });

        return new \App\Http\Resources\RealDataCollection($mappedAgendaa);
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
