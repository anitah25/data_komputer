<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komputer;
use App\Service\Komputer\KomputerGetData;
use App\Service\Komputer\KomputerStore;
use App\Service\Komputer\KomputerUpdate;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\KomputerExport;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class KomputerController extends Controller
{

    private $komputerGetData;
    private $komputerStore;
    private $komputerUpdate;

    public function __construct(KomputerGetData $komputerGetData, KomputerStore $komputerStore, KomputerUpdate $komputerUpdate)
    {
        $this->komputerGetData = $komputerGetData;
        $this->komputerStore = $komputerStore;
        $this->komputerUpdate = $komputerUpdate;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $komputers = $this->komputerGetData->getFilteredKomputers($request->all(), 10);

        $ruangan = $this->komputerGetData->getUniqueRuangan();

        return view(
            'admin.komputer.daftar',
            [
                'komputers' => $komputers,
                'ruangan' => $ruangan,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan komputer baru
        return view('admin.komputer.tambah', [
            'ruangans' => $this->komputerGetData->getUniqueRuangan(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // validasi data
            $validated = $this->komputerStore->validateInput($request);

            // generate barcode
            $barcode = $this->komputerStore->generateQRCode($validated['kode_barang']);
            $validated['barcode'] = $barcode;

            // simpan data komputer
            $komputer = $this->komputerStore->storeKomputer($validated);

            // simpan galeri foto
            $this->komputerStore->storeGallery($komputer, $request);

            DB::commit();

            return redirect()
                ->route('komputer.index')
                ->with('success', 'Data komputer berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Change withErrors to with to match the check in the view
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan data komputer: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kode_barang)
    {
        return view('admin.komputer.detail', [
            'komputer' => $this->komputerGetData->getByKodeBarang($kode_barang),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $kode_barang)
    {
        return view('admin.komputer.edit', [
            'komputer' => $this->komputerGetData->getByKodeBarang($kode_barang),
            'ruangans' => $this->komputerGetData->getUniqueRuangan()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validated = $this->komputerUpdate->validateInput($request, $id);

            if ($request->get('old_nomor_aset') != $validated['nomor_aset']) {
                // Generate new QR code if nomor_aset has changed
                $barcode = $this->komputerStore->generateQRCode($validated['nomor_aset']);
                $validated['barcode'] = $barcode;
            }

            $komputer = Komputer::findOrFail($id);
            $komputer = $this->komputerUpdate->updateKomputer($komputer, $validated);

            $this->komputerUpdate->handleGallery($komputer, $request);

            DB::commit();

            return redirect()
                ->route('komputer.index')
                ->with('success', 'Data komputer berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Change withErrors to with to match the check in the view
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui data komputer: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $komputer = Komputer::findOrFail($id);

            // Delete all gallery images associated with this computer
            foreach ($komputer->galleries as $gallery) {
                if (Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                $gallery->delete();
            }

            // Delete the computer record
            $komputer->delete();

            // Clear cache for this computer
            Cache::forget('komputer_' . $komputer->nomor_aset);
            Cache::forget('ruangan_list');

            DB::commit();

            return redirect()
                ->route('komputer.index')
                ->with('success', 'Data komputer berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data komputer: ' . $e->getMessage());
        }
    }

    /**
     * Export data to specified format
     */
    public function export(Request $request)
    {
        // Get query parameters
        $format = $request->input('format', 'excel');
        $columns = $request->input('columns', ['kode_barang', 'nama_komputer', 'ruangan', 'nama_pengguna', 'spesifikasi', 'kondisi', 'penggunaan']);
        
        // Build the query with filters
        $query = Komputer::query()->with('ruangan');
        
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('nama_komputer', 'like', "%{$keyword}%")
                  ->orWhere('nama_pengguna_sekarang', 'like', "%{$keyword}%");
            });
        }
        
        if ($request->filled('ruangan')) {
            $query->where('ruangan_id', $request->input('ruangan'));
        }
        
        if ($request->filled('kondisi')) {
            $query->where('kondisi_komputer', $request->input('kondisi'));
        }
        
        // Get data
        $komputers = $query->get();
        
        // Format timestamp for filename
        $timestamp = now()->format('Ymd_His');
        $filename = "data_komputer_{$timestamp}";
        
        // Create export based on requested format
        switch ($format) {
            case 'csv':
                return $this->exportToCSV($komputers, $columns, $filename);
            case 'pdf':
                return $this->exportToPDF($komputers, $columns, $filename);
            case 'excel':
            default:
                return $this->exportToExcel($komputers, $columns, $filename);
        }
    }

    /**
     * Helper method to export to Excel
     */
    private function exportToExcel($komputers, $columns, $filename)
    {
        // This requires the Laravel Excel package
        // composer require maatwebsite/excel
        
        return FacadesExcel::download(new KomputerExport($komputers, $columns), "{$filename}.xlsx");
    }

    /**
     * Helper method to export to CSV
     */
    private function exportToCSV($komputers, $columns, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];
        
        $callback = function() use ($komputers, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add header row
            $headerRow = $this->getHeaderRow($columns);
            fputcsv($file, $headerRow);
            
            // Add data rows
            foreach ($komputers as $komputer) {
                $row = $this->formatDataRow($komputer, $columns);
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper method to export to PDF
     */
    private function exportToPDF($komputers, $columns, $filename)
    {
        // This requires a PDF package like dompdf
        // composer require barryvdh/laravel-dompdf
        
        $headerRow = $this->getHeaderRow($columns);
        $data = [];
        
        foreach ($komputers as $komputer) {
            $data[] = $this->formatDataRow($komputer, $columns);
        }
        
        $pdf = FacadePdf::loadView('admin.komputer.export_pdf', [
            'headerRow' => $headerRow,
            'data' => $data,
            'title' => 'Data Komputer ESDM'
        ]);
        
        return $pdf->download("{$filename}.pdf");
    }

    /**
     * Helper to get header row based on selected columns
     */
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

    /**
     * Helper to format data row based on selected columns
     */
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
