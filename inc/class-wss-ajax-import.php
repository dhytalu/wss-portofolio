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

        return $body; // Return or process the data as needed

    }

    public function ajax(){
        $item = sanitize_post($_POST['item']);
        $data = $this->api($item);

        $return = array(
            'data'  => $data,
            'item'  => $item
        );
        wp_send_json($return);
    }

}

// Memanggil kelas untuk menjalankannya
$Wss_Portofolio_Ajax_import = new Wss_Portofolio_Ajax_Import();