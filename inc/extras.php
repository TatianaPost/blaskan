<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package blaskan
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blaskan_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add mansonry class
	if ( is_home() || is_search() || is_front_page() || is_archive() || is_category() || is_tag() ) {
		$classes[] = 'mansonry-posts';
	}

	return $classes;
}
add_filter( 'body_class', 'blaskan_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blaskan_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'blaskan_pingback_header' );


/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function blaskan_post_classes( $classes ) {
	
	global $wp_query;
	
	if ( $wp_query->current_post == 0 && !is_singular() ) {
		$classes[] = 'big-post col-md-12';
	}

	if ( $wp_query->current_post != 0 && !is_singular() ) {
		$classes[] = 'col-md-6 col-sm-12';
	}

	return $classes;
}
add_filter( 'post_class', 'blaskan_post_classes' );

function blaskan_comment($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">

    <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    </div>
    

    <div class="comment-content">
    	<div class="comment-meta commentmetadata">
	    	<div class="comment-info">
	    		<?php printf( __( '<cite class="fn">%s</cite>' ), get_comment_author_link() ); ?>
		        <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
		        	<?php printf( '%1$s', get_comment_date() ); ?>
		        </a>
		        <?php edit_comment_link( __( '(Edit)', 'blaskan' ), '  ', '' ); ?>
	    	</div>

	         <div class="reply">
		        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		    </div>
	    </div>

	    <?php if ( $comment->comment_approved == '0' ) : ?>
	        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
	        <br />
	    <?php endif; ?>
	    <div class="comment-text">
	    	<?php comment_text(); ?>
	    </div>
    </div>
    

   
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
    <?php
    }