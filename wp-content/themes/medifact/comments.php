<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Medifact
 * @since Medifact 
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
*/

if ( post_password_required() ) {
    return;
}
?>
<div id="comments" class="comments">
    <?php if ( have_comments() ) : ?>
        <h3>
            <?php
                $comments_number = get_comments_number();
                if ( 1 === $comments_number ) {
                    /* translators: %s: post title */
                    printf( esc_html__( 'One thought on &ldquo;%s&rdquo;','medifact' ), get_the_title() );
                } else {
                    printf(
                        /* translators: 1: number of comments, 2: post title */
                        _nx(
                            '%1$s Comment',
                            '%1$s Comments',
                            $comments_number,
                            'comments title',
                            'medifact'
                        ),
                        esc_html(number_format_i18n( $comments_number ) ),
                        get_the_title()
                    );
                }
            ?>
        </h3>

        <?php the_comments_navigation(); ?>

        <div class="auth-comment">
			<?php
				wp_list_comments( array(
					'style'       => '',
					'short_ping'  => true,
					'avatar_size' => 42,
					'callback' => 'medifact_comments',
				) );
			?>
        </div>
        <!-- .comment-list -->

        <?php the_comments_navigation(); ?>

    <?php endif; // Check for have_comments(). ?>

    <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'medifact' ) ) :
    ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'medifact' ); ?></p> 
    <?php endif; ?>

    <?php 
        $req      = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $comments_args = array
        (
            'submit_button' => '<div class="form-group">'.
              '<input  name="%1$s" type="submit" id="%2$s" class="btn btn-submit" value="leave comment" />'.
            '</div>',
            'title_reply'  =>  esc_html__( '<h4>LEAVE A REPLY</h4>', 'medifact'  ), 
            'comment_notes_after' => '',  
                
            'comment_field' =>  
                '<textarea class="form-control" id="comment" name="comment" placeholder="' . esc_attr__( 'Message', 'medifact' ) . '" rows="7" aria-required="true" '. $aria_req . '>' .
                '</textarea>',
            'fields' => apply_filters( 'comment_form_default_fields', array (
                'author' => '<div >'.               
                    '<input id="author" class="form-control" name="author" placeholder="' . esc_attr__( 'Name', 'medifact' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                    '" size="30"' . $aria_req . ' /></div>',
                'email' =>'<div >'.
                    '<input id="email" class="form-control" name="email" placeholder="' . esc_attr__( 'Email Address', 'medifact' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                    '" size="30"' . $aria_req . ' /></div>',
            ) ),
        );
    ?>
</div>
<div class="reply-form" id="comment-box"> 
    <?php
    comment_form();
    ?>
</div>
<!-- .comments-area -->
