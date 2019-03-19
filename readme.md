# MEOM Live Search

# Usage

1. Add `<div class="meom-live-search"></div>` where you'd like to list live search results.

2. For custom results, add a following file to your theme folder: `/meom-live-search/search-results.php`.

Content for live search result could be like:

```
<ul>
<?php while ( have_posts() ) : the_post() ?>
	<li>
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</li>
<?php endwhile; ?>
</ul>
```

# Notes

- Plugins listens inputs with `[name="s"]` and fires ajax request on changes. Input have to be wrapped inside `form` element.
- It will render results inside `.meom-live-search` elements.
- Spinner is shown while requesting sever for resulst. Spinner uses classname ``.

# Filters
```
add_filter( 'meom_live_search_results_element', function( $results_element ) {
	return '.meom-live-search';
} );
```
```
add_filter( 'meom_live_search_input', function( $input ) {
	return '[name="s"]';
} );
```
```
add_filter( 'meom_live_search_args', function( $args, $get_params ) {
	return array_merge( $args, array( 'posts_per_page' => 5 ) );
} );
```
