<?php
/**
* Plugin Name: Outer Link Creator
* Plugin URI: https://github.com/uurtech/outerlinkcreator
* Description: Wordpress Plugin That Changes Links With Redirections Page
* Version: 0.81321
* Author: uurtech
* Author URI: http://www.teknoturko.com
* License: Freeware
*/
class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Outer Link Creator', 
            'administrator', 
            'outer-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'outer_link_creator' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>My Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'outer_option_group' );   
                do_settings_sections( 'outer-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'outer_option_group', // Option group
            'outer_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Delay in seconds', // Title
            array( $this, 'print_section_info' ), // Callback
            'outer-setting-admin' // Page
        );  

        add_settings_field(
            'delay_in_second', // ID
            'Delay in second', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'outer-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'delay_message', 
            'Message', 
            array( $this, 'title_callback' ), 
            'outer-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['delay_in_second'] ) )
            $new_input['delay_in_second'] = absint( $input['delay_in_second'] );

        if( isset( $input['delay_message'] ) )
            $new_input['delay_message'] = sanitize_text_field( $input['delay_message'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        try{
            $outer = get_option('outer_option_name');
        }catch(Exception $e){
            $outer['delay_in_second'] = "";
            $outer['delay_message'] = "";
        }
        printf(
            '<input type="text" id="delay_in_second" name="outer_option_name[delay_in_second]" value="'.$outer['delay_in_second'].'" />',
            isset( $this->options['delay_in_second'] ) ? esc_attr( $this->options['delay_in_second']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
         try{
            $outer = get_option('outer_option_name');
        }catch(Exception $e){
            $outer['delay_in_second'] = "";
            $outer['delay_message'] = "";
        }
        printf(
            '<input type="text" id="title" name="outer_option_name[delay_message]" value="'.$outer['delay_message'].'" />',
            isset( $this->options['delay_message'] ) ? esc_attr( $this->options['delay_message']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();

function outer_link_create($content){
    $yeni = str_replace('href="http','href="http://www.teknoturko.com/wp-content/plugins/outerlinkcreator/link.php?link=http',$content);
    return $yeni;
}

add_filter('the_content','outer_link_create');