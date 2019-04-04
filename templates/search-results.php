<div class="meom-live-search">
    <?php
    if ( isset( $search_results ) ) : ?>
        <h2>
            <?php esc_html_e( 'Results:', 'meom-live-search' ); ?>
        </h2>
        <p class="meom-live-search__loader">
            <?php esc_html_e( 'Searching...', 'meom-live-search' ); ?>
        </p>
        <?php if ( ! empty( $search_results ) ) : ?>
        <ul class="meom-live-search__list">
            <?php foreach ( $search_results as $result ) : ?>
                <li class="meom-live-search__item">
                    <a class="meom-live-search__link" href="<?php echo esc_url( get_the_permalink( $result->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $result->ID ) ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else : ?>
            <p class="meom-live-search__error">
                <?php esc_html_e( 'No search results found. :(', 'meom-live-search' ); ?>
            </p>
        <?php endif;
    endif; ?>
</div>
