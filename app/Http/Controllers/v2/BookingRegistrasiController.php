<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingRegistrasiController extends Controller
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
        // tanggal_booking, jam_booking, no_rkm_medis, tanggal_periksa, kd_dokter, kd_poli, no_reg, kd_pj, limit_reg, waktu_kunjungan, status
        $request->validate([
            'no_rkm_medis'    => 'required|exists:pasien,no_rkm_medis',
            'tanggal_periksa' => 'required|date',
            'kd_dokter'       => 'required|exists:dokter,kd_dokter',
            'kd_poli'         => 'required|exists:poliklinik,kd_poli',
            'kd_pj'           => 'required|exists:penjab,kd_pj',
            'limit_reg'       => 'required|numeric|in:0,1',
            'waktu_kunjungan' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // check if the patient already has a booking
        $booking = new \App\Models\BookingRegistrasi();

        // check if the patient already has a booking
        $existingBooking = $booking->where('no_rkm_medis', $request->no_rkm_medis)
            ->where('tanggal_periksa', $request->tanggal_periksa)
            ->first();

        if ($existingBooking) {
            return ApiResponse::error('already_booked', 'Pasien sudah memiliki booking pada tanggal tersebut');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $booking) {
            // get the last no_reg for the date of tanggal_periksa
            $lastNoReg = $booking->where('tanggal_periksa', $request->tanggal_periksa)
                ->max('no_reg');
            
                // lastNoReg example 002 + 1 = 003
            $noReg = str_pad($lastNoReg + 1, 3, '0', STR_PAD_LEFT);

            // append the no_reg to the request
            $request->merge([
                // tanggal booking format Y-m-d
                'tanggal_booking' => \Carbon\Carbon::now()->format('Y-m-d'),
                'jam_booking' => \Carbon\Carbon::now()->format('H:i:s'),
                'status' => 'Terdaftar',
                'no_reg' => $noReg,
            ]);

            // create the booking data
            $booking->create($request->all());

            // build reg_periksa data
            $regPeriksaData = $this->buildRegPeriksaData($request);

            // create the reg_periksa data
            \App\Models\RegPeriksa::create($regPeriksaData);
        }, 5); // optional: set the number of attempts, default is 1

        // if everything is successful, return the success response
        return ApiResponse::success('Booking berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $base64_no_rkm_medis_and_tanggal_periksa
     * @return \Illuminate\Http\Response
     */
    public function show($base64_no_rkm_medis_and_tanggal_periksa, Request $request)
    {
        $includes = [];
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
        }

        $decoded = base64_decode($base64_no_rkm_medis_and_tanggal_periksa);
        $exploded = explode('.', $decoded);

        $no_rkm_medis = $exploded[0];
        $tanggal_periksa = $exploded[1];

        $booking = new \App\Models\BookingRegistrasi();

        $data = $booking->where('no_rkm_medis', $no_rkm_medis)
            ->where('tanggal_periksa', $tanggal_periksa)
            ->with($includes)
            ->first();

        if ($data) {
            return new \App\Http\Resources\RealDataResource($data);
        }

        return ApiResponse::error('not_found', 'Data booking tidak ditemukan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // INFO : implement edit booking if needed
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
        // INFO : implement update booking if needed
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // INFO : implement delete booking if needed
    }

    /**
     * Build the reg_periksa data from the request
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     * */
    protected function buildRegPeriksaData($request)
    {
        $pasien     = \App\Models\Pasien::where('no_rkm_medis', $request->no_rkm_medis)->first();
        $poliklinik = \App\Models\Poliklinik::where('kd_poli', $request->kd_poli)->first();
        $regPeriksa = \App\Models\RegPeriksa::where('no_rkm_medis', $request->no_rkm_medis);

        // calculate the age of the patient
        $umurDaftarMonths = Carbon::parse($request->tanggal_periksa)->diffInMonths($pasien->tgl_lahir);
        $umurDaftarYears  = Carbon::parse($request->tanggal_periksa)->diffInYears($pasien->tgl_lahir);

        return [
            'no_reg'         => $request->no_reg,
            'no_rawat'       => $this->buildNoRawat($request),
            'tgl_registrasi' => $request->tanggal_periksa,
            'jam_reg'        => Carbon::now()->format('H:i:s'),
            'kd_dokter'      => $request->kd_dokter,
            'no_rkm_medis'   => $request->no_rkm_medis,
            'kd_poli'        => $request->kd_poli,
            'p_jawab'        => $pasien->namakeluarga,
            'almt_pj'        => $this->buildAlamatPj($pasien),
            'hubunganpj'     => $pasien->keluarga,
            'biaya_reg'      => $poliklinik->registrasi,
            'stts'           => 'Belum',
            'stts_daftar'    => (clone $regPeriksa)->exists() ? 'Lama' : 'Baru',
            'status_lanjut'  => 'Ralan',
            'kd_pj'          => $request->kd_pj,
            'umurdaftar'     => $umurDaftarMonths <= 12 ? $umurDaftarMonths : $umurDaftarYears,
            'sttsumur'       => $umurDaftarMonths <= 12 ? 'Bl' : 'Th',
            'status_bayar'   => 'Belum Bayar',
            'status_poli'    => (clone $regPeriksa)->where('kd_poli', $request->kd_poli)->exists() ? 'Lama' : 'Baru',
        ];
    }

    protected function buildNoRawat($request)
    {
        return Carbon::parse($request->tanggal_periksa)->format('Y/m/d') . '/' . str_pad($request->no_reg, 6, '0', STR_PAD_LEFT);
    }

    protected function buildAlamatPj($pasien)
    {
        return $pasien->alamatpj . ', ' . $pasien->kecamatanpj . ', ' . $pasien->kabupatenpj . ', ' . $pasien->propinsipj;
    }
}
