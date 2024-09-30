<?php
class Wss_Portofolio_Ajax_Import {

    private $access_key;
    private $api_url;
    private $item;
    
    public function __construct() {

        $options = get_option('wss_portofolio');
        $this->access_key = $options['access_key'];
        $this->api_url = 'https://my.websweetstudio.com/wp-json/wp/v2/';

        // Hook untuk menambahkan ajax
        add_action('wp_ajax_wss_portofolio_import', [$this, 'ajax']);
        add_action('wp_ajax_wss_portofolio_importproses', [$this, 'ajax_importproses']);
        add_action('wp_ajax_wss_portofolio_compare', [$this, 'ajax_portofolio']);
    }

    private function api($item){
        
        $api_url = $this->api_url.$item.'?access_key='. $this->access_key;
        // Make the request
        $response = wp_remote_get($api_url);

        // Check for errors
        if (is_wp_error($response)) {
            return 'Error: ' . $response->get_error_message();
        }

         // Get the response body
        $body = wp_remote_retrieve_body($response);

        // Decode the JSON response
        $data = json_decode($body, true);

        return $data; // Return or process the data as needed

    }
    
    public function kategori_portofolio($data){

        $return = [];
        
        $data_terms = [];
        $terms = get_terms([
            'taxonomy' => 'kategori-portofolio',
            'hide_empty' => false,
        ]);
        foreach ($terms as $term){
            $data_terms['slug'][] = $term->slug;
            $data_terms['category'][] = $term->name;
        }

        foreach ($data as $k => $d) {
            $status = 'Sudah Ada';
            $slug = $d['slug'];
            $category = $d['category'];
            if(!in_array($slug, $data_terms['slug']) && !in_array($category, $data_terms['category'])){
                wp_insert_term(
                    $category,   // the term 
                    'kategori-portofolio', // the taxonomy
                    array(
                        'description' => '',
                        'slug'        => $slug,
                    )
                );
                $status = 'Berhasil ditambahkan';
            }
            $return[$k] = [
                'slug' => $slug,
                'category' => $category,
                'status' => $status,
            ];
        }

        return $return;
    }

    public function check_portofolio_exist($judul,$id_portofolio_wss){
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT p.ID 
             FROM $wpdb->posts p
             JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
             WHERE p.post_title = %s 
             AND p.post_type = 'portofolio' 
             AND pm.meta_key = 'portofolio_wss_id'
             AND pm.meta_value = %s",
            $judul,
            $id_portofolio_wss
        );
        
        $post_id = $wpdb->get_var($query);
        
        if ($post_id) {
            // Post ditemukan, tampilkan ID
            return $post_id;
        } else {
            // Post tidak ditemukan
            return false;
        }
    }

    // Fungsi untuk menetapkan featured image dari URL
    public function set_featured_image_from_url($post_id, $image_url, $caption) {
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        $filename = basename($image_url);
        $file = $upload_dir['path'] . '/' . $filename;
        file_put_contents($file, $image_data);

        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => $caption,
            'post_excerpt'   => $caption,
            'post_status'    => 'inherit',
        );

        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($post_id, $attach_id);

        return $attach_id;
    }

    public function insert_portofolio($data,$id_post=null){
        $post = [
            'post_title' => $data['title'],
            'post_content' => $data['content'],
            'post_status' => 'publish',
            'post_type' => 'portofolio',
        ];

        //jika ada id maka update
        if($id_post){
            $post['ID'] = $id_post;
        }

        $post_id = wp_insert_post($post);
        if(!is_wp_error($post_id)){

            //tambahkan meta post
            update_post_meta($post_id, 'portofolio_wss_id', $data['id']);
            update_post_meta($post_id, 'portofolio_wss_last_modified', $data['last_modified']);
            update_post_meta($post_id, 'portofolio_wss_screenshot', $data['screenshot']);

            // Ambil term berdasarkan slug
            $term = get_term_by('slug', $data['jenis'], 'kategori-portofolio');
            if ($term) {
                // Ambil ID term
                $term_id = $term->term_id;
                // Tambahkan term ke post
                wp_set_post_terms($post_id, [$term_id], 'kategori-portofolio');
            }

            //check jika ada update thumbnail, thumbnail lama hapus
            if($id_post){
                $ss = get_post_meta( $post_id, 'portofolio_wss_screenshot', true );
                if (has_post_thumbnail($post_id) && $ss !== $data['screenshot'] ) {
                    // Hapus thumbnail yang ada
                    delete_post_thumbnail($post_id);

                    //upload thumbnail baru
                    $attachment_id = $this->set_featured_image_from_url($id_post,$data['screenshot'],$data['title']);
                }
            } else {
                //upload thumbnail
                $attachment_id = $this->set_featured_image_from_url($post_id,$data['screenshot'],$data['title']);
            }

        }

        return $post_id;
    }

    public function posts_portofolio($data){
        $result = [];

        foreach ($data as $k => $d) {

            //cek jika post tersedia
            $id_post = $this->check_portofolio_exist($d['title'], $d['id']);
            $status = $id_post?'Sudah diImport':'Belum diImport';

            // susun result
            $result[$k] = $d;
            $result[$k]['status'] = $status;
            
        }

        return $result;
    }
    
    public function ajax_importproses(){
        $data = sanitize_post($_POST['item']);

        //cek jika post tersedia
        $id_post = $this->check_portofolio_exist($data['title'], $data['id']);
        $status = $id_post?'Sudah diImport':'Belum diImport';       

        if($id_post){
            //check last_modified / tanggal terakhir perubahan
            $last = get_post_meta($id_post, 'portofolio_wss_last_modified', true);
            // Membuat objek DateTime
            $date1 = new DateTime($last);
            $date2 = new DateTime($data['last_modified']);
            //jika tanggal lebih dari / ada perubahan
            if ($date2 > $date1) {
                //insert post baru
                $id_post = $this->insert_portofolio($data, $id_post);
                $status = 'Berhasil Diperbaharui';
            }
        } else {
            //insert post baru
            $id_post = $this->insert_portofolio($data,false);
            $status = 'Berhasil diImport';
        }

        $return['status'] = $status;

        wp_send_json($return);
    }

    public function ajax(){
        $item = sanitize_post($_POST['item']);
        $data = $this->api($item);

        $return = [
            'item' => $item,
        ];

        if($item=='jenis-web'){          
            $return['data'] = $this->kategori_portofolio($data);
        } else {
            $return['data'] = $this->posts_portofolio($data);
        }

        wp_send_json($return);
    }

}

// Memanggil kelas untuk menjalankannya
$Wss_Portofolio_Ajax_import = new Wss_Portofolio_Ajax_Import();