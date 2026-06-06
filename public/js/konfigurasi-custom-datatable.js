const konfigurasiPengaturanDataAngkatan = {
    "columnDefs": [
        { "targets": [0,1,2,3,4], "orderable": false }
        ],
    "columns": [
        { "width": "5%" },
        { "width": "25%", className: "text-center"},
        { "width": "25%", className: "text-center"},
        { "width": "30%", className: "text-center"},
        { "width": "15%", className: "text-center kolom-action" }
    ]
};

const konfigurasiPengaturanDataAlumni = {
    "columnDefs": [
        { "targets": [0,1, 6], "orderable": false } // Kolom # dan Aksi tidak bisa disortir
    ],
    "columns": [
        { "width": "5%" , className: "text-center"},
        { "width": "10%", className: "text-center"},
        { "width": "25%"},
        { "width": "20%"},
        { "width": "15%", className: "text-center" },
        { "width": "15%", className: "text-center" },
        { "width": "10%", className: "text-center kolom-action" }
    ]
};

const konfigurasiPengaturanDataPengelola = {
    "columnDefs": [ { "targets": [0, 4], "orderable": false } ],
    "columns": [
        { "width": "5%" }, { "width": "30%" }, { "width": "25%" },
        { "width": "25%" }, { "width": "15%", className: "text-center kolom-action" }
    ]
};

const konfigurasiDirektoriAlumni = {
    "columnDefs": [
        { "targets": [0,1, 5], "orderable": false } 
    ],
    "columns": [
        { "width": "5%" , className: "text-center"},
        { "width": "10%"},
        { "width": "25%"},
        { "width": "35%", className: "text-center" },
        { "width": "15%", className: "text-center" },
        { "width": "10%", className: "text-center kolom-action" }
    ]
};