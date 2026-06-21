<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $tanggalMulai;
    protected $tanggalAkhir;

    public function __construct($tanggalMulai, $tanggalAkhir)
    {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function collection()
    {
        return Transaksi::where('user_id', auth()->id())
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalAkhir])
            ->with('kategori')
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Kategori',
            'Keterangan',
            'Jumlah'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->tanggal->format('d/m/Y'),
            ucfirst($transaksi->jenis),
            $transaksi->kategori->nama_kategori ?? 'Tanpa Kategori',
            $transaksi->keterangan,
            'Rp ' . number_format($transaksi->jumlah, 0, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}