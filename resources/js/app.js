// resources/js/app.js

// Core Libraries (Order is Crucial)
// import jQuery from 'jquery'; // Import jQuery sebagai modul
// window.$ = window.jQuery = jQuery; // <<< PENTING: Ekspor jQuery secara global ke window

// import "jquery-ui";

// Bootstrap (depends on jQuery)
// import "bootstrap";

// Supporting Libraries
import "moment/moment.js"; // Moment.js harus ada jika ada plugin yang membutuhkannya

// AdminLTE Core JS
import "admin-lte/dist/js/adminlte.js";
// import 'admin-lte/dist/js/demo.js'; // Biasanya hanya untuk demo, bisa dihapus jika tidak perlu
import "admin-lte/dist/js/pages/dashboard.js"; // Jika menggunakan halaman dashboard AdminLTE

// Your custom JavaScript code or plugin initializations
$(document).ready(function () {
    // console.log('AdminLTE core assets loaded successfully!');
    // Tambahkan inisialisasi JS Anda di sini
    // jQuery UI conflict resolution (from AdminLTE template)
    // Pastikan Anda menempatkan ini setelah import 'jquery' dan 'jquery-ui'
    $.widget.bridge("uibutton", $.ui.button);
});
