<?php
	/*
		Template Name: Review Archive Template
	*/
	get_header();
?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<?php if ( have_posts() ) : ?>
				<header class="archive-header">
					<h1 class="archive-title"><?php
						if ( is_day() ) {
							printf( __( 'Daily Archives: %s', 'nvreview' ), '<span>' . get_the_date() . '</span>' );
						} elseif ( is_month() ) {
							printf( __( 'Monthly Archives: %s', 'nvreview' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'nvreview' ) ) . '</span>' );
						} elseif ( is_year() ) {
							printf( __( 'Yearly Archives: %s', 'nvreview' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'nvreview' ) ) . '</span>' );
						} elseif ( is_tag() ) {
							printf( __( 'Tag Archives: %s', 'nvreview' ), '<span>' . single_tag_title( '', false ) . '</span>' );
						} elseif ( is_tax() ) {
							printf( __( 'Reviews Archives: %s', 'nvreview' ), '<span>' . single_cat_title( '', false ) . '</span>' );
						} else {
							_e( 'Reviews Archives', 'nvreview' );
						}
					?></h1>
	
					<?php
						// Show an optional tag description.
						if ( is_tag() ) {
							$tag_description = tag_description();
							if ( $tag_description )
								echo '<div class="archive-meta">' . $tag_description . '</div>';
						}
						// Show an optional category description.
						if ( is_category() ) {
							$category_description = category_description();
							if ( $category_description )
								echo '<div class="archive-meta">' . $category_description . '</div>';
						}
					?>
				</header><!-- .archive-header -->

				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title">
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'nvreview' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h1>
							<p>Categories:</p>
							<ul class="tags blue">
								<?php the_terms( $post->ID, 'restaurant_categories', '<li>', '</li><li>', '</li>' ); ?>
							</ul>
						</header>
						<div class="entry-content clearfix">
							<div class="description">
								<div class="logo">
									<?php the_post_thumbnail(); ?>
								</div><!-- .logo -->
								<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'nvreview' ) ); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'nvreview' ), 'after' => '</div>' ) ); ?>
							</div><!-- .description -->
						</div><!-- .entry-content -->
					</article>
	
				<?php endwhile; // end of the loop. ?>
	
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below" class="navigation" role="navigation">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'nvreview' ); ?></h3>
						<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older reviews', 'nvreview' ) ); ?></div>
						<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer reviews <span class="meta-nav">&rarr;</span>', 'nvreview' ) ); ?></div>
					</nav><!-- #<?php echo 'nav-below'; ?> .navigation -->
				<?php endif; ?>
			<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php // get_sidebar( 'review' ); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
