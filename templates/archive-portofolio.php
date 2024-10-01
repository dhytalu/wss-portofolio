<?php
/**
 * The template for displaying archive portofolio
 *
 * @package wss-portofolio
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrapper" id="archive-wrapper">
    <div class="container" id="content" tabindex="-1">

        <main class="site-main" id="main">

            <?php
            if ( have_posts() ) {
                
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="taxonomy-description">', '</div>' );

                echo '<div class="wportos-row">';
                    // Start the loop.
                    while ( have_posts() ) {
                        the_post();

                        require(WSS_PORTOFOLIO_PLUGIN_DIR . 'templates/content-portofolio.php');

                    }
                echo '</div>';
            } else {
                echo 'Tidak ada portofolio disini..';
            }
            ?>

        </main><!-- #main -->

    </div>
</div>

<?php
get_footer();