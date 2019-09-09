<?php

/*
Plugin Name: Nha Cai Asia
Plugin URI: https://nhacaiasia.com/
Description: This is custom plugin for NhaCaiAsia.
Version: 1.0.0
Author: RiverN
Author URI: https://nhacaiasia.com/
Text Domain: nhacaiasia
*/

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'NHACAI__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NHACAI__PLUGIN_URL', plugin_dir_url( __FILE__ ) );


//add custom tag for custom post_tye
require_once NHACAI__PLUGIN_DIR.'/taxonomy/main.php';
new NHACAIASIA_CUSTOM_TAXONOMY();

add_action( 'init', 'gp_register_taxonomy_for_object_type');

function gp_register_taxonomy_for_object_type() {
    register_taxonomy_for_object_type('cac-sanh','listing');
    register_taxonomy_for_object_type('san-pham','listing');
    register_taxonomy_for_object_type('phan-loai','listing');
};



//add metabox Khuyen Mai To Listing
require_once NHACAI__PLUGIN_DIR.'/metabox/khuyen-mai.php';
new KHUYENMAI_META;


//add metabox Nha Cai To Events
require_once NHACAI__PLUGIN_DIR.'/metabox/nha-cai.php';
new NHACAI_META;


//add upload img for custom tax ab
require_once NHACAI__PLUGIN_DIR.'/showimg/sanh-upload.php';
require_once NHACAI__PLUGIN_DIR.'/showimg/san-pham-upload.php';
require_once NHACAI__PLUGIN_DIR.'/showimg/phan-loai-upload.php';


// Search Class For Search APi
class Search {

    public $post_id;
    public $title;

    public function Search($post_id,$title) {
        $this->post_id = $post_id;
        $this->title = $title;
    }
}


// API Search
add_action('wp_ajax_load_search', 'load_search');
function load_search() {

    $query = isset($_POST['query'])? $_POST['query'] : "";
    $args = array('s'=> $query,'post_type'=>'events');
    $search = new wp_query($args);
    $res = [];
    if($search->have_posts()) while($search->have_posts()) : $search->the_post();
        $res[] = new Search(get_the_id(),get_the_title());
    endwhile; wp_reset_postdata();
    
    header('Content-Type: application/json');
    echo json_encode($res);
    exit;
}