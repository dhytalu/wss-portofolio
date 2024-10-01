<?php
/**
 * The template for displaying single portofolio
 *
 * @package wss-portofolio
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrapper" id="single-wrapper">
    <div class="container" id="content" tabindex="-1">

        <main class="site-main" id="main">

            <?php
                the_title(
                    sprintf( '<h2 class="wportos-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                    '</a></h2>'
                );
            ?>

            <?php if (has_post_thumbnail()) { 
                echo get_the_post_thumbnail(get_the_ID(), 'large', array('class' => 'wportos-thumb', 'loading' => 'lazy'));
            } ?>

        </main><!-- #main -->

    </div>
</div>

<?php
get_footer();