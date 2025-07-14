<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Surat Bebas Temuan</title>
    <style>
        @page {
            size: 595.28pt 935.43pt;
            margin: 2cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .logo {
            position: absolute;
            top: 30px;
            left: 30px;
            width: 75px;
        }

        .sub-header {
            text-align: center;
            font-size: 10.5pt;
            margin-bottom: 5px;
        }

        .title {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            text-decoration: underline;
            font-size: 12.5pt;
        }

        .nomor {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            padding: 0 20px;
        }

        .info-table {
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .footer {
            width: 100%;
            margin-top: 40px;
            padding-right: 20px;
        }

        .footer .right {
            float: right;
            width: 50%;
            text-align: center;
        }

        .signature {
            margin-top: 40px;
            /* spasi dikurangi dari 60px */
            text-align: center;
            line-height: 1.4;
        }

        .bold {
            font-weight: bold;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    {{-- Logo di kiri atas --}}
    <img src="{{ public_path('logosumsel-removebg-preview.png') }}" class="logo" alt="Logo">

    <div class="header">
        PEMERINTAH PROVINSI SUMATERA SELATAN
    </div>
    <div class="header" style="font-size: 14pt;">
        INSPEKTORAT DAERAH PROVINSI
    </div>
    <div class="sub-header">
        Jl. Ade Irma Nasution No.Telp.354221 – Fax.350977
    </div>
    <div class="header" style="letter-spacing: 4px;">
        P A L E M B A N G
    </div>

    <div class="title">
        SURAT KETERANGAN <br>
        BEBAS TEMUAN INSPEKTORAT DAERAH PROVINSI SUMATERA SELATAN
    </div>
    <?php

    use Illuminate\Support\Str;

    $random = strtoupper(Str::random(6));
    $tahun = date('Y');
    $nomorSurat = "SURAT-$random-$tahun"; ?>
    <div class="nomor">
        Nomor : {{$nomorSurat}}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini :</p>
        <table class="info-table">
            <tr>
                <td width="30%">Nama</td>
                <td width="2%">:</td>
                <td>H. KURNIAWAN, AP., M.Si., CGCAE</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>197506171995011001</td>
            </tr>
            <tr>
                <td>Pangkat/Gol. Ruang</td>
                <td>:</td>
                <td>Pembina Utama Madya (IV/d)</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>Inspektur Provinsi Sumatera Selatan</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>Pemerintah Provinsi Sumatera Selatan</td>
            </tr>
        </table>

        <p>Dengan ini menerangkan dengan sesungguhnya, bahwa Pegawai Negeri Sipil :</p>
        <table class="info-table">
            <tr>
                <td width="30%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $pegawai->nama ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $pegawai->nip ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Pangkat/Gol. Ruang</td>
                <td>:</td>
                <td>{{ $pegawai->pangkat_golongan ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pegawai->jabatan ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $pegawai->unit_kerja ?? 'N/A' }}</td>
            </tr>
        </table>
        @if ($pengajuan->jenis_surat == "Mutasi Jabatan")
        <p>Surat keterangan ini diajukan sebagai kelengkapan administrasi untuk keperluan **mutasi jabatan** menuju
            {{$pengajuan->tujuan_mutasi}}.</p>
        @endif

        @if ($pengajuan->jenis_surat == "Promosi Jabatan")
        <p>Surat keterangan ini diajukan sebagai kelengkapan administrasi untuk keperluan **promosi jabatan**.</p>
        @endif

        @if ($pengajuan->jenis_surat == "Audit Kerja")
        <p>Surat keterangan ini diajukan sebagai kelengkapan administrasi untuk keperluan **audit kinerja/kerja**.</p>
        @endif

    </div>

    <div class="footer">
        <div class="right">
            Palembang, {{ \Carbon\Carbon::now()->format('d F Y') }} <br>
            INSPEKTUR <br>
            PROVINSI SUMATERA SELATAN

            <div class="signature">
                <div class="bold">H. KURNIAWAN, AP., M.Si., CGCAE</div>
                <div>Pembina Utama Madya (IV/d)</div>
                <div>NIP. 19750617 199501 1001</div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</body>

</html>