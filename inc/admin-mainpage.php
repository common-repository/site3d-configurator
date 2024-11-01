<?php
/**
 * Site3D Admin section
 */

if (!defined('ABSPATH')) exit;

/**
 * Site3D_Wp_List_Table class will create the page to load the table
 */
class Site3D_Wp_Main_Page
{
  /**
   * Constructor will create the menu item
   */
  public function __construct()
  {
    add_action('admin_menu', array($this, 'admin_list_table_page'));
  }

  /**
   * Menu item will allow us to load the page to display the table
   */
  public function admin_list_table_page()
  {
    wp_enqueue_style('site3d-admin-style', plugin_dir_url(dirname(__FILE__)) . 'css/admin-style.css');

    // Fallback: Make sure admin always has access
    $site3d_cap = (current_user_can('site3d_access')) ? 'site3d_access' : 'manage_options';

    add_menu_page(__('Site3D Configurator', 'site3d-configurator'), __('Site3D', 'site3d-configurator'), $site3d_cap,
      'site3d-list.php', array($this, 'list_table_page'), plugins_url('/img/site3d.svg', dirname(plugin_basename(__FILE__))));
  }

  /**
   * Display the list table page
   *
   * @return Void
   */
  public function list_table_page()
  {
    $id_W = empty($_GET['id_W']) ? 0 : (int)$_GET['id_W'];
    $name_W = empty($_GET['name_W']) ? 0 : (int)$_GET['name_W'];

    if (!empty($name_W) && !empty($id_W))
    {
      new Site3D_Details();
      return;
    }

    ?>

    <div class="site3d">

      <h2 class="site3d__header"><?php _e('Site3D Configurator', 'site3d-configurator'); ?></h2>

      <p class="site3d__text site3d__text--big">
        1. <?php _e('Create a configurator in our', 'site3d-configurator'); ?>
        <a target="_blank" href="https://configurator.site3d.site"><?php _e('service', 'site3d-configurator'); ?></a>
      </p>

      <p class="site3d__text site3d__text--big">
        2. <?php _e('Get a shortcode to insert a 3D widget anywhere on the site', 'site3d-configurator'); ?>
      </p>

      <div class="site3d__frame">

        <div class="site3d__frame-content">

          <p class="site3d__text site3d__text--no-margin"><?php _e('Configurator ID *', 'site3d-configurator'); ?></p>

          <input type="text" name="id-input" class="site3d__input site3d__input--text">

          <p class="site3d__text"><?php _e('Widget Height', 'site3d-configurator'); ?></p>

          <div class="site3d__btn-container">

            <button class="site3d__button site3d__button--active" value="0">
              <?php _e('Default', 'site3d-configurator'); ?>
            </button>

            <button class="site3d__button" value="1000">
              <?php _e('100%', 'site3d-configurator'); ?>
            </button>

            <button class="site3d__button site3d__button--open-range" value="200">
              <?php _e('In pixels', 'site3d-configurator'); ?>
            </button>

          </div>

          <div class="site3d__range-container">

            <input type="range" class="site3d-input site3d__input--range" name="widthW (px)"
                   value="0" min="0" step="5" max="1000"/>

            <output class="site3d__range-output" for="slider__range"></output>

          </div>

        </div>


        <a class="site3d__button--result"><?php _e('Get a Shortcode', 'site3d-configurator'); ?></a>

        <div class="site3d__shirt-code"></div>

        <p class="site3d__copied"><?php _e('Shortcode copied!', 'site3d-configurator'); ?></p>

      </div>
    </div>
    <?php
  }
}