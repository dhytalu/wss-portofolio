jQuery(function($){
    
    //proses import jenis
    function load_jenis(data){
        
        if (!data) {
            console.log("Error: Data WSS is empty");
            return;
        }

        var table = '<table id="tabledata" class="wp-list-table widefat fixed striped table-view-list posts">';
        table += '<thead>';
            table += '<tr>';
                table += `<th>No</th>`;
                table += `<th>Kategori</th>`;
                table += `<th>Slug</th>`;
                table += `<th>Status</th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        data.forEach((item, index) => {
            var check = item.exist == false?'checked':'';
            table += `<tr>`;
                table += `<td>${index+1}</td>`;
                table += `<td>${item.category}</td>`;
                table += `<td>${item.slug}</td>`;
                table += `<td>${item.status}</td>`;
            table += '</tr>';
        });
        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);
    }

    function processImportPost(articles) {
        let index = 0;
        $('.wss-result-ajax').prepend('<p class="prosesimport">Proses import portofolio...</p>'); 

        function sendArticle() {
            if (index < articles.length) {
                const artikelId = articles[index].id;
                
                $('.porto-'+artikelId+' .status').html('<span style="color:blue;">Memproses..</span>');
                $.ajax({
                    url: ajaxurl, 
                    type: 'POST',
                    data: {
                        action: 'wss_portofolio_importproses', // Action yang akan dipanggil
                        item: articles[index]
                    },
                    success: function(response) {
                        $('.porto-'+artikelId+' .status').html('<span style="color:green;">'+response.status+'</span>');
                                               
                        index++;
                        sendArticle(); // Kirim artikel berikutnya
                    },
                    error: function(xhr, status, error) {
                        $('.porto-'+artikelId+' .status').html('<span style="color:red;">'+error+'</span>');
                        console.error('Error processing input:', error);                        
                        index++; // Tetap lanjut meskipun terjadi error
                        sendArticle(); // Kirim artikel berikutnya
                    }
                });
                
            } else {
                console.log('Semua portofolio telah diproses.');
                $('.wss-result-ajax .prosesimport').html('Semua portofolio telah diproses');             
            }
        }

        // Mulai proses
        sendArticle();
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
                table += `<th>Last Modif</th>`;
                table += `<th>Status</th>`;
                table += `<th></th>`;
            table += '</tr>';
        table += '</thead>';

        table += '</tbody>';
        data.forEach((item, index) => {
            table += `<tr class="porto-${item.id}">`;
                table += `<td>${index+1}</td>`;
                table += `<td>${item.title}</td>`;
                table += `<td>${item.jenis}</td>`;
                table += `<td>${item.last_modified}</td>`;
                table += `<td class="status">${item.status}</td>`;
                table += `<td><a href="#TB_inline?&width=600&height=550&inlineId=wss-content-${index}" class="thickbox button">Detail</a></td>`;
            table += '</tr>';
        });

        table += '</tbody></table>';
        $('.wss-result-ajax').html(table);

        //append modal content
        data.forEach((item, index) => {
            var content = `<div id="wss-content-${index}" style="display:none;">`;
            content += '<table><tbody>';
                content += '</tr>';
                    content += `<td>Screenshot</td>`;
                    content += `<td><img src="${item.screenshot}"/></td>`;
                content += '</tr>';
                content += '</tr>';
                    content += `<td>Preview</td>`;
                    content += `<td><a href="${item.url_live_preview}" target="_blank" class="button">lihat</a></td>`;
                content += '</tr>';
                content += '</tr>';
                    content += `<td>Content</td>`;
                    content += `<td>${item.content}</td>`;
                content += '</tr>';
            content += '</tbody></table>';
            content += '</div>';
            $('.wss-result-ajax').append(content);

        });

        //jalankan proses import
        processImportPost(data);
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