<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komputer;
use App\Models\RiwayatPerbaikanKomputer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Service\Komputer\KomputerGetData;
use App\Service\Komputer\KomputerStore;
use App\Service\Komputer\KomputerUpdate;
use App\Service\Komputer\RiwayatPerbaikan;

class RiwayatPerbaikanKomputerController extends Controller
{

    private $komputerGetData;
    private $komputerStore;
    private $komputerUpdate;
    private $riwayatPerbaikan;

    public function __construct(
        KomputerGetData $komputerGetData, 
        KomputerStore $komputerStore, 
        KomputerUpdate $komputerUpdate,
        RiwayatPerbaikan $riwayatPerbaikan
    )
    {
        $this->komputerGetData = $komputerGetData;
        $this->komputerStore = $komputerStore;
        $this->komputerUpdate = $komputerUpdate;
        $this->riwayatPerbaikan = $riwayatPerbaikan;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $kode_barang)
    {
        // Get the computer
        $komputer = Komputer::where('kode_barang', $kode_barang)->firstOrFail();

        // Build query with filters
        $query = RiwayatPerbaikanKomputer::where('asset_id', $komputer->id);
        
        // Apply search if keyword is provided
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('jenis_maintenance', 'like', "%{$keyword}%")
                  ->orWhere('teknisi', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }
        
        // Apply filter by maintenance type if provided
        if ($request->filled('jenis')) {
            $query->where('jenis_maintenance', $request->input('jenis'));
        }
        
        // Get data with pagination
        $riwayat = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get unique maintenance types for filter dropdown
        $jenis_maintenance = RiwayatPerbaikanKomputer::where('asset_id', $komputer->id)
            ->select('jenis_maintenance')
            ->distinct()
            ->pluck('jenis_maintenance');
        
        return view('admin.riwayat_perbaikan.riwayat', [
            'riwayat' => $riwayat,
            'komputer' => $komputer,
            'jenis_maintenance' => $jenis_maintenance,
            'ruangans' => $this->komputerGetData->getUniqueRuangan()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id_komputer)
    {
        
        DB::beginTransaction();
        try {
            // Create new maintenance record
            $validated = $this->riwayatPerbaikan->validationStore($request);

            $this->riwayatPerbaikan->store($validated, $id_komputer);
            
            DB::commit();
            return redirect()
                ->route('komputer.riwayat.index', $request->get('kode_barang'))
                ->with('success', 'Riwayat perbaikan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan riwayat perbaikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_barang, $id_riwayat)
    {
        DB::beginTransaction();
        try {
            
            $validated = $this->riwayatPerbaikan->validationUpdate($request);
            
            $this->riwayatPerbaikan->update($validated, $id_riwayat);

            DB::commit();
            return redirect()
                ->route('komputer.riwayat.index', $request->get('kode_barang'))
                ->with('success', 'Riwayat perbaikan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui riwayat perbaikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_barang, $riwayat_id)
    {
        try {
            // Find and delete the maintenance record
            $riwayat = RiwayatPerbaikanKomputer::findOrFail($riwayat_id);
            $riwayat->delete();
            
            return redirect()
                ->route('riwayat-perbaikan.index', $kode_barang)
                ->with('success', 'Riwayat perbaikan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus riwayat perbaikan: ' . $e->getMessage());
        }
    }

    /**
     * Export maintenance history
     */
    public function export(Request $request, $kode_barang)
    {
        // Implement export functionality similar to KomputerController::export
        // ...

        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
