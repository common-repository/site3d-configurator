<?php
/**
 * Plugin Name: Site3D Configurator
 * Plugin URI: https://configurator.site3d.site
 * Description: The plugin allows you to use a shortcode to add a widget to any place on the site to demonstrate a 3D model with the ability to configure it.
 * Author: Site3D
 * Author URI: https://configurator.site3d.site
 * Text Domain: site3d-configurator
 * Domain Path: /lang/
 * Version: 0.1
 *
 * Requires at least: 2.5
 * Requires PHP: 5.4
 *
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function site3d_load_textdomain()
{
  load_plugin_textdomain('site3d-configurator', false, dirname(plugin_basename(__FILE__)) . '/lang');
}

add_action('init', 'site3d_load_textdomain');

function site3d_html_widget($atts)
{
  $params = shortcode_atts(
    array(
      'data' => ''
    ),
    $atts
  );
  return '<div data-site3d="' . $params["data"] . '"></div>';
}

add_shortcode('site3d', 'site3d_html_widget');

add_action('wp_print_footer_scripts', 'site3d_hook_javascript');

function site3d_hook_javascript()
{
  $locale = get_locale();

  if ($locale == 'ru_RU')
    $lang = 'ru';
  else
    $lang = 'en';

  echo wp_get_script_tag(
    array(
      'src' => 'https://site3d.site/configurator/load.js',
      'id' => "site3d-configurator-load",
      'data-lang' => $lang
    )
  );
}

add_action('init', 'site3d_init');

function site3d_init()
{
  if (is_admin())
  {
    add_action('wp_ajax_site3d_ajax_convert', 'site3d_ajax_convert');
    add_action('wp_ajax_nopriv_site3d_ajax_convert', 'site3d_ajax_convert');
    wp_enqueue_script(
      'Site3DAdminJs',
      trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) . 'js/admin-scripts.js',
      array('jquery')
    );

    wp_localize_script(
      'Site3DAdminJs',
      'Site3DAdminJs_obj',
      array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('Site3D-nonce')
      )
    );
    require_once 'inc/admin-mainpage.php';
    new Site3D_Wp_Main_Page();
  }
}

function site3d_ajax_convert()
{
  if (isset($_REQUEST))
  {
    if (!wp_verify_nonce($_REQUEST['security'], 'Site3D-nonce'))
      wp_die('Basic protection failed');

    $result = array('status' => false);

    $idW = (int)sanitize_text_field($_REQUEST['idW']);

    if ($idW > 0)
    {
      $result['status'] = true;

      $height = (int)sanitize_text_field($_REQUEST['heightW']);

      $data = $idW;

      if ($height == 0)
      {
        $data .= "";
      }
      elseif ($height < 1000)
      {
        $data .= ",height=" . $height . "px";
      }
      elseif ($height == 1000)
      {
        $data .= ",height=100%";
      }

      $result["answer"] = '[site3d data="' . $data . '"]';
    }

    echo json_encode($result);
  }

  wp_die();
}

?>
