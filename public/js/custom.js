/**
 * Menginisialisasi konfirmasi SweetAlert untuk tombol hapus di dalam tabel.
 * @param {string} selectorTabel - Selector ID dari <tbody> tabel (Contoh: '#tabel-data-angkatan tbody')
 * @param {string} prefixForm - ID prefix dari form (Contoh: 'form-hapus')
 * @param {string} pesan - Pesan konfirmasi yang akan ditampilkan
 */
function inisialisasiTombolHapus(selectorTabel, prefixForm, pesan) {
    
    $(selectorTabel).on('click', '.btn-hapus-swal', function (e) {
        e.preventDefault(); 
        var entityId = $(this).data('id');
        
        var form = $('#' + prefixForm + '-' + entityId);

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: pesan, 
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // 4. Jika user mengonfirmasi...
            if (result.isConfirmed) {
                // ...submit form yang sesuai
                form.submit();
            }
        });
    });
}


function inisialisasiTombolHapusV2() {
    $(document).on('submit', '.delete-form', function(event) {
        // Hentikan submit untuk menampilkan konfirmasi
        event.preventDefault();
        const form = this; // 'this' adalah form yang memicu event

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
            icon: 'my-swal-icon'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, submit form secara normal
                form.submit();
            }
        });
    });
}

/* * IMAGE PREVIEW / LIGHTBOX 
 * Cara pakai: Tambahkan class "img-clickable" pada tag <img>
 */
$(document).ready(function() {
    $(document).on('click', '.img-clickable', function() {
        // 1. Ambil URL gambar dari src
        var src = $(this).attr('src');

        // 2. Masukkan ke dalam modal
        $('#previewImage').attr('src', src);

        // 3. Tampilkan modal
        $('#imagePreviewModal').modal('show');
    });
});