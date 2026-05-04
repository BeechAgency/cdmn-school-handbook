<?php
/**
 * Plugin Name: CDMN School Handbook
 * Description: Enables Student & Staff Handbook functionality for Schools using the Catholic Diocese of Maitland-Newcastle template.
 * Version: 1.2
 * Author: Beech Agency
 * Author URI: https://beech.agency
 */

if ( ! defined('ABSPATH') ) exit;


add_action('wp_enqueue_scripts', 'school_handbook_enqueue_assets');
function school_handbook_enqueue_assets() {

	if ( ! is_singular('page') ) {
		return;
	}

	$template = get_page_template_slug(get_queried_object_id());

	if (
		$template === 'templates/handbook.php' 
	) {

		wp_enqueue_style(
			'school-handbook',
			plugin_dir_url(__FILE__) . 'assets/css/handbook.css',
			[],
			'2.3'
		);

		wp_enqueue_script(
			'school-handbook',
			plugin_dir_url(__FILE__) . 'assets/js/handbook.js',
			['jquery'],
			'2.3',
			true
		);

		if ( ! wp_style_is('slick-css', 'registered') ) {
			wp_register_style(
				'slick-css',
				plugin_dir_url(__FILE__) . 'assets/css/slick.css'
			);
		}

		if ( ! wp_script_is('slick-js', 'registered') ) {
			wp_register_script(
				'slick-js',
				plugin_dir_url(__FILE__) . 'assets/js/slick.min.js',
				['jquery'],
				'1.8.1',
				true
			);
		}

		wp_enqueue_style('slick-css');
		wp_enqueue_script('slick-js');
	}
}

add_filter('body_class', 'school_handbook_body_class');
function school_handbook_body_class($classes) {
	if ( ! is_singular('page') ) return $classes;

	$template = get_page_template_slug(get_queried_object_id());

	if ( $template === 'templates/handbook.php' ) {
		$classes[] = 'handbook';
	}

	return $classes;
}

register_activation_hook(__FILE__, 'school_handbook_create_pages');
function school_handbook_create_pages() {

   $pages = [
        [
            'title'    => 'Student Handbook',
            'slug'     => 'student-handbook',
            'template' => 'handbook.php',
        ],
        [
            'title'    => 'Staff Handbook',
            'slug'     => 'staff-handbook',
            'template' => 'handbook.php',
        ],
    ];

   foreach ( $pages as $page ) {

         $existing = get_posts([
            'post_type'  => 'page',
            'title'      => $page['title'],
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'templates/' . $page['template'],
            'numberposts'=> 1,
            'post_status'=> ['publish','draft','private']
        ]);

        if ( $existing ) {
            continue;
        }

         $page_id = wp_insert_post([
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_status'  => 'draft',
            'post_type'    => 'page',
            'post_content' => '',
        ]);

        if ( $page_id && ! is_wp_error($page_id) ) {
            update_post_meta(
                $page_id,
                '_wp_page_template',
                'templates/' . $page['template']
            );
        }
    }
}

add_filter('theme_page_templates', 'school_handbook_register_templates');
function school_handbook_register_templates($templates) {
  $templates['templates/handbook.php'] = 'Handbook';
  return $templates;
}

add_filter('template_include', 'school_handbook_template_include');
function school_handbook_template_include($template) {

  if ( is_page() ) {
    $page_template = get_page_template_slug();

    if ( $page_template === 'templates/handbook.php' ) {
      return plugin_dir_path(__FILE__) . 'templates/handbook.php';
    }


  }

  return $template;
}

add_filter('acf/settings/load_json', 'school_handbook_acf_load_json');
function school_handbook_acf_load_json($paths) {
  $paths[] = plugin_dir_path(__FILE__) . 'acf/acf-json';
  return $paths;
}

add_filter('acf/settings/save_json', 'school_handbook_acf_save_json');
function school_handbook_acf_save_json($path) {

    if (
        isset($_POST['acf_field_group']['key']) &&
        $_POST['acf_field_group']['key'] === 'group_6986ecc51be49'
    ) {
        return plugin_dir_path(__FILE__) . 'acf/acf-json';
    }

    return $path;
}

add_action('admin_head', 'school_handbook_admin_head');
function school_handbook_admin_head() {

    if ( ! isset($_GET['post']) ) return;

    $post_id = (int) $_GET['post'];
    $template = get_page_template_slug($post_id);

    if ( in_array($template, [
        'templates/handbook.php'
    ]) ) {
        echo '<style>
            .acf-postbox { display: none !important; }
            #acf-group_6986ecc51be49 { display: block !important; }
        </style>';
    }
}

/*Preview*/
add_filter(
    'acfe/flexible/render/template/key=field_6986ecc60eeee',
    'school_handbook_acfe_templates',
    10,
    4
);
function school_handbook_acfe_templates($file, $field, $layout, $is_preview) {

    $file = plugin_dir_path(__FILE__) . 'acf-layouts/' . $layout['name'] . '/template.php';
    return $file;
}

/*Thumbnails*/
add_filter('acfe/flexible/thumbnail/key=field_6986ecc60eeee', 'school_handbook_flexible_thumbnail', 10, 3);
function school_handbook_flexible_thumbnail($thumbnail, $field, $layout){
	$file = plugin_dir_url(__FILE__) . 'acf-layouts/' . $layout['name'] . '/template.png';
    if(file_exists(plugin_dir_path(__FILE__) . 'acf-layouts/' . $layout['name'] . '/template.png')){
        return $file;
    }
    return $thumbnail;
}

add_action('admin_notices', 'school_handbook_acfe_admin_notice');
function school_handbook_acfe_admin_notice() {

    if ( ! function_exists('is_plugin_active') ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( ! is_plugin_active('acf-extended/acf-extended.php') ) {

        echo '<div class="notice notice-error">
            <p><strong>School Handbook:</strong> This plugin requires <strong>Advanced Custom Fields: Extended</strong> to be installed and activated.</p>
        </div>';
    }
}

/*CSS*/
add_action('admin_enqueue_scripts', 'school_handbook_admin_assets');
function school_handbook_admin_assets($hook_suffix) {

    if ( ! isset($_GET['post']) ) return;

    $post_id = (int) $_GET['post'];
    $template = get_page_template_slug($post_id);

    if ( in_array($template, [
        'templates/handbook.php'
    ]) ) {
        wp_enqueue_style(
            'school-handbook-admin',
            plugin_dir_url(__FILE__) . 'assets/css/admin-handbook.css',
            [],
            '1.2'
        );
    }
}


/** UPDATER */
if( ! class_exists( 'BEECH_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}

$updater = new BEECH_Updater( __FILE__ );
$updater->set_username( 'BeechAgency' );
$updater->set_repository( 'cdmn-school-handbook' );
/*
	$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/
$updater->initialize();