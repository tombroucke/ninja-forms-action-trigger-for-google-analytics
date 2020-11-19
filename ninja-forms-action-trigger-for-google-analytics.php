<?php
/*
 * Plugin Name: Ninja Forms - Action Trigger for Google Analytics
 * Plugin URI: 
 * Description: Adds a custom action to Ninja Forms.
 * Version: 1.0.0
 * Author: Tom Broucke
 * Author URI: http://tombroucke.be
 * Text Domain: ninja-forms-action-trigger-for-google-analytics 
 * Domain Path: /languages/
 *
 * Copyright 2016 Tom Broucke.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

include "includes/server-side-google-analytics-master/ss-ga.class.php";

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    add_action( 'admin_notices', 'version_admin_notice' );

    function version_admin_notice(){

        $settings = Ninja_Forms()->get_settings();
        if( isset( $settings[ 'disable_admin_notices' ] ) && $settings[ 'disable_admin_notices' ] ) return;
        $currentScreen = get_current_screen();
        if( ! in_array( $currentScreen->id, array( 'toplevel_page_ninja-forms' ) ) ) return;

        ?>
        <div class="notice notice-error is-dismissible">
                <p class="notice-title"><?php _e( 'Upgrade Ninja Forms to at least version 3.0 to use the Action Trigger for Google Analytics', 'dmp' ); ?></p>
                <ul class="nf-notice-body nf-red">
                    <li><span class="dashicons dashicons-awards"></span><a href="<?php echo admin_url( 'admin.php?page=ninja-forms-three' ); ?>">Upgrade</a></li>
                </ul>
            </div>  
        <?php
    }

} else {

    /**
     * Class NF_ActionTriggerForGoogleAnalytics
     */
    final class NF_ActionTriggerForGoogleAnalytics
    {
        const VERSION = '0.0.1';
        const SLUG    = 'action-trigger-for-google-analytics';
        const NAME    = 'Action Trigger for Google Analytics';
        const AUTHOR  = 'Tom Broucke';
        const PREFIX  = 'NF_ActionTriggerForGoogleAnalytics';

        /**
         * @var NF_ActionTriggerForGoogleAnalytics
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Server Side Google Analytics
         *
         * @since 3.0
         * @var string $url
         */
        private static $ssga;

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_ActionTriggerForGoogleAnalytics Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_ActionTriggerForGoogleAnalytics)) {
                self::$instance = new NF_ActionTriggerForGoogleAnalytics();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }
            
            return self::$instance;
        }

        public function __construct()
        {
            /*
             * Required for all Extensions.
             */
            add_action( 'admin_init', array( $this, 'setup_license') );

            /*
             * Optional. If your extension processes or alters form submission data on a per form basis...
             */
            add_filter( 'ninja_forms_register_actions', array($this, 'register_actions'));

            add_action( 'plugins_loaded', array($this, 'load_textdomain') );

        }

        public function load_textdomain() {
            load_plugin_textdomain( 'ninja-forms-action-trigger-for-google-analytics', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_actions($actions)
        {
            $actions[ 'action-trigger-for-google-analytics' ] = new NF_ActionTriggerForGoogleAnalytics_Actions_ActionTriggerForGoogleAnalytics(); // includes/Actions/ActionTriggerForGoogleAnalyticsExample.php

            return $actions;
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }
        
        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }
        
        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /*
         * Required methods for all extension.
         */

        public function setup_license()
        {
            if ( ! class_exists( 'NF_Extension_Updater' ) ) return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }
    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_ActionTriggerForGoogleAnalytics()
    {
        return NF_ActionTriggerForGoogleAnalytics::instance();
    }

    NF_ActionTriggerForGoogleAnalytics();
}
