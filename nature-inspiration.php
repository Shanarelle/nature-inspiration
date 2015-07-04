<?php
  /*
  Plugin Name: Nature Inspiration
  Plugin URI: http://example.com/
  Description: Accepts a set of likes and searches 500px for an image in some
    of those likes - returning a hopefully inspirational picture
  Version: 0.0.1
  Author: Clare Reid
  Author URI: http://example.com/
  */

  /**
   * Copyright (c) `date "+%Y"` . All rights reserved.
   *
   * Released under the GPL license
   * http://www.opensource.org/licenses/gpl-license.php
   *
   * This is an add-on for WordPress
   * http://wordpress.org/
   *
   * **********************************************************************
   * This program is free software; you can redistribute it and/or modify
   * it under the terms of the GNU General Public License as published by
   * the Free Software Foundation; either version 2 of the License, or
   * (at your option) any later version.
   *
   * This program is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU General Public License for more details.
   * **********************************************************************
   */

   class WP_Nature_Inspiration{

  // Constructor
    function __construct() {

        // Tracks new sections for whitelist_custom_options_page()
        $this->page_sections = array();
        // Must run after wp's `option_update_filter()`, so priority > 10
        add_action( 'whitelist_options', array( $this, 'whitelist_custom_options_page' ),11 );

        // create the settings page content
        add_action('admin_init', array( $this, 'plugin_admin_init' ) );
        // register to menu
        add_action( 'admin_menu', array( $this, 'wpni_add_menu' ));
        register_activation_hook( 'nature-inspiration.php', array( $this, 'wpni_install' ) );
        register_deactivation_hook( 'nature-inspiration.php', array( $this, 'wpni_uninstall' ) );

    }

    /*
      * Actions perform at loading of admin menu
      */
    function wpni_add_menu() {

        add_options_page( 'Nature Inspiration', 'Inspire Settings', 'create_users', 'nat_insp', array( $this, 'wpni_settings_page' ) );
    }

    function plugin_admin_init(){
      register_setting( 'nat_insp_options', 'nat_insp', array( $this, 'plugin_options_validate' ) );
      $this->add_settings_section('nat_insp_main', 'Inspiration Settings', array( $this, 'plugin_section_text' ), 'nat_insp');
      add_settings_field('plugin_text_checkbox', 'Plugin Checkbox Input', array( $this, 'plugin_setting_checkbox' ), 'nat_insp', 'nat_insp_main');
    }

    /*
    * settings description paragraph
    */
    function plugin_section_text() {
        error_log( "displaying section text" );
        echo "<p>Main description of this section here.</p>";
    }

    /*
    * rendering logic for specific setting - not sure if can pass in parameters
    */
    function plugin_setting_checkbox() {
        $options = get_option('nat_insp_options');
        echo "<input id='plugin_text_checkbox' name='nat_insp_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
    }

    /*
    * will need to actually validate in due course - probably
    */
    function plugin_options_validate($input) {
        error_log( "input: " + $input );
        return $input;
    }

    /*
    * will output the settings form where you can select your likes
    */
    function wpni_settings_page() {
        ?>
        <div class="wrap">
            <h2>Natural Inspiration</h2>
            <form method="post" action="options.php">
              <?php
                settings_fields( 'nat_insp' );
                do_settings_sections( 'nat_insp' );
                submit_button();
              ?>
              <!-- Trees <input type="checkbox" name="option1" value="Trees"><br>
              Lakes <input type="checkbox" name="option1" value="Lakes"><br>
              Rivers <input type="checkbox" name="option1" value="Rivers"><br>
              Waterfalls <input type="checkbox" name="option1" value="Waterfalls"><br>
              Jungles <input type="checkbox" name="option1" value="Jungles"><br>
              Deserts <input type="checkbox" name="option1" value="Deserts"><br>
              Swamps <input type="checkbox" name="option1" value="Swamps"><br>
              Mountains <input type="checkbox" name="option1" value="Mountains"><br>
              Fog <input type="checkbox" name="option1" value="Fog"><br> -->
            </form>
        </div>
        <?php
    }

    /*
     * Actions perform on activation of plugin
     */
    function wpni_install() {
      $default_options = array(
        'text_string' => 'hiya'
      );
      update_options('nat_insp_options', $default_options);

    }

    /*
     * Actions perform on de-activation of plugin
     */
    function wpni_uninstall() {



    }

    // White-lists options on custom pages.
    // Workaround for second issue: http://j.mp/Pk3UCF
    public function whitelist_custom_options_page( $whitelist_options ){
        // Custom options are mapped by section id; Re-map by page slug.
        foreach($this->page_sections as $page => $sections ){
            $whitelist_options[$page] = array();
            foreach( $sections as $section )
                if( !empty( $whitelist_options[$section] ) )
                    foreach( $whitelist_options[$section] as $option )
                        $whitelist_options[$page][] = $option;
                }
        return $whitelist_options;
    }

    // Wrapper for wp's `add_settings_section()` that tracks custom sections
    private function add_settings_section( $id, $title, $cb, $page ){
        add_settings_section( $id, $title, $cb, $page );
        if( $id != $page ){
            if( !isset($this->page_sections[$page]))
                $this->page_sections[$page] = array();
            $this->page_sections[$page][$id] = $id;
        }
    }

}

new WP_Nature_Inspiration();

?>


<?php

// Creating the widget
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget',

// Widget name will appear in UI
__('WPBeginner Widget', 'wpb_widget_domain'),

// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), )
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( 'Hello, World!', 'wpb_widget_domain' );
echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );





?>
