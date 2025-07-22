<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KomputerExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $komputers;
    protected $columns;
    protected $headerRow;

    public function __construct($komputers, $columns)
    {
        $this->komputers = $komputers;
        $this->columns = $columns;
        $this->headerRow = $this->getHeaderRow($columns);
    }

    public function collection()
    {
        return collect($this->komputers)->map(function ($komputer) {
            return $this->formatDataRow($komputer, $this->columns);
        });
    }

    public function headings(): array
    {
        return $this->headerRow;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function getHeaderRow($columns)
    {
        $headerMap = [
            'kode_barang' => 'Kode Barang',
            'nama_komputer' => 'Nama Komputer',
            'ruangan' => 'Ruangan',
            'nama_pengguna' => 'Pengguna',
            'spesifikasi' => 'Spesifikasi',
            'kondisi' => 'Kondisi',
            'penggunaan' => 'Penggunaan',
            'tanggal_pengadaan' => 'Tanggal Pengadaan'
        ];
        
        $headers = [];
        foreach ($columns as $column) {
            if (isset($headerMap[$column])) {
                $headers[] = $headerMap[$column];
            }
        }
        
        return $headers;
    }

    private function formatDataRow($komputer, $columns)
    {
        $row = [];
        
        foreach ($columns as $column) {
            switch ($column) {
                case 'kode_barang':
                    $row[] = $komputer->kode_barang;
                    break;
                case 'nama_komputer':
                    $row[] = $komputer->nama_komputer;
                    break;
                case 'ruangan':
                    $row[] = $komputer->ruangan ? $komputer->ruangan->nama_ruangan : 'Tidak tersedia';
                    break;
                case 'nama_pengguna':
                    $row[] = $komputer->nama_pengguna_sekarang;
                    break;
                case 'spesifikasi':
                    $row[] = "Processor: {$komputer->spesifikasi_processor}, RAM: {$komputer->spesifikasi_ram}, Storage: {$komputer->spesifikasi_penyimpanan}";
                    break;
                case 'kondisi':
                    $row[] = $komputer->kondisi_komputer;
                    break;
                case 'penggunaan':
                    $row[] = $komputer->penggunaan_sekarang;
                    break;
                case 'tanggal_pengadaan':
                    $row[] = $komputer->tahun_pengadaan;
                    break;
            }
        }
        
        return $row;
    }
}
