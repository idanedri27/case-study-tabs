<?php
/**
 * Case Study Theme — theme setup, assets and ACF block registration.
 *
 * @package case-study-theme
 */

defined( 'ABSPATH' ) || exit;

define( 'CST_THEME_VERSION', '1.0.0' );
define( 'CST_THEME_DIR', get_template_directory() );
define( 'CST_THEME_URI', get_template_directory_uri() );

/**
 * Theme supports.
 */
function cst_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'script', 'style' ) );

	load_theme_textdomain( 'case-study-theme', CST_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'cst_theme_setup' );

/**
 * Front-end assets.
 */
function cst_enqueue_assets() {
	wp_enqueue_style(
		'cst-main',
		CST_THEME_URI . '/assets/css/main.css',
		array(),
		CST_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'cst_enqueue_assets' );

/**
 * Register ACF blocks (block.json based) + block view script.
 */
function cst_register_acf_blocks() {
	wp_register_style(
		'cst-main',
		CST_THEME_URI . '/assets/css/main.css',
		array(),
		CST_THEME_VERSION
	);

	wp_register_script(
		'cst-case-study-tabs',
		CST_THEME_URI . '/assets/js/case-study-tabs.js',
		array(),
		CST_THEME_VERSION,
		true
	);

	register_block_type( CST_THEME_DIR . '/blocks/case-study-tabs' );
}
add_action( 'init', 'cst_register_acf_blocks' );

/**
 * ACF local JSON — save & load field groups from the theme.
 */
function cst_acf_json_save_point( $path ) {
	return CST_THEME_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'cst_acf_json_save_point' );

function cst_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = CST_THEME_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'cst_acf_json_load_point' );

/**
 * Allow SVG uploads (safe for local/admin use — needed for the Figma icons).
 */
function cst_allow_svg_uploads( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cst_allow_svg_uploads' );

/**
 * Fix SVG mime detection on WordPress uploads.
 */
function cst_fix_svg_mime_check( $data, $file, $filename, $mimes ) {
	if ( str_ends_with( strtolower( $filename ), '.svg' ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'cst_fix_svg_mime_check', 10, 4 );
