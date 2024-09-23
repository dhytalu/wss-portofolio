<?php
class Wss_Portofolio_Post {

    public $post_type = 'portofolio';
    public $kategori = 'kategori-portofolio';

    public function init() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }

    //register post type
    public function register_post_type() {
        $labels = array(
            'name' => 'Portofolio',
            'singular_name' => 'Portofolio',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Portofolio',
            'edit_item' => 'Edit Portofolio',
            'new_item' => 'New Portofolio',
            'view_item' => 'View Portofolio',
            'search_items' => 'Search Portofolio',
            'not_found' => 'No Portofolio found',
            'not_found_in_trash' => 'No Portofolio found in Trash',
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        register_post_type($this->post_type, $args);
    }

    //register taxonomy
    public function register_taxonomy() {
        $labels = array(
            'name' => 'Kategori Portofolio',
            'singular_name' => 'Kategori Portofolio',
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->kategori)
        );

        register_taxonomy($this->kategori, $this->post_type, $args);
    }

}

$portofolio = new Wss_Portofolio_Post;
$portofolio->init();