@extends('templates.header-potrait-kop')
@section('content')
<div class="mb-3 text-center">
  <img src="{{ asset('assets/images/bismillah-2.png') }}" alt="basmallah" style="width: 35%">
</div>

<div class="text-right mb-3">
  Pekalongan, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
</div>

<table class="mb-3">
  <tr>
    <th style="width: 300px;">Nomor</th>
    <td style="width: 50px;">:</td>
    <td>
      <?= $nomor ?>
    </td>
  </tr>
  <tr>
    <th style="width: 300px;">Hal</th>
    <td style="width: 50px;">:</td>
    <td>Undangan</td>
  </tr>
  <tr>
    <th style="width: 300px;">Lampiran</th>
    <td style="width: 50px;">:</td>
    <td>
      <?= count($penerima) <= 7 ? '-' : '1 (satu) berkas' ?>
    </td>
  </tr>
</table>

<div class="mb-2">
  <span class="p-0 m-0">Kepada Yth.</span>
  @if (count($penerima) <= 5) <ol class="mr-2" style="list-style-type: decimal; padding-inline-start: 20px;">
    @foreach ($penerima as $key => $item)
    <li>{{ $item->pegawai->nama }}</li>
    @endforeach
    </ol>
    @else
    <p>Nama-nama terlampir</p>
    @endif
</div>

<p class="mb-3">
  Di <br />
  <span class="ml-5">Tempat</span>
</p>

<p style="line-height: 1.8">
  <strong><i>Assalamu'alaikum Warahmatullahi Wabarakatuh</i></strong> <br />
  Puji syukur kami panjatkan kepada Allah SWT atas rahmat-Nya yang melimpah. Kami bersyukur atas petunjuk-Nya yang tak
  pernah terputus. Semoga kita selalu berada dalam lindungan-Nya dan mendapatkan keberkahan-Nya. Aamiin. <br />
  Dihomon kehadirannya pada :
</p>

<div style="width: 90%; margin: auto;" class="mb-0">
  <table class="table table-borderless table-sm">
    <tr>
      <th style="width: 20%;">Hari</th>
      <td style="width: 20px;">:</td>
      <td>{{ \Carbon\Carbon::parse($undangan->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
    </tr>
    <tr>
      <th style="width: 20%;">Jam</th>
      <td style="width: 20px;">:</td>
      <td>{{ \Carbon\Carbon::parse($undangan->tanggal)->isoFormat('HH:mm') }} WIB s/d selesai</td>
    </tr>
    <tr>
      <th style="width: 20%;">Tempat</th>
      <td style="width: 20px;">:</td>
      <td>{{ $undangan->tempat }}</td>
    </tr>
    <tr>
      <th style="width: 20%;">Acara</th>
      <td style="width: 20px;">:</td>
      <td>{{ $undangan->perihal }}</td>
    </tr>
  </table>
</div>

<p class="mb-5">
  Demikian disampaikan,. Terimakasih. <br />
  <i>Nasrun minallohi wa fatkhun qorieb.</i> <br />
  <strong><i>Wassalamu'alaikum Warahmatullahi Wabarakatuh</i></strong>
</p>

<table class="table table-borderless table-sm">
  <tr>
    <td class="p-0 m-0" style="width:30%;"></td>
    <td class="p-0 m-0" style="width:30%;"></td>
    <td class="p-0 m-0">
      <p class="mb-0 pb-0">Pekalongan, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
      {{-- $undangan->penanggungJawab->jenjang_jabatan->nama is capital make is cammel case--}}
      <p class="mb-0 pb-0" style="font-weight: bold; text-transform: capitalize;">{{
        strtolower($undangan->penanggungJawab->jenjang_jabatan->nama) }}</p>
      <br /><br /><br /><br />
      <p class="mb-0 pb-0" style="font-weight: bold">{{ $undangan->penanggungJawab->nama }}</p>
    </td>
  </tr>
</table>

{{-- if undangan->catatan not null not empty or not - --}}
@if ($undangan->catatan != null && $undangan->catatan != '-' && $undangan->catatan != '')
<div class="mt-4">
  <strong>
    NB : {{ $undangan->catatan }}
  </strong>
</div>
@endif

@if (count($penerima) > 5)
{{-- break for penerima --}}
<div class="break-page"></div>

<h5 class="text-center" style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">
  Daftar Nama Terundang
</h5>

<div class="mt-4">
  <table class="table table-sm">
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Bidang</th>
      <th>Jabatan</th>
    </tr>
    <tbody>
      @foreach ($penerima as $key => $item)
      <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->pegawai->nama }}</td>
        <td>{{ $item->pegawai->bidang }}</td>
        <td>{{ $item->pegawai->jbtn }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif


{{-- Daftar Hadir --}}
<div class="break-page"></div>
<?php $maxRow = 25 ?>

<h5 class="text-center" style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">
  <u>DAFTAR HADIR</u>
</h5>

<table>
  <tr>
    <th style="width: 4cm;">Hari / Tanggal</th>
    <td>: {{ \Carbon\Carbon::parse($undangan->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
  </tr>
  <tr>
    <th style="width: 4cm;">Jam</th>
    <td>: {{ \Carbon\Carbon::parse($undangan->tanggal)->isoFormat('HH:mm') }} WIB s/d selesai</td>
  </tr>
  <tr>
    <th style="width: 4cm;">Agenda</th>
    <td>: {{ $undangan->perihal }}</td>
  </tr>
</table>

<div class="mt-0">
  <div class="text-right">
    <small class="text-muted" style="color: #6c757d !important; font-size: 0.7rem !important;">
      Generated automatically by system - {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y HH:mm') }}
    </small>
  </div>
  <table class="table table-bordered table-sm">
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th style="width:4cm !important;">TTD</th>
    </tr>
    <tbody>
      @foreach ($penerima as $key => $item)
      <tr>
        <td class="text-center">{{ $key + 1 }}.</td>
        <td>{{ $item->pegawai->nama }}</td>
        {{-- if in jbtn contain koordinator change to koor --}}
        <td>
          @if (str_contains($item->pegawai->jbtn, 'Koordinator') || str_contains($item->pegawai->jbtn, 'koordinator'))
            @if (str_contains($item->pegawai->jbtn, 'Koordinator'))
              {{ str_replace('Koordinator', 'Koor', $item->pegawai->jbtn) }}
            @elseif (str_contains($item->pegawai->jbtn, 'koordinator'))
              {{ str_replace('koordinator', 'Koor', $item->pegawai->jbtn) }}
            @endif
          @else
            @if (strlen($item->pegawai->jbtn) > 10)
              {{ ucwords(strtolower($item->pegawai->jbtn)) }}
            @else
            {{ strtoupper($item->pegawai->jbtn) }}
            @endif
          @endif
        </td>
        <td>
          @if ($key % 2 == 0)
          <div class="">{{ $key + 1 }}.</div>
          @else
          <div class="text-center">{{ $key + 1 }}.</div>
          @endif
        </td>
      </tr>
      @endforeach

      {{-- if count penerima < maxRow loop blank  --}}
      @if (count($penerima) < $maxRow)
        @for ($i = 0; $i < $maxRow - count($penerima); $i++) 
          <tr>
            <td class="text-center">
              {{-- lanjutkan penomoran jika tr diatas sudah 20 maka disini dimulai dari 21 --}}
              {{ $i + count($penerima) + 1 }}.
            </td>
            <td><div class="my-2"></div></td>
            <td><div class="my-2"></div></td>
            <td>
              @if (($i + count($penerima)) % 2 == 0)
                <div class="">{{ $i + count($penerima) + 1 }}.</div>
              @else
                <div class="text-center">{{ $i + count($penerima) + 1 }}.</div>
              @endif
            </td>
          </tr>
        @endfor
      @elseif (count($penerima) > $maxRow)
        @for ($i = 0; $i < $maxRow - (count($penerima) % $maxRow); $i++) 
          <tr>
            <td class="text-center">
              {{-- lanjutkan penomoran jika tr diatas sudah 20 maka disini dimulai dari 21 --}}
              {{ $i + count($penerima) + 1 }}.
            </td>
            <td><div class="my-2"></div></td>
            <td><div class="my-2"></div></td>
            <td>
              @if (($i + count($penerima)) % 2 == 0)
                <div class="">{{ $i + count($penerima) + 1 }}.</div>
              @else
                <div class="text-center">{{ $i + count($penerima) + 1 }}.</div>
              @endif
            </td>
          </tr>
          @endfor
          @endif
    </tbody>
  </table>
</div>


@endsection