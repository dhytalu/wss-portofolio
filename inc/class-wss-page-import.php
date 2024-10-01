<?php
class Wss_Portofolio_Page_import {

    public function __construct() {
        // Hook untuk menambahkan menu admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        // Enqueue script
        add_action('admin_enqueue_scripts', [$this,'enqueue_admin_script']);
    }

    public function add_admin_menu() {
        // Menambahkan submenu di bawah post type 'portofolio'
        add_submenu_page(
            'edit.php?post_type=portofolio', // Parent slug
            'Import Portofolio',        // Judul halaman
            'Import Portofolio',        // Judul menu
            'manage_options',           // Capability
            'wss_import_portofolio',   // Slug
            [$this, 'create_admin_page']    // Callback untuk menampilkan halaman
        );
    }

    public function enqueue_admin_script($hook) {
        // Cek apakah ini adalah halaman dengan slug yang diinginkan
        if ($hook != 'portofolio_page_wss_import_portofolio') {
            return;
        }

        // Enqueue script
        wp_enqueue_script('wss-import-portofolio', WSS_PORTOFOLIO_PLUGIN_URL . 'js/wss-import.js', array('jquery'), WSS_PORTOFOLIO_VERSION, true);
    }

    public function create_admin_page() {
        add_thickbox();
        ?>
        <div class="wrap">
            <h1>Import Portofolio</h1>
            <form id="wss-import-portofolio" method="post" action="">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Item</th>
                        <td> 
                            <select name="item" id="item-portofolio">
                                <option value="portofolio">Portofolio</option>
                                <option value="jenis-web">Kategori Portofolio</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php
                submit_button('Ambil Data');
                ?>
            </form>
            <br>
            <br>
            <div class="wss-result-ajax"></div>

            <style>
                .prosesimport {
                    position: relative;
                    background-color: #ffffff;
                    padding: 1rem;
                    margin-bottom: 1rem;
                }
                .prosesimport .progress {
                    content: '';
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    width: 0%;
                    background-color: #bae2fe;
                    z-index: 1;
                    transition: all 1s;
                }
                .prosesimport.success .progress {
                    background-color: #bffeba;
                }
                .prosesimport span {
                    position: relative;
                    z-index: 2;
                }
            </style>

        </div>
        <?php
    }
}

// Memanggil kelas untuk menjalankannya
if (is_admin()) {
    $Wss_Portofolio_Page_import = new Wss_Portofolio_Page_import();
}