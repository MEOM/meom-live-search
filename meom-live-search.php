<?php
/**
 * Plugin Name: MEOM Live Search
 * Plugin Uri: https://github.com/MEOM/meom-live-search
 * Description: WordPress plugin for showing live search results. Compatible with Polylang and Relevanssi plugins.
 * Version: 1.0.3
 * Author: MEOM
 * Author URI: https://www.meom.fi
 * Text Domain: meom-live-search
 * Domain Path: /languages
 */

    /*
    MEOM Live Search
    Copyright © 2019 MEOM Oy

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
    */

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

define( 'MEOM_LIVE_SEARCH_VERSION', '1.0.3' );

/**
 * MEOM Live Search class.
 *
 * @class MEOM_Live_Search
 * @since   1.0.0
 * @version 1.0.0
 */
class MEOM_Live_Search {

    /**
     * Constructor
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function __construct() {
        // Add actions
        add_action( 'rest_api_init', array( $this, 'register_live_search_rest_route' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'mls_enqueue_scripts' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    }

    /**
     * Initialize rest api point
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function register_live_search_rest_route() {
        register_rest_route('meom-live-search/v1', '/search', array(
            'methods'  => 'GET',
            'callback' => function () {
                $default_args = array(
                    'post_status' => 'publish',
                    'post_type'   => get_post_types( array(
                        'public'              => true,
                        'exclude_from_search' => false,
                    ) ),
                );

                $args = apply_filters( 'meom_live_search_args', array_merge( $default_args, $_GET ), $_GET );

                ob_start();

                if ( function_exists( 'relevanssi_do_query' ) ) {
                    $query_object = new WP_Query( $args );
                    $search_results = relevanssi_do_query( $query_object );
                } else {
                    $search_results = new WP_Query( $args );
                    if ( ! empty( $search_results->posts ) ) {
                        $search_results = $search_results->posts;
                    } else {
                        $search_results = [];
                    }
                }

                $this->mls_render_template( [ 'search_results' => $search_results, 's' => $args[ 's' ] ] );

                return array( 'resultHTML' => ob_get_clean() );
            },
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Render template
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function mls_render_template( $args = [] ) {
        $template = locate_template( '/meom-live-search/search-results.php' );

        if ( ! $template ) {
            $template = __DIR__ . '/templates/search-results.php';
        }

        extract( $args );
        include $template;
    }

    /**
     * Enqueue scripts
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function mls_enqueue_scripts() {
        wp_enqueue_script(
            'meom-live-search',
            plugins_url( '/js/meom-live-search.js', __FILE__ ),
            array( 'jquery' ),
            MEOM_LIVE_SEARCH_VERSION,
            true
        );

        $live_search = array(
            'lang'           => ( function_exists( 'pll_current_language' ) ) ? pll_current_language( 'slug' ) : get_locale(),
            'resultsElement' => esc_attr( apply_filters( 'meom_live_search_results_element', '.meom-live-search' ) ),
            'searchInput'    => esc_attr( apply_filters( 'meom_live_search_input', '[name="s"]' ) ),
        );
        wp_localize_script(
            'meom-live-search',
            'liveSearch',
            $live_search
        );

        wp_enqueue_style(
            'meom-live-search-style',
            plugins_url( 'css/meom-live-search.css', __FILE__ ),
            array(),
            MEOM_LIVE_SEARCH_VERSION
        );
    }

    /**
     * Load plugin textdomain
     *
     * @since   1.0.0
     * @version 1.0.0
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'meom-live-search',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }
}

$meom_live_search = new MEOM_Live_Search();
