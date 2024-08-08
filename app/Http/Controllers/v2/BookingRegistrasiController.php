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
        ]);

        // check if the patient already has a booking
        $booking = new \App\Models\BookingRegistrasi();

        // check if the patient already has a booking
        $existingBooking = $booking->where('no_rkm_medis', $request->no_rkm_medis)
            ->where('tanggal_periksa', $request->tanggal_periksa)
            ->first();

        if ($existingBooking) {
            return ApiResponse::error('Pasien sudah memiliki booking pada tanggal tersebut', 'already_booked', null, 400);
        }

        $jadwal = new \App\Models\JadwalPoli();

        // check limit kuota
        $countBooking = $booking->where('tanggal_periksa', $request->tanggal_periksa)
            ->where('kd_poli', $request->kd_poli)
            ->where('kd_dokter', $request->kd_dokter)
            ->count();

        // get hari in indonesia from tgl_periksa
        $hari = Carbon::parse($request->tanggal_periksa)->translatedFormat('l');

        $dataJadwal = $jadwal->where('kd_dokter', $request->kd_dokter)
            ->where('kd_poli', $request->kd_poli)
            ->where('hari_kerja', strtoupper($hari))
            ->first();

        if ($dataJadwal->kuota == 0) {
            return ApiResponse::error('Kuota pemeriksaan sudah habis, anda bisa booking di jadwal berbeda.', 'kuota_empty', null, 400);
        }

        if ($countBooking >= $dataJadwal->kuota) {
            return ApiResponse::error('Kuota pemeriksaan sudah penuh, anda bisa booking di jadwal berbeda.', 'kuota_full', null, 400);
        }

        // check diff jam
        $now = Carbon::now();
        $jamPraktek = Carbon::parse($dataJadwal->jam_mulai);

        if ($now->diffInHours($jamPraktek) >= 6) {
            return ApiResponse::error("Maksimal booking 6 jam sebelum mulai praktik ( $dataJadwal->jam_mulai WIB).", 'time_limit', null, 400);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $booking) {
            // get the last no_reg for the date of tanggal_periksa
            $lastNoReg = $booking->where('tanggal_periksa', $request->tanggal_periksa)
                ->where('kd_poli', $request->kd_poli)
                ->where('kd_dokter', $request->kd_dokter)
                ->max('no_reg');

            $lastRegistrasi = \App\Models\RegPeriksa::select('no_rawat')
                ->where('tgl_registrasi', $request->tanggal_periksa)
                ->orderBy('no_rawat', 'desc')
                ->first();

            if ($lastRegistrasi) {
                $lastPasienByRegistrasi = explode('/', $lastRegistrasi->no_rawat);
                $lastPasienByRegistrasi = end($lastPasienByRegistrasi);
            } else {
                $lastPasienByRegistrasi = 0;
            }

            if (!$lastNoReg) {
                $lastNoReg = 0;
            }

            $pasienKe = str_pad($lastPasienByRegistrasi + 1, 6, '0', STR_PAD_LEFT);
            $noReg = str_pad($lastNoReg + 1, 3, '0', STR_PAD_LEFT);

            // get the current date and time
            $now = Carbon::now();

            // append the no_reg to the request
            $request->merge([
                'tanggal_booking'     => $now->format('Y-m-d'),
                'jam_booking'         => $now->format('H:i:s'),
                'status'              => 'Terdaftar',
                'no_reg'              => $noReg,
                'waktu_kunjungan'     => $now->format('Y-m-d H:i:s'),
            ]);

            // create the booking data
            $booking->create($request->all());

            // build reg_periksa data
            $regPeriksaData = $this->buildRegPeriksaData($request, $pasienKe);

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

        return ApiResponse::error("Data booking tidak ditemukan", "resource_not_found", null, 404);
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

    /**
     * Build the reg_periksa data from the request
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     * */
    protected function buildRegPeriksaData($request, $lastPasienByNoRawa)
    {
        $pasien     = \App\Models\Pasien::where('no_rkm_medis', $request->no_rkm_medis)->first();
        $poliklinik = \App\Models\Poliklinik::where('kd_poli', $request->kd_poli)->first();
        $regPeriksa = \App\Models\RegPeriksa::where('no_rkm_medis', $request->no_rkm_medis);

        // calculate the age of the patient
        $umurDaftarMonths = Carbon::parse($request->tanggal_periksa)->diffInMonths($pasien->tgl_lahir);
        $umurDaftarYears  = Carbon::parse($request->tanggal_periksa)->diffInYears($pasien->tgl_lahir);

        return [
            'no_reg'         => $request->no_reg,
            'no_rawat'       => $this->buildNoRawat($request, $lastPasienByNoRawa),
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

    protected function buildNoRawat($request, $lastPasienByNoRawa)
    {
        return Carbon::parse($request->tanggal_periksa)->format('Y/m/d') . '/' . str_pad($lastPasienByNoRawa, 6, '0', STR_PAD_LEFT);
    }

    protected function buildAlamatPj($pasien)
    {
        return $pasien->alamatpj . ', ' . $pasien->kecamatanpj . ', ' . $pasien->kabupatenpj . ', ' . $pasien->propinsipj;
    }
}
