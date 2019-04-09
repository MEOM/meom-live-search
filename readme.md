# MEOM Live Search

## Usage

1. Add `<div class="meom-live-search"></div>` where you'd like to list live search results.

2. For custom results, add a following file to your theme folder: `/meom-live-search/search-results.php`.

Below is an example of custom search result template. Our function returns a variable called `$search_results` that contains an array of posts, if found any.

```
<div class="meom-live-search">
	<ul>
		<?php foreach ( $search_results as $result ) : ?>
			<li>
				<a href="<?php echo esc_url( get_the_permalink( $result->ID ) ); ?>">
					<?php echo esc_html( get_the_title( $result->ID ) ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
```

**HOX!** You need to include search form before using this plugin. For example:
```
<form>
	<label for="s">Search:</label>
	<input type="text" name="s">
</form>
```

## Notes

- Plugin listens inputs with `[name="s"]` and fires ajax request on changes. Input have to be wrapped inside `<form>` element.
- Plugin will render results inside `.meom-live-search` elements.
- Loader is shown while requesting server for results. Loader uses class name `.meom-live-search__loader`. When loader is visible it gets class name `.meom-live-search__loader--show`

## Filters
Filter for changing the class that gets results.
```
add_filter( 'meom_live_search_results_element', function( $results_element ) {
	return '.meom-live-search';
} );
```

Filter for changing input element we are listening to.
```
add_filter( 'meom_live_search_input', function( $input ) {
	return '[name="s"]';
} );
```

Filter for changing search arguments.
```
add_filter( 'meom_live_search_args', function( $args, $get_params ) {
	return array_merge( $args, array( 'posts_per_page' => 5 ) );
}, 10, 2 );
```
