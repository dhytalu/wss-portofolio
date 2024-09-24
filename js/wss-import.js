jQuery(function($){

    function run_compare(jsonData){        

        // Loop melalui setiap baris tabel
        $('#tabledata tbody tr').each(function(index, row) {

            // Ambil nilai dari setiap td berdasarkan kelas
            var values = {};
            $(row).find('td').each(function() {
                var key = $(this).attr('class'); // Dapatkan kelas sebagai key
                var value = $(this).text(); // Dapatkan teks sebagai value
                if(key){
                    values[key] = value; // Simpan dalam objek
                }
            });

            // Bandingkan dengan data JSON
            var found = jsonData.some(function(item) {
                return Object.keys(values).every(function(key) {
                    return item[key] == values[key]; // Bandingkan setiap key
                });
            });

            // Tindakan jika data ditemukan
            if (found) {
                $(row).find('td:first-child input').attr('checked', false);
                $(row).find('td:last-child').text('Tersedia');
            } else {
                $(row).find('td:first-child input').attr('checked', true);
                $(row).find('td:last-child').text('Tidak Tersedia');
                $(row).css('background-color', '#e3fde9'); // Tandai dengan warna hijau
            }

        });

        //prepend
        $('.wss-result-ajax').prepend('<button id="import-data" class="button button-primary">Import Data</button><br>');
    }
    
    //proses import jenis
    function load_jenis(data,compare){
        
        if (!data) {
            console.log("Error: Data WSS is empty");
            return;
        }

        var table = '<table id="tabledata" class="wp-list-table widefat fixed striped table-view-list posts">';
        table += '<thead>';
            table += '<tr>';
                table += `<th></th>`;
                table += `<th>No</th>`;
                table += `<th>Kategori</th>`;
                table += `<th>Slug</th>`;
                table += `<th></th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        data.forEach((item, index) => {
            table += `<tr>`;
                table += `<td><input type="checkbox" name="data[]" value='${JSON.stringify(item)}'></td>`;
                table += `<td>${index+1}</td>`;
                table += `<td class="category">${item.category}</td>`;
                table += `<td class="slug">${item.slug}</td>`;
                table += `<td></td>`;
            table += '</tr>';
        });
        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);

        run_compare(compare);
    }
    
    function load_porto(data){
        if (!data) {
            console.log("Error: Data WSS is empty");
            return;
        }

        var table = '<table id="tabledata" class="wp-list-table widefat fixed striped table-view-list posts">';
        table += '<thead>';
            table += '<tr>';
                table += `<th>No</th>`;
                table += `<th>Judul</th>`;
                table += `<th>Jenis</th>`;
                table += `<th>Screenshot</th>`;
                table += `<th>Modif</th>`;
                table += `<th>Preview</th>`;
                table += `<th></th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        data.forEach((item, index) => {
            table += '<tr>';
                table += `<td>${index}</td>`;
                table += `<td>${item.title}</td>`;
                table += `<td>${item.jenis}</td>`;
                table += `<td>${item.screenshot}</td>`;
                table += `<td>${item.last_modified}</td>`;
                table += `<td>${item.url_live_preview}</td>`;
                // table += `<td>${item.content}</td>`;
                table += `<td></td>`;
            table += '</tr>';
        });

        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);

        run_compare(compare);
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
                        load_jenis(response.data,response.compare);
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