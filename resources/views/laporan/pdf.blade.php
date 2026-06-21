<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            padding: 0;
        }
        .header h3 {
            margin: 5px 0;
            padding: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            border: none;
        }
        .info td {
            border: none;
            padding: 3px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #333;
        }
        .summary-box table {
            border: none;
            margin-bottom: 0;
        }
        .summary-box td {
            border: none;
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 50px;
            display: inline-block;
        }
        h4 {
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN</h2>
        <h3>{{ $user->nama_umkm }}</h3>
        <p>Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="100"><strong>Pemilik</strong></td>
                <td>: {{ $user->name }}</td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>: {{ $user->alamat_umkm ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Telepon</strong></td>
                <td>: {{ $user->telepon ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td width="60%"><strong>Total Pemasukan</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Total Pengeluaran</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Saldo</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($saldo, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <h4>Ringkasan Per Kategori</h4>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Pemasukan</th>
                <th class="text-right">Pengeluaran</th>
                <th class="text-right">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perKategori as $item)
            <tr>
                <td>{{ $item['kategori'] }}</td>
                <td class="text-right">Rp {{ number_format($item['pemasukan'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['pengeluaran'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['pemasukan'] - $item['pengeluaran'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data kategori</td>
            </tr>
            @endforelse
            <tr class="bold">
                <td><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($saldo, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h4>Detail Transaksi</h4>
    <table>
        <thead>
            <tr>
                <th width="80">Tanggal</th>
                <th width="80">Jenis</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-right" width="120">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $t)
            <tr>
                <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                <td>{{ ucfirst($t->jenis) }}</td>
                <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $t->keterangan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <br><br><br>
        <div class="footer-line"></div>
        <p>{{ $user->name }}</p>
    </div>
</body>
</html>