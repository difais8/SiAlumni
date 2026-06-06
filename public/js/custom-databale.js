function inisialisasiTabel(selector, konfigurasi) {
    // Menggabungkan konfigurasi default dengan konfigurasi spesifik
    const pengaturanDefault = {
        "autoWidth": false,
        "order": [],
    };

    const pengaturanAkhir = Object.assign({}, pengaturanDefault, konfigurasi);

    // Inisialisasi DataTable dan simpan instance-nya
    const tabel = new DataTable(selector, pengaturanAkhir);

    // --- LOGIKA PENOMORAN OTOMATIS ---
    // Tambahkan event listener untuk penomoran ulang setiap kali tabel diurutkan atau dicari
    tabel.on('order.dt search.dt', function () {
        let i = 1;
        // Pilih semua sel di kolom pertama (indeks 0) yang sedang ditampilkan
        tabel.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            // Isi sel tersebut dengan nomor urut yang benar
            this.data(i++);
        });
    }).draw(); // .draw() untuk memicu penomoran saat pertama kali dimuat
}