<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Peminjaman Armada Dinas - RSUD Sidawangi</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.3;
        }
        .header-kop {
            text-align: center;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }
        .header-kop h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: normal;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-kop h2 {
            margin: 2px 0;
            font-size: 14pt;
            font-weight: bold;
            color: #0369a1;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-kop p {
            margin: 0;
            font-size: 8pt;
            color: #64748b;
        }
        .report-title {
            text-align: center;
            margin: 10px 0 14px 0;
        }
        .report-title h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
        }
        .report-title .periode {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 3px;
        }
        .kpi-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .kpi-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            text-align: center;
        }
        .kpi-box .val {
            font-size: 12pt;
            font-weight: bold;
            color: #0284c7;
        }
        .kpi-box .lbl {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #94a3b8;
            text-align: center;
        }
        table.data-table td {
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-danger { background-color: #ffe4e6; color: #be123c; }
        .badge-gray { background-color: #f1f5f9; color: #475569; }
        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .signature-space {
            height: 50px;
        }
        .footer-note {
            margin-top: 15px;
            font-size: 7pt;
            color: #94a3b8;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- KOP RESMI RSUD SIDAWANGI -->
    <div class="header-kop">
        <h3>Pemerintah Daerah Provinsi Jawa Barat</h3>
        <h2>{{ \App\Models\Setting::get('nama_instansi', 'Rumah Sakit Umum Daerah Sidawangi') }}</h2>
        <p>{{ \App\Models\Setting::get('alamat_instansi', 'Jl. Pangeran Kejaksan, Sidawangi, Kec. Sumber, Kabupaten Cirebon, Jawa Barat') }} · Telp: {{ \App\Models\Setting::get('kontak_pool', '(0231) 8331234') }}</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        <h1>Rekapitulasi Peminjaman & Utilisasi Kendaraan Dinas</h1>
        <div class="periode">
            Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
            @if($unitKerjaNama) · Unit: <strong>{{ $unitKerjaNama }}</strong> @endif
            @if($statusLabel) · Filter Status: <strong>{{ $statusLabel }}</strong> @endif
        </div>
    </div>

    <!-- KPI BOXES -->
    <table class="kpi-table">
        <tr>
            <td style="width: 25%; padding-right: 6px;">
                <div class="kpi-box">
                    <div class="val">{{ $bookings->count() }}</div>
                    <div class="lbl">Total Permohonan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 3px;">
                <div class="kpi-box">
                    <div class="val">{{ $bookings->whereIn('status', ['disetujui', 'kendaraan_keluar', 'kendaraan_kembali', 'selesai'])->count() }}</div>
                    <div class="lbl">Disetujui & Berjalan</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 3px;">
                <div class="kpi-box">
                    <div class="val">{{ $bookings->whereIn('status', ['ditolak_garasi', 'ditolak_pimpinan'])->count() }}</div>
                    <div class="lbl">Ditolak</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 6px;">
                <div class="kpi-box">
                    <div class="val">{{ number_format($totalKm, 0, ',', '.') }} km</div>
                    <div class="lbl">Akumulasi Jarak Tempuh</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABEL DATA DETAIL -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 75px;">Kode</th>
                <th style="width: 60px;">Tgl Berangkat</th>
                <th style="width: 60px;">Tgl Kembali</th>
                <th style="width: 90px;">Pemohon / Unit</th>
                <th style="width: 85px;">Kendaraan</th>
                <th style="width: 75px;">Sopir / Swakemudi</th>
                <th>Tujuan & Keperluan</th>
                <th style="width: 45px;">KM Tempuh</th>
                <th style="width: 60px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $idx => $b)
                @php
                    $odoKeluar = $b->checkout?->odometer_keluar;
                    $odoMasuk = $b->checkin?->odometer_masuk;
                    $km = ($odoKeluar && $odoMasuk) ? ($odoMasuk - $odoKeluar) : null;
                    $badgeClass = match ($b->status) {
                        'disetujui' => 'badge-info',
                        'kendaraan_keluar' => 'badge-info',
                        'selesai' => 'badge-success',
                        'ditolak_garasi', 'ditolak_pimpinan', 'dibatalkan' => 'badge-danger',
                        default => 'badge-warning',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center" style="font-family: monospace; font-weight: bold;">{{ $b->kode_peminjaman }}</td>
                    <td class="text-center">{{ $b->tanggal_berangkat?->format('d/m/Y') }}<br><small style="color: #64748b;">{{ $b->jam_berangkat }}</small></td>
                    <td class="text-center">{{ $b->tanggal_kembali_rencana?->format('d/m/Y') }}<br><small style="color: #64748b;">{{ $b->jam_kembali_rencana }}</small></td>
                    <td>
                        <strong>{{ $b->user?->name }}</strong><br>
                        <span style="color: #64748b; font-size: 7pt;">{{ $b->unitKerja?->nama_unit }}</span>
                    </td>
                    <td>
                        <strong>{{ $b->vehicle?->no_polisi ?? 'Belum Ditunjuk' }}</strong><br>
                        <span style="color: #64748b; font-size: 7pt;">{{ $b->vehicle?->tipe_model }}</span>
                    </td>
                    <td>
                        {{ $b->jenis_pengemudi === 'sopir_dinas' ? ($b->driver?->nama ?? 'Sopir Pool') : 'Swakemudi' }}
                    </td>
                    <td>
                        <strong>{{ $b->kota_tujuan }}</strong> - {{ $b->tujuan_perjalanan }}
                    </td>
                    <td class="text-right">
                        {{ $km !== null ? number_format($km, 0, ',', '.') . ' km' : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $b->status_label }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data peminjaman kendaraan yang sesuai dengan kriteria filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN & TANDA TANGAN -->
    @php
        $kotaTtd = \App\Models\Setting::get('kota_penandatanganan', 'Cirebon');
        $direkturJabatan = \App\Models\Setting::get('direktur_jabatan', 'Plt. Direktur RSUD Sidawangi');
        $direkturNama = \App\Models\Setting::get('direktur_nama', 'dr. H. Hadri Pramono, Sp.P');
        $direkturNip = \App\Models\Setting::get('direktur_nip', '197405102002121003');

        $pejabatPoolJabatan = \App\Models\Setting::get('pejabat_pool_jabatan', 'Kepala Bagian Umum & Rumah Tangga / Pool');
        $pejabatPoolNama = \App\Models\Setting::get('pejabat_pool_nama', 'Kusnadi, S.Sos., M.Si');
        $pejabatPoolNip = \App\Models\Setting::get('pejabat_pool_nip', '198003152008011006');
    @endphp
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>{{ $direkturJabatan }}</strong><br>
                Provinsi Jawa Barat
                <div class="signature-space"></div>
                <strong><u>{{ $direkturNama }}</u></strong><br>
                NIP. {{ $direkturNip }}
            </td>
            <td>
                {{ $kotaTtd }}, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
                <strong>{{ $pejabatPoolJabatan }}</strong><br>
                RSUD Sidawangi Provinsi Jawa Barat
                <div class="signature-space"></div>
                <strong><u>{{ $pejabatPoolNama }}</u></strong><br>
                NIP. {{ $pejabatPoolNip }}
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen dicetak secara otomatis melalui MAS PENDI (Manajemen Aset dan Peminjaman Kendaraan Dinas RSUD Sidawangi) pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB.
    </div>

</body>
</html>
