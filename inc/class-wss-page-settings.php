<?php
class Wss_Portofolio_Page_Settings {

    public $option_name = 'wss_portofolio';

    public function __construct() {
        // Hook untuk menambahkan menu admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        // Hook untuk mendaftarkan pengaturan
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_admin_menu() {
        // Menambahkan submenu di bawah post type 'portofolio'
        add_submenu_page(
            'edit.php?post_type=portofolio', // Parent slug
            'Settings Portofolio',        // Judul halaman
            'Settings',        // Judul menu
            'manage_options',           // Capability
            'wss_settings_portofolio',   // Slug
            [$this, 'create_admin_page']    // Callback untuk menampilkan halaman
        );
    }

    public function register_settings() {
        // Mendaftarkan pengaturan
        register_setting($this->option_name.'_grup', $this->option_name);
    }

    public function create_admin_page() {
        $options = get_option($this->option_name);
        ?>
        <div class="wrap">
            <h1>Pengaturan</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->option_name.'_grup'); // Pengaturan
                ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Access Key</th>
                        <td> 
                            <input type="text" name="<?php echo $this->option_name; ?>[access_key]" value="<?php echo esc_attr($options['access_key'] ?? ''); ?>" />
                            <p>Access Key dari Websweetstudio.com</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// Memanggil kelas untuk menjalankannya
if (is_admin()) {
    $Wss_Portofolio_Page_Settings = new Wss_Portofolio_Page_Settings();
}