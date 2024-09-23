jQuery(function($){

    function load_porto(data){
        if (!data) {
            console.log("Error: Data WSS is empty");
            return;
        }

        var table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
        table += '<thead>';
            table += '<tr>';
                table += `<th>No</th>`;
                table += `<th>Judul</th>`;
                table += `<th>Jenis</th>`;
                table += `<th>Screenshot</th>`;
                table += `<th>Modif</th>`;
                table += `<th>Preview</th>`;
                // table += `<th></th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        var jsonArray = JSON.parse(data);
        jsonArray.forEach((item, index) => {
            table += '<tr>';
                table += `<td>${index}</td>`;
                table += `<td>${item.title}</td>`;
                table += `<td>${item.jenis}</td>`;
                table += `<td>${item.screenshot}</td>`;
                table += `<td>${item.last_modified}</td>`;
                table += `<td>${item.url_live_preview}</td>`;
                // table += `<td>${item.content}</td>`;
            table += '</tr>';
        });

        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);

    }

    //proses import jenis
    function load_jenis(data){
        
        if (!data) {
            console.log("Error: Data WSS is empty");
            return;
        }

        var table = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
        table += '<thead>';
            table += '<tr>';
                table += `<th>No</th>`;
                table += `<th>Kategori</th>`;
                table += `<th>Slug</th>`;
                table += `<th></th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        var jsonArray = JSON.parse(data);
        jsonArray.forEach((item, index) => {
            table += '<tr>';
                table += `<td>${index}</td>`;
                table += `<td>${item.category}</td>`;
                table += `<td>${item.slug}</td>`;
            table += '</tr>';
        });

        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);

    }

    jQuery(document).ready(function($) {
        $('#wss-import-portofolio').on('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form default
    
            var item = $('#item-portofolio').val(); // Mengambil semua data dari form
    
            $('.wss-result-ajax').html('Loading...');
            $.ajax({
                url: ajaxurl, 
                type: 'POST',
                data: {
                    action: 'wss_portofolio_import', // Action yang akan dipanggil
                    item: item
                },
                success: function(response) {
                    // Tindak lanjut setelah berhasil
                    if(item == 'jenis-web'){
                        load_jenis(response.data);
                    } else {
                        load_porto(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    // Tindak lanjut jika terjadi kesalahan
                    $('#wss-import-portofolio #submit').html('Ambil Data');
                    console.error('Error:', error);
                }
            });
        });
    });
});