<?php

/*
	Plugin Name: MEOM Live Search
	Description: Showing live search results.
	Author: MEOM
	Author URI: https://www.meom.fi
	Version: 1.0.0
*/

add_action('rest_api_init', function () {
	register_rest_route('meom/v1', '/search', array(
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

			query_posts( $args );
			mls_render_template();
			wp_reset_postdata();

			return array( 'resultHTML' => ob_get_clean() );
		},
	));
});

function mls_render_template() {
	$template = locate_template( '/meom-live-search/search-results.php' );

	if ( ! $template ) {
		$template = __DIR__ . '/templates/search-results.php';
	}

	include( $template );
}

function mls_enqueue_scripts() {
	wp_enqueue_script(
		'meom-live-search',
		plugins_url( '/js/meom-live-search.js', __FILE__ ),
		array( 'jquery', 'underscore' ),
		'1.0.0',
		true
	);

	$live_search = array(
		'lang'           => ( function_exists( 'pll_current_language' ) ) ? pll_current_language( 'slug' ) : get_locale(),
		'resultsElement' => esc_attr( apply_filters( 'meom_live_search_results_element', '.meom-live-search' ) ),
		'searchInput'    => esc_attr( apply_filters( 'meom_live_search_input', '[name="s"]' ) ),
	);
	wp_localize_script( 'meom-live-search', 'liveSearch', $live_search );
}
add_action( 'wp_enqueue_scripts', 'mls_enqueue_scripts' );
