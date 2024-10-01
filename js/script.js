jQuery(function($){
    $(document).ready(function() {
        // Event listener hanya untuk elemen dengan class .wportos-button-preview
        $(document).on('click','.wportos-button-preview', function() {
            
            console.log('click-wportos');
            // Menggunakan setTimeout untuk memastikan ThickBox sudah terbuka
            setTimeout(function() {
                // Menambahkan class baru ke elemen modal
                $('#TB_window').addClass('wportos-modal-preview');
                console.log('wportos');
            }, 200); // Sesuaikan delay jika perlu

        });
    });
});