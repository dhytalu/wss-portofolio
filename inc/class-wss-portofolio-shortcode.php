<?php
class Wss_Portofolio_Shortcode {

    public function init() {
        add_shortcode( 'wportos-button-preview', array( $this, 'button_preview' ) );
    }

    public function button_preview($atts) {
        ob_start();

            add_thickbox();

            global $post;
            $atts = shortcode_atts(array(
                'id' => $post->post,
            ), $atts);

            $post_id = $atts['id'];
            $preview = get_post_meta($post_id,'portofolio_wss_preview',true);

            echo '<a href="https://demo36.sweet.web.id?keepThis=true&TB_iframe=true&height=500&width=500" class="wportos-button-preview thickbox">';
                echo $preview;
                echo 'Preview';
            echo '</a>';

        return ob_get_clean();
    }

}

$Wss_Portofolio_Shortcode = new Wss_Portofolio_Shortcode;
$Wss_Portofolio_Shortcode->init();