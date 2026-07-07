<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_SPP_{{ str_replace('/', '-', $filters['tahun_ajaran']) }}_{{ $bulanLabel }}</title>
    <link rel="stylesheet" href="{{ asset('assets/backend/css/styles.min.css') }}">
    <style>
        * { box-sizing: border-box; }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; margin: 0; padding: 0; }
            .print-container { 
                padding: 1cm !important; 
                margin: 0 !important; 
                width: 100% !important; 
                max-width: none !important;
                box-shadow: none !important; 
            }
            @page { 
                size: portrait; 
                margin: 1cm; 
            }
        }

        body {
            background: #f4f4f4;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .print-container {
            background: white;
            width: 210mm; 
            min-height: 297mm;
            margin: 20px auto;
            padding: 2cm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            left: 0;
            top: 0;
            height: 70px;
        }

        .kop-text {
            text-align: center;
            width: 100%;
            padding: 0 70px;
        }

        .kop-text h2 { margin: 0; font-weight: 800; font-size: 17px; text-transform: uppercase; }
        .kop-text h3 { margin: 2px 0; font-weight: 700; font-size: 14px; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 10px; font-style: italic; }

        /* Title */
        .report-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-title h4 {
            display: inline-block;
            border-bottom: 2px solid #000;
            margin-bottom: 5px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
        }

        /* Meta Info */
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
            border-collapse: collapse;
        }
        .meta-info td { padding: 3px 0; vertical-align: top; }

        /* Table */
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            table-layout: fixed;
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid #000;
            padding: 5px 8px;
            word-wrap: break-word;
            vertical-align: middle;
        }
        .table-laporan th {
            background-color: #f0f0f0 !important;
            text-transform: uppercase;
            font-weight: 700;
            text-align: center;
            -webkit-print-color-adjust: exact;
        }

        /* Status Badge for Print */
        .status-text {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
        }

        /* Signature Area */
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            width: 220px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        {{-- Header / Kop Surat --}}
        <div class="kop-surat">
            <img src="{{ asset('assets/images/logo.png') }}" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
                <h3>SMP NEGERI 1 MAUPONGGO SATU ATAP</h3>
                <p>Alamat: Desa Mauponggo, Kec. Mauponggo, Kab. Nagekeo, NTT</p>
                <p>Email: smpn1mauponggosatap@gmail.com | Website: smpn1mauponggo.sch.id</p>
            </div>
        </div>

        {{-- Laporan Title --}}
        <div class="report-title">
            <h4>LAPORAN PEMBAYARAN SPP BULANAN</h4>
            <p class="mb-0 small">Periode: <strong>{{ $bulanLabel }} {{ explode('/', $filters['tahun_ajaran'])[0] }}</strong></p>
        </div>

        {{-- Meta Filter Info --}}
        <table class="meta-info">
            <tr>
                <td width="15%">Tahun Ajaran</td>
                <td width="2%">:</td>
                <td width="33%"><strong>{{ $filters['tahun_ajaran'] }}</strong></td>
                <td width="15%">Bulan Tagihan</td>
                <td width="2%">:</td>
                <td width="33%"><strong>{{ $bulanLabel }}</strong></td>
            </tr>
            <tr>
                <td>Kelas / Tingkat</td>
                <td>:</td>
                <td><strong>{{ $kelasInfo }}</strong></td>
                <td>Filter Status</td>
                <td>:</td>
                <td><strong>{{ isset($filters['status']) && $filters['status'] ? strtoupper(str_replace('_', ' ', $filters['status'])) : 'SEMUA' }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td colspan="4">{{ date('d F Y') }}</td>
            </tr>
        </table>

        {{-- Data Table --}}
        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 220px;">Nama Lengkap Siswa</th>
                    <th style="width: 60px;">Kelas</th>
                    <th style="width: 100px;">Tahun Ajaran</th>
                    <th style="width: 120px;">Nominal Tagihan</th>
                    <th style="width: 100px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($results as $row)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $row->siswa->nama_lengkap }}</td>
                    <td style="text-align: center;">{{ $row->kelas?->nama ?? '—' }}</td>
                    <td style="text-align: center;">{{ $row->tarif->tahun_ajaran }}</td>
                    <td style="text-align: right;">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @php
                            $map = [
                                'belum_bayar' => 'BELUM LUNAS',
                                'sebagian'    => 'SEBAGIAN',
                                'lunas'       => 'LUNAS'
                            ];
                        @endphp
                        <span class="status-text">{{ $map[$row->status] ?? strtoupper($row->status) }}</span>
                    </td>
                </tr>
                @php $total += $row->nominal; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f9f9f9 !important; -webkit-print-color-adjust: exact;">
                    <td colspan="4" style="text-align: right; padding: 10px;">TOTAL POTENSI PEMASUKAN :</td>
                    <td style="text-align: right; padding: 10px;">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Signature Section --}}
        <div class="signature-area">
            <div class="signature-box">
                <p>Mauponggo, {{ date('d F Y') }}</p>
                <p style="margin-bottom: 80px;">Bendahara Sekolah,</p>
                <p style="text-decoration: underline; font-weight: bold; margin-bottom: 0;">( ........................................ )</p>
                <p style="font-size: 12px;">NIP. ..................................</p>
            </div>
        </div>

        {{-- Action Buttons (Screen Only) --}}
        <div class="mt-5 text-center no-print">
            <hr>
            <button onclick="window.print()" class="btn btn-primary px-4 me-2">
                <iconify-icon icon="solar:printer-bold" class="me-1"></iconify-icon> Cetak Laporan
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary px-4">
                Tutup Preview
            </button>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</body>
</html>
