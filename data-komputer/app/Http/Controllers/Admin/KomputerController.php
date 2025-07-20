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
        return view('admin.komputer.tambah');
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
            $barcode = $this->komputerStore->generateQRCode($validated['nomor_aset']);
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
    public function show(string $nomor_aset)
    {
        return view('admin.komputer.detail', [
            'komputer' => $this->komputerGetData->getByNomorAset($nomor_aset),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $nomor_aset)
    {
        return view('admin.komputer.edit', [
            'komputer' => $this->komputerGetData->getByNomorAset($nomor_aset),
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
}
