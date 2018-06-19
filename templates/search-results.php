<ul class="meom-live-search__list">
<?php while ( have_posts() ) : the_post(); ?>
	<li class="meom-live-search__item">
		<a class="meom-live-search__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</li>
<?php endwhile; ?>
</ul>
