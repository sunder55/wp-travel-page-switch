<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')) :
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('child_theme_configurator_css')) :
    function child_theme_configurator_css()
    {
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer'));
        wp_enqueue_script('child-script', get_stylesheet_directory_uri() . '/scripts.js', array('jquery'), time());
        wp_localize_script('child-script', 'ajaxUrl', array('ajax_url' => admin_url('admin-ajax.php')));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 998);


//START CHANGE TRIP TYPE

//END CHANGE TRIP TYPE
//
//

// HIDE ADMIN BAR FOR USER EXCEPT ADMINISTRATOR
function hide_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar');

function redirect_non_admin_users()
{
    if (!current_user_can('administrator') && !defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'redirect_non_admin_users');
// END HIDE ADMIN BAR FOR USER EXCEPT ADMINISTRATOR
// 
// 

// RIDIRECT 'wp-login.php' TO 'my-account'
//function redirect_wp_login_to_my_account() {
    // Periksa apakah pengguna sedang mengakses wp-login.php
    //if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        // Lakukan pengalihan ke /my-account/
        //wp_redirect(site_url('/my-account/'));
       // exit();
    //}
//}
//add_action('init', 'redirect_wp_login_to_my_account');
// END RIDIRECT 'wp-login.php' TO 'my-account'
// 

// WP_TRAVEL_ENGINE_BOOK_CONFIRMATION shortcode for traveller information and travel booking

//  WP_TRAVEL_ENGINE_PLACE_ORDER  /// shortcode for checkout page

// WP_TRAVEL_ENGINE_THANK_YOU // shortcode for thankyou page


add_action('wp_ajax_nopriv_submit_traveler_information', 'submit_traveler_information');
add_action('wp_ajax_submit_traveler_information', 'submit_traveler_information');
function submit_traveler_information()
{
    $form_datas = $_POST['form_data'];
    $booking_id = $_POST['booking_id'];
    $outputArray = [];
    foreach ($form_datas as $item) {
        // Skip the nonce
        if ($item['name'] == 'nonce') {
            continue;
        }

        // Extract the parts of the name
        preg_match('/wp_travel_engine_placeorder_setting\[place_order\]\[travelers\]\[([^\]]+)\]\[([^\]]+)\]/', $item['name'], $matches);

        if (count($matches) == 3) {
            $key = $matches[1];
            $index = $matches[2];
            $value = $item['value'];

            // Assign the value to the output array
            $outputArray['place_order']['travelers'][$key][$index] = $value;
        }
    }

    if ($outputArray) {
        $update =  update_post_meta($booking_id, 'wp_travel_engine_placeorder_setting', $outputArray);
        if ($update == true) {

            echo 'successfull';
        }
    }

    wp_die();
}


add_action('save_post_booking', 'cpm_after_chekout_submit');

function cpm_after_chekout_submit($post_id)
{
    if (isset($_POST['wp_travel_engine_nw_bkg_submit'])) {
        $prev_booking_id = $_GET['bookingId'];
        // die(var_dump($prev_booking_id));
        $traveler_info = get_post_meta($prev_booking_id, 'wp_travel_engine_placeorder_setting', true);
        $update =  update_post_meta($post_id, 'wp_travel_engine_placeorder_setting', $traveler_info);
   
    }

}
