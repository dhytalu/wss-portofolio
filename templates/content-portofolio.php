<?php
/**
 * Post rendering content portofolio
 *
 * @package wss-portofolio
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class('wportos-col'); ?> id="post-<?php the_ID(); ?>">
    <div class="wportos-card">

        <?php if (has_post_thumbnail()) { 
            echo get_the_post_thumbnail(get_the_ID(), 'large', array('class' => 'wportos-thumb', 'loading' => 'lazy'));
        } ?>

        <?php
            the_title(
                sprintf( '<h2 class="wportos-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                '</a></h2>'
            );
        ?>

    </div>
</article>
