@extends('admin.components.layout')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="border-bottom pb-2">
                    <i class="bi bi-plus-circle-fill text-primary"></i> Tambah Data Perangkat Komputer
                </h2>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Also check for validation errors -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Error!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="formTambahPerangkat" action="{{ route('komputer.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="card-title mb-3">Data Identifikasi Perangkat</h4>
                            <div class="mb-3">
                                <label for="lokasi_penempatan" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi_penempatan') is-invalid @enderror" id="lokasi_penempatan" name="lokasi_penempatan" value="{{ old('lokasi_penempatan') }}" placeholder="Contoh: Ruang Rapat Lt.1" required>
                                @error('lokasi_penempatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Nama ruangan wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nomor_aset" class="form-label">Nomor Aset <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nomor_aset') is-invalid @enderror" id="nomor_aset" name="nomor_aset" value="{{ old('nomor_aset') }}" placeholder="Contoh: ESDM-PC-001" required>
                                @error('nomor_aset')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Nomor aset wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_komputer" class="form-label">Nomor Komputer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_komputer') is-invalid @enderror" id="nama_komputer" name="nama_komputer" value="{{ old('nama_komputer') }}" placeholder="Contoh: PC-ADMIN-01" required>
                                @error('nama_komputer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Nomor komputer wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="merek_komputer" class="form-label">Merek Komputer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merek_komputer') is-invalid @enderror" id="merek_komputer" name="merek_komputer" value="{{ old('merek_komputer') }}" placeholder="" required>
                                @error('merek_komputer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @else
                                    <div class="invalid-feedback">
                                        Merek komputer wajib diisi
                                    </div>
                                    @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tahun_pengadaan" class="form-label">Tahun Pengadaan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun_pengadaan') is-invalid @enderror" id="tahun_pengadaan" name="tahun_pengadaan" min="2000" max="{{ date('Y') }}" value="{{ old('tahun_pengadaan', date('Y')) }}" required>
                                @error('tahun_pengadaan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Tahun pengadaan wajib diisi dengan format yang benar
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_pengguna_sekarang" class="form-label">Nama Pengguna Sekarang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_pengguna_sekarang') is-invalid @enderror" id="nama_pengguna_sekarang" name="nama_pengguna_sekarang" value="{{ old('nama_pengguna_sekarang') }}" placeholder="Contoh: Nama Pegawai" required>
                                @error('nama_pengguna_sekarang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Nama pengguna wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="penggunaan_sekarang" class="form-label">Penggunaan Sekarang</label>
                                <textarea class="form-control @error('penggunaan_sekarang') is-invalid @enderror" id="penggunaan_sekarang" name="penggunaan_sekarang" rows="3">{{ old('penggunaan_sekarang') }}</textarea>
                                @error('penggunaan_sekarang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4 class="card-title mb-3">Spesifikasi Perangkat</h4>

                            <div class="mb-3">
                                <label for="spesifikasi_processor" class="form-label">Processor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('spesifikasi_processor') is-invalid @enderror" id="spesifikasi_processor" name="spesifikasi_processor" placeholder="Contoh: Intel Core i5-10400 2.9GHz" value="{{ old('spesifikasi_processor') }}" required>
                                @error('spesifikasi_processor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Spesifikasi processor wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="spesifikasi_ram" class="form-label">RAM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('spesifikasi_ram') is-invalid @enderror" id="spesifikasi_ram" name="spesifikasi_ram" placeholder="Contoh: 8GB DDR4 2666MHz" value="{{ old('spesifikasi_ram') }}" required>
                                @error('spesifikasi_ram')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Spesifikasi RAM wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="spesifikasi_vga" class="form-label">VGA</label>
                                <input type="text" class="form-control @error('spesifikasi_vga') is-invalid @enderror" id="spesifikasi_vga" name="spesifikasi_vga" placeholder="Contoh: NVIDIA GeForce GTX 1650 4GB" value="{{ old('spesifikasi_vga') }}">
                                @error('spesifikasi_vga')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="spesifikasi_penyimpanan" class="form-label">Penyimpanan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('spesifikasi_penyimpanan') is-invalid @enderror" id="spesifikasi_penyimpanan" name="spesifikasi_penyimpanan" placeholder="Contoh: SSD 256GB + HDD 1TB" value="{{ old('spesifikasi_penyimpanan') }}" required>
                                @error('spesifikasi_penyimpanan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Informasi penyimpanan wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sistem_operasi" class="form-label">Sistem Operasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sistem_operasi') is-invalid @enderror" id="sistem_operasi" name="sistem_operasi" placeholder="Contoh: Windows 11 Pro" value="{{ old('sistem_operasi') }}" required>
                                @error('sistem_operasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Sistem operasi wajib diisi
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kesesuaian_pc" class="form-label">Kesesuaian PC dalam Mendukung Pekerjaan</label>
                                <select class="form-select @error('kesesuaian_pc') is-invalid @enderror" id="kesesuaian_pc" name="kesesuaian_pc">
                                    <option value="Sangat Sesuai" {{ old('kesesuaian_pc') == 'Sangat Sesuai' ? 'selected' : '' }}>Sangat Sesuai</option>
                                    <option value="Sesuai" {{ old('kesesuaian_pc') == 'Sesuai' ? 'selected' : 'selected' }}>Sesuai</option>
                                    <option value="Kurang Sesuai" {{ old('kesesuaian_pc') == 'Kurang Sesuai' ? 'selected' : '' }}>Kurang Sesuai</option>
                                    <option value="Tidak Sesuai" {{ old('kesesuaian_pc') == 'Tidak Sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                                </select>
                                @error('kesesuaian_pc')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="card-title mb-3">Kondisi dan Pemeliharaan</h4>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisi_komputer" class="form-label">Keterangan Kondisi Komputer Saat Ini <span class="text-danger">*</span></label>
                                <select class="form-select @error('kondisi_komputer') is-invalid @enderror" id="kondisi_komputer" name="kondisi_komputer" required>
                                    <option value="" selected disabled>Pilih kondisi</option>
                                    <option value="Sangat Baik" {{ old('kondisi_komputer') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                    <option value="Baik" {{ old('kondisi_komputer') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Cukup" {{ old('kondisi_komputer') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                    <option value="Kurang" {{ old('kondisi_komputer') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                    <option value="Rusak" {{ old('kondisi_komputer') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                @error('kondisi_komputer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Kondisi komputer wajib dipilih
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan_kondisi" class="form-label">Detail Kondisi</label>
                                <textarea class="form-control @error('keterangan_kondisi') is-invalid @enderror" id="keterangan_kondisi" name="keterangan_kondisi" rows="3" placeholder="Berikan detail kondisi perangkat saat ini...">{{ old('keterangan_kondisi') }}</textarea>
                                @error('keterangan_kondisi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="histori_pemeliharaan" class="form-label">Histori Pemeliharaan</label>
                                <textarea class="form-control @error('histori_pemeliharaan') is-invalid @enderror" id="histori_pemeliharaan" name="histori_pemeliharaan" rows="6" placeholder="Contoh: 12/05/2023 - Penggantian RAM dari 4GB ke 8GB">{{ old('histori_pemeliharaan') }}</textarea>
                                @error('histori_pemeliharaan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Media dan Dokumentasi Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="card-title mb-3">Media dan Dokumentasi</h4>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="fotoInput" class="form-label">Foto Perangkat <span class="text-danger">*</span></label>

                                <!-- Upload Area -->
                                <div class="upload-area mb-3">
                                    <div class="upload-container text-center p-4 border rounded-3 bg-light position-relative">
                                        <!-- Changed the ID to prevent conflicts and added onclick directly -->
                                        <input class="form-control @error('foto') is-invalid @enderror @error('foto.*') is-invalid @enderror"
                                            type="file" id="fotoInput" name="foto[]" accept="image/*" multiple>

                                        <div class="upload-icon mb-3">
                                            <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>

                                        <h5>Unggah Foto Perangkat</h5>
                                        <p class="text-muted mb-3">Pilih atau seret file ke area ini</p>

                                        <!-- Added onclick directly to the button as a backup -->
                                        <button type="button" class="btn btn-primary px-4 py-2" id="selectFiles" onclick="document.getElementById('fotoInput').click();">
                                            <i class="bi bi-image me-2"></i> Pilih Foto
                                        </button>

                                        <div class="mt-2 small text-muted">
                                            Format: JPG, PNG, JPEG (Maks. 5MB per foto)
                                        </div>
                                    </div>
                                </div>

                                @error('foto')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('foto.*')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <!-- Preview Area -->
                                <div id="preview-area" class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><i class="bi bi-images me-2"></i> Preview Foto</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addMoreImages" style="display: none;">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Foto Lagi
                                        </button>
                                    </div>

                                    <div class="image-preview-container">
                                        <div class="row" id="previewContainer">
                                            <div class="col-12 text-center p-3 text-muted empty-preview">
                                                <p><i class="bi bi-image me-2"></i>Belum ada foto yang dipilih</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Status -->
                                <div id="upload-status" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 text-primary"><i class="bi bi-info-circle me-2"></i>Status Unggahan</h6>
                                                    <p class="mb-0 small" id="fileCount">0 file dipilih</p>
                                                    <p class="mb-0 small" id="totalSize">Total: 0 KB</p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearAllImages">
                                                    <i class="bi bi-trash me-1"></i> Hapus Semua
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <hr>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-save"></i> Simpan Data Perangkat
                            </button>
                            <a href="{{ route('komputer.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Form validation with Bootstrap
        (function() {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Image upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Elements - Changed fileInput to match new ID
            const fileInput = document.getElementById('fotoInput');
            const selectFilesBtn = document.getElementById('selectFiles');
            const addMoreImagesBtn = document.getElementById('addMoreImages');
            const clearAllImagesBtn = document.getElementById('clearAllImages');
            const previewContainer = document.getElementById('previewContainer');
            const uploadStatus = document.getElementById('upload-status');
            const fileCountElement = document.getElementById('fileCount');
            const totalSizeElement = document.getElementById('totalSize');
            const uploadContainer = document.querySelector('.upload-container');
            const form = document.getElementById('formTambahPerangkat');

            // Check if elements exist and log to console
            console.log('File input found:', !!fileInput);
            console.log('Select files button found:', !!selectFilesBtn);

            // Remove hiding the file input with JavaScript - we'll use CSS only
            if (fileInput) {
                // Make input visually hidden but still accessible to JavaScript
                fileInput.className += ' visually-hidden';
            } else {
                console.error('File input element not found!');
            }

            // Click handlers for buttons - with additional error handling
            if (selectFilesBtn) {
                selectFilesBtn.addEventListener('click', function(e) {
                    console.log('Select files button clicked');
                    if (fileInput) {
                        fileInput.click();
                    } else {
                        console.error('Cannot click file input - not found');
                    }
                });
            } else {
                console.error('Select files button not found!');
            }

            if (addMoreImagesBtn) {
                addMoreImagesBtn.addEventListener('click', function() {
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            }

            if (clearAllImagesBtn) {
                clearAllImagesBtn.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin menghapus semua foto?')) {
                        selectedFiles = [];
                        updateFileInput();
                        updatePreview();
                        updateUploadStatus();
                    }
                });
            }

            // Store files directly on the file input
            let selectedFiles = [];

            // File input change handler
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    console.log('File input change event fired', this.files.length, 'files selected');
                    const files = Array.from(this.files);

                    if (files.length > 0) {
                        processFiles(files);
                    }
                });
            }

            // Make the upload container work with drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                uploadContainer.classList.add('highlight');
            }

            function unhighlight() {
                uploadContainer.classList.remove('highlight');
            }

            uploadContainer.addEventListener('drop', function(e) {
                const droppedFiles = Array.from(e.dataTransfer.files);
                processFiles(droppedFiles);
            }, false);

            // Process files (validation and adding to collection)
            function processFiles(files) {
                const validFiles = [];
                const maxSize = 5 * 1024 * 1024; // 5MB

                files.forEach(file => {
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        showAlert(`File "${file.name}" bukan file gambar yang didukung.`, 'warning');
                        return;
                    }

                    // Validate file size
                    if (file.size > maxSize) {
                        showAlert(`File "${file.name}" terlalu besar. Maksimal 5MB.`, 'warning');
                        return;
                    }

                    // Check for duplicates
                    const isDuplicate = selectedFiles.some(existing =>
                        existing.name === file.name && existing.size === file.size
                    );

                    if (isDuplicate) {
                        showAlert(`File "${file.name}" sudah dipilih.`, 'info');
                        return;
                    }

                    validFiles.push(file);
                });

                if (validFiles.length > 0) {
                    selectedFiles = [...selectedFiles, ...validFiles];
                    updateFileInput();
                    updatePreview();
                    updateUploadStatus();

                    // Show the add more button once we have files
                    addMoreImagesBtn.style.display = 'block';
                }
            }

            // Update the file input with selected files
            function updateFileInput() {
                if (!fileInput) {
                    console.error('Cannot update file input - not found');
                    return;
                }

                try {
                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();

                    // Add our files to it
                    selectedFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    // Set the files property of our file input
                    fileInput.files = dataTransfer.files;

                    // Log to console for debugging
                    console.log(`File input updated with ${fileInput.files.length} files`);
                } catch (error) {
                    console.error('Error updating file input:', error);
                }
            }

            // Update preview area - FIXED FUNCTION
            function updatePreview() {
                if (!previewContainer) {
                    console.error('Preview container not found');
                    return;
                }

                previewContainer.innerHTML = '';

                if (selectedFiles.length === 0) {
                    previewContainer.innerHTML = `
                        <div class="col-12 text-center p-3 text-muted empty-preview">
                            <p><i class="bi bi-image me-2"></i>Belum ada foto yang dipilih</p>
                        </div>
                    `;
                    if (addMoreImagesBtn) {
                        addMoreImagesBtn.style.display = 'none';
                    }
                    return;
                }

                // For each file, create a preview image
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6 mb-3';

                        col.innerHTML = `
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="card-img-top preview-img" alt="${file.name}">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle btn-remove"
                                        data-index="${index}" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <div class="card-body p-2">
                                    <p class="card-text mb-1 text-truncate small fw-semibold" title="${file.name}">${file.name}</p>
                                    <p class="card-text text-muted small">${formatFileSize(file.size)}</p>
                                </div>
                            </div>
                        `;

                        previewContainer.appendChild(col);

                        // Add event listener to remove button
                        const removeBtn = col.querySelector('.btn-remove');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                removeFile(parseInt(this.dataset.index));
                            });
                        }
                    };

                    // Start reading the file - this triggers the onload event when done
                    reader.readAsDataURL(file);
                });
            }

            // Update upload status information
            function updateUploadStatus() {
                if (selectedFiles.length === 0) {
                    uploadStatus.style.display = 'none';
                    return;
                }

                uploadStatus.style.display = 'block';

                let totalSize = 0;
                selectedFiles.forEach(file => {
                    totalSize += file.size;
                });

                fileCountElement.textContent = `${selectedFiles.length} file dipilih`;
                totalSizeElement.textContent = `Total: ${formatFileSize(totalSize)}`;
            }

            // Remove a file at specific index
            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                updatePreview();
                updateUploadStatus();
            }

            // Format file size for display
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Show alert message
            function showAlert(message, type = 'info') {
                const alertContainer = document.createElement('div');
                alertContainer.className = `alert alert-${type} alert-dismissible fade show mt-2`;
                alertContainer.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            const uploadArea = document.querySelector('.upload-area');
            uploadArea.after(alertContainer);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                alertContainer.classList.remove('show');
                setTimeout(() => alertContainer.remove(), 150);
            }, 5000);
        }

        // Ensure the files are properly submitted with the form
        if (form) {
            form.addEventListener('submit', function(e) {
                // If no files are selected and they're required, show an alert
                if (selectedFiles.length === 0) {
                    showAlert('Anda harus memilih setidaknya satu foto', 'danger');
                    e.preventDefault();
                    return false;
                }

                // Double-check that the files are in the file input
                if (fileInput.files.length === 0 && selectedFiles.length > 0) {
                    console.log('Attempting to fix file input before submit');
                    updateFileInput();

                    // If still empty, something's wrong
                    if (fileInput.files.length === 0) {
                        showAlert('Terjadi kesalahan saat mempersiapkan file untuk diunggah', 'danger');
                        e.preventDefault();
                        return false;
                    }
                }

                // Log success
                console.log(`Form submitted with ${fileInput.files.length} files`);
                return true;
            });
        }
    });
</script>

    <style>
        /* Upload area styling */
        .upload-container {
            border: 2px dashed #ccc !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* Hide the file input but keep it accessible to JavaScript */
        #fotoInput {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Make the upload container clearly indicate it's clickable */
        .upload-container:hover {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .upload-container.highlight {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.1);
        }

        /* Preview area styling */
        .image-preview-container {
            min-height: 100px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .preview-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: calc(0.375rem - 1px);
            border-top-right-radius: calc(0.375rem - 1px);
        }

        /* Hover effects */
        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-remove {
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }

        .card:hover .btn-remove {
            opacity: 1;
        }
    </style>
@endsection
