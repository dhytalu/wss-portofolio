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

    public function ajax(){
        $item = sanitize_post($_POST['item']);
        $data = $this->api($item);

        $return = [
            'item' => $item,
        ];

        if($item=='jenis-web'){          
            $return['data'] = $this->kategori_portofolio($data);
        }

        wp_send_json($return);
    }

}

// Memanggil kelas untuk menjalankannya
$Wss_Portofolio_Ajax_import = new Wss_Portofolio_Ajax_Import();