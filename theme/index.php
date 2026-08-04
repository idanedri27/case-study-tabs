<?php
/**
 * Main template — renders page content (Gutenberg blocks).
 *
 * @package case-study-theme
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
