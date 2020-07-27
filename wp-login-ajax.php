<?php

/**
 * Plugin Name:		WP Login Ajax
 * Plugin URI:		https://wordpress.org/plugins/wp-login-ajax
 * Description:		WP Login Ajax
 * Version:			1.0.0
 * Author:			sina shamsizadeh
 * Author URI:		https://instagram.com/sina_shamsizadeh
 * License:			GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:		wp-login-ajax
 * Domain Path:		/languages
 *
 * The plugin bootstrap file
 *
 * @author	Sina Shamizadeh
 * @package	Simple Invoice Generator
 * @since	1.0.0
 */

class WPLoginAjax {

	/**
    * Instance of this class.
    *
    * @since   1.0.0
    * @access  public
    * @var     WPLoginAjax
    */
	public static $instance;

    /**
    * Provides access to a single instance of a module using the singleton pattern.
    *
    * @since   1.0.0
    * @return	object
    */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
        }
		return self::$instance;
    }

	/**
    * Define the core functionality of the plugin.
    *
    * @since	1.0.0
    */
	public function __construct() {
        define( 'WPLOGINAJAXVERSION', '1.0.0' );
        define( 'WPLOGINAJAXURL', plugin_dir_url( __FILE__ ) );
        define( 'WPLOGINAJAXBASEID', 'wp_login_ajax' );

        add_action( 'init', [$this, 'load_plugin_textdomain'] );
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
        add_action( 'wp_ajax_live_login', [ $this, 'live_login' ] );
        add_action( 'wp_ajax_nopriv_live_login', [ $this, 'live_login' ] );
        add_shortcode('wp-login-ajax', [$this, 'shortcode']);
    }

	/**
    * Load the plugin text domain for translation.
    *
    * @since	1.0.0
    */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-login-ajax', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
    /**
    * Frontend scripts.
    *
    * @since   1.0.0
    */
    public function frontend_scripts() {
        wp_enqueue_style( WPLOGINAJAXBASEID.'-frontend-grid', WPLOGINAJAXURL.'assets/frontend.css', WPLOGINAJAXVERSION );
        wp_enqueue_script( 'wp-login-ajax-frontend-script', WPLOGINAJAXURL . 'assets/frontend.js', [ 'jquery' ], WPLOGINAJAXVERSION, true );
        wp_localize_script(
            'wp-login-ajax-frontend-script',
            'wp_login_ajax_localize',
            [
                'ajax'        => [
                    'url'      => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'wp_login_ajax_nonce' ),
                    'site_url' => site_url(),
                ],
                'translation' => [
                    'login' => [
                        'loading' => __( 'Please wait...', 'wp-login-ajax' ),
                        'success' => __( 'Login was successfully, Redirecting...', 'wp-login-ajax' ),
                        'fail'    => __( 'Your username or password is wrong', 'wp-login-ajax' ),
                    ],
                ],
            ]
        );
    }

    public static function ssl_url() {
        return ( is_ssl() ) ? 'https://' : 'http://';
    }

    /**
    * Login.
    *
    * @since   1.0.0
    */
    public static function login( $livelogin = false ) {
        $livelogin = $livelogin == true ? 'wp-login-ajax-live-login' : '';
        global $wp;
        $current_url = home_url( $wp->request );
        $form_action = $livelogin == true ? $current_url : home_url() . '/wp-login.php';

        $out = '<div class="wp-login-ajax-login-wrap">';
            if ( ! is_user_logged_in() ) :
                $out .= '
                <form name="loginform" id="login" class="wp-login-ajax-login-login ' . esc_attr( $livelogin ) . '" action="' . esc_url( $form_action, self::ssl_url() ) . '" method="post">
                    <div class="status" style="display: none;"></div>
                    <div class="wrap-input username">
                        <label for="username">' . __( 'Username', 'wp-login-ajax' ) . '</label>
                        <input type="text" name="log" id="user_login" class="input" value="" size="20" placeholder="Username or Email">
                    </div>
                    <div class="wrap-input password">
                        <label for="password">' . __( 'Password', 'wp-login-ajax' ) . '</label>
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" placeholder="Password">
                    </div>
                    <div class="submit-lost">
                        <input class="submit_button" type="submit" value="Login" name="submit">
                        <a href="' . wp_lostpassword_url() . '" class="lost">' . __( 'Lost your password?', 'wp-login-ajax' ) . '</a>
                    </div>
                </form>';
            else :
                $user = wp_get_current_user();
                $out .= '
                <div class="avatar">
                    ' . get_avatar( $user->ID ) . '
                    <p class="user-name">' . __( 'Welcome', 'wp-login-ajax' ) . ' ' . $user->display_name . '</p>
                </div>
                <div class="login-btns">
                    <a href="' . esc_url( get_edit_profile_url( $user->ID ), self::ssl_url() ) . '" class="profile">' . __( 'Profile', 'wp-login-ajax' ) . '</a>
                    <a href="' . wp_logout_url( esc_url( $current_url, self::ssl_url() ) ) . '" class="login_button">' . __( 'Logout', 'wp-login-ajax' ) . '</a>
                </div>';
            endif;
        $out .= '</div>';

        echo $out;
    }

    /**
    * Live Login.
    *
    * @since   1.0.0
    */
    public function live_login() {

        // Check nonce
        check_ajax_referer( 'wp_login_ajax_nonce', 'nonce' );

        $info                  = array();
        $info['user_login']    = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember']      = true;
        $user_signon           = wp_signon( $info, false );

        // Return necessary data
        if ( is_wp_error( $user_signon ) ) :
            echo json_encode(
                array(
                    'loggedin' => false,
                    'status'   => false,
                )
            );
        else :
            echo json_encode(
                array(
                    'loggedin' => true,
                    'status'   => true,
                )
            );
        endif;

        wp_die();
    }

    /**
    * Account shortcode.
    *
    * @since   1.0.0
    */
    public function shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'url'						=> '#',
        ), $atts));

        self::login(true);
    }


}
WPLoginAjax::get_instance();