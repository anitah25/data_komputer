// Script untuk Sistem Informasi Komputer ESDM
document.addEventListener('DOMContentLoaded', function () {
    // Form validation
    const form = document.getElementById('formTambahPerangkat');
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.preventDefault();
                // Simulasi penyimpanan data
                saveFormData();
            }
            
            form.classList.add('was-validated');
        });
    }
    
    // Preview image
    const imageInput = document.getElementById('fotoPerangkat');
    const previewImage = document.getElementById('previewImage');
    
    if (imageInput && previewImage) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Generate barcode
    const generateBarcodeBtn = document.getElementById('generateBarcode');
    const nomorAsetInput = document.getElementById('nomorAset');
    
    if (generateBarcodeBtn && nomorAsetInput) {
        generateBarcodeBtn.addEventListener('click', function() {
            const nomorAset = nomorAsetInput.value.trim();
            if (nomorAset) {
                generateBarcode(nomorAset);
            } else {
                alert('Masukkan Nomor Aset terlebih dahulu untuk membuat barcode');
                nomorAsetInput.focus();
            }
        });
    }

    // Year validation
    const tahunPengadaan = document.getElementById('tahunPengadaan');
    if (tahunPengadaan) {
        // Set default max year to current year
        const currentYear = new Date().getFullYear();
        tahunPengadaan.setAttribute('max', currentYear);
        
        // Set default value to current year
        tahunPengadaan.value = currentYear;
    }
});

// Function to generate barcode
function generateBarcode(text) {
    const barcodeContainer = document.getElementById('barcodeContainer');
    if (barcodeContainer) {
        // Clear previous barcode
        barcodeContainer.innerHTML = '';
        
        // Create canvas element for barcode
        const canvas = document.createElement('canvas');
        barcodeContainer.appendChild(canvas);
        
        // Generate barcode using JsBarcode
        JsBarcode(canvas, text, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 100,
            displayValue: true,
            fontSize: 18,
            margin: 10
        });
    }
}

// Function to simulate saving form data
function saveFormData() {
    // Get submit button and add animation class
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
    
    // Simulate API call with timeout
    setTimeout(() => {
        // Display success message
        const form = document.getElementById('formTambahPerangkat');
        form.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                <h2 class="mt-3">Data Berhasil Disimpan!</h2>
                <p class="lead">Data perangkat komputer telah berhasil ditambahkan ke sistem.</p>
                <div class="mt-4">
                    <a href="daftar-perangkat.html" class="btn btn-primary me-2">
                        <i class="bi bi-list-ul"></i> Lihat Daftar Perangkat
                    </a>
                    <a href="tambah-perangkat.html" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Perangkat Lain
                    </a>
                </div>
            </div>
        `;
    }, 2000);
}

// Function to handle tab navigation for form sections
function switchTab(tabId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => tab.classList.remove('active'));
    
    // Show selected tab content
    document.getElementById(tabId).classList.add('active');
    
    // Update tab navigation
    const tabLinks = document.querySelectorAll('.tab-link');
    tabLinks.forEach(link => link.classList.remove('active'));
    document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
}
