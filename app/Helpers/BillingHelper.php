<?php

namespace App\Helpers;

class BillingHelper
{
    public static function determineTarifOperasiTitle($columnName)
    {
        $columns = [
            "biayaoperator1"          => "Biaya Operator 1",
            "biayaoperator2"          => "Biaya Operator 2",
            "biayaoperator3"          => "Biaya Operator 3",
            "biayaasisten_operator1"  => "Biaya Asisten Operator 1",
            "biayaasisten_operator2"  => "Biaya Asisten Operator 2",
            "biayaasisten_operator3"  => "Biaya Asisten Operator 3",
            "biayadokter_anestesi"    => "Biaya Dokter Anestesi",
            "biayaasisten_anestesi"   => "Biaya Asisten Anestesi",
            "biayaasisten_anestesi2"  => "Biaya Asisten Anestesi 2",
            "biayabidan"              => "Bidan",
            "biayabidan2"             => "Bidan 2",
            "biayabidan3"             => "Bidan 3",
            "biaya_omloop"            => "Biaya Onloop 1",
            "biaya_omloop2"           => "Biaya Onloop 2",
            "biaya_omloop3"           => "Biaya Onloop 3",
            "biaya_omloop4"           => "Biaya Onloop 4",
            "biaya_omloop5"           => "Biaya Onloop 5",
            "biaya_dokter_pjanak"     => "Biaya Dokter Penanggung Jawab Anak",
            "biaya_dokter_umum"       => "Biaya Dokter Umum",
            "biayainstrumen"          => "Biaya Instrumen",
            "biayadokter_anak"        => "Biaya Dokter Anak",
            "biayaperawaat_resusitas" => "Biaya Perawaat Resusitas",
            "biayaperawat_luar"       => "Biaya Perawat Luar",
            "biayaalat"               => "Biaya Sewa Alat",
            "biayasewaok"             => "Biaya Sewa OK/VK",
            "akomodasi"               => "Biaya Akomodasi",
            "bagian_rs"               => "N.M.S.",
            "biayasarpras"            => "Sarpras",
        ];

        return $columns[$columnName];
    }
}
