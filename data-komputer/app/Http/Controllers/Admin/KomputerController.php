<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komputer;
use App\Service\Komputer\KomputerGetData;
use App\Service\Komputer\KomputerStore;
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

    public function __construct(KomputerGetData $komputerGetData, KomputerStore $komputerStore)
    {
        $this->komputerGetData = $komputerGetData;
        $this->komputerStore = $komputerStore;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
