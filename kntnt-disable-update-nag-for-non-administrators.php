<?php

/**
 * @wordpress-plugin
 * Plugin Name:
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Disables update nags for non-administrators.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) && new Plugin;

final class Plugin {

    private $allowed_roles = [ 'administrator' ];

    public function __construct() {
        add_action( 'init', [ $this, 'run' ] );
    }

    public function run() {
        if ( ! array_intersect( wp_get_current_user()->role, $allowed_roles ) ) {
            add_filter( 'pre_site_transient_update_core', [ $this, 'dont_nag' ] );
            add_filter( 'pre_site_transient_update_plugins', [ $this, 'dont_nag' ] );
            add_filter( 'pre_site_transient_update_themes', [ $this, 'dont_nag' ] );
        }
    }

    public function dont_nag() {
        global $wp_version;
        return (object) [
            'last_checked' => time(),
            'version_checked' => $wp_version,
        ];
    }

}
