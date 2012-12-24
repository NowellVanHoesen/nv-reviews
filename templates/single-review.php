<?php
	/*
		Template Name: Review Single Template
	*/
	get_header();
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'nvreview' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span>', 'nvreview' ) . ' %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title ' . __( '<span class="meta-nav">&rarr;</span>', 'nvreview' ) ); ?></span>
				</nav><!-- .nav-single -->
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<p>
							<?php the_terms( $post->ID, 'restaurant_categories', 'Categories: ', ' | ', '' ); ?>
						</p>
						<?php if ( comments_open() ) : ?>
							<div class="comments-link">
								<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'nvreview' ) . '</span>', __( '1 Reply', 'nvreview' ), __( '% Replies', 'nvreview' ) ); ?>
							</div><!-- .comments-link -->
						<?php endif; // comments_open() ?>
					</header>
					<div class="entry-content">
						<div class="logo clearfix">
							<?php the_post_thumbnail(); ?>
						</div><!-- .logo -->
						<div class="description clearfix">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'nvreview' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'nvreview' ), 'after' => '</div>' ) ); ?>
						</div><!-- .description -->
							<?php
								$street = get_post_meta( $post->ID, 'street', true );
								$city = get_post_meta( $post->ID, 'city', true );
								$state = get_post_meta( $post->ID, 'state', true );
								$zip = get_post_meta( $post->ID, 'zip', true );
								$phone = get_post_meta( $post->ID, 'phone', true );
								$smLink = get_post_meta( $post->ID, 'smLink', true );
								$signatureItems = get_post_meta( $post->ID, 'signatureItems', true );
								$ourThoughts = get_post_meta( $post->ID, 'ourThoughts', true );
								$addrLine2 = $city . ', ' . $state . ' ' . $zip;
								$mapShortcode = '[tb_google_map zoom=15 width=65% height=300px address="' . $street . ' ' . $addrLine2 . '"]';
								$test = do_shortcode( $mapShortcode );
								if ( $test !== $mapShortcode ) {
									echo $test;
								}
							?>
						<div class="address clearfix">
							<h2><?php _e( 'Contact Info', 'nvreview' ); ?></h2>
							<h4><?php _e( 'Address / Phone', 'nvreview' ); ?></h4>
							<p>
								<?php _e( $street, 'nvreview' ); ?><br /><?php _e( $addrLine2, 'nvreview' ); ?>
							</p>
							<p><?php echo $phone; ?></p>
							<?php if( count( $smLink ) > 0 ) : ?>
								<h4><?php _e( 'Web / Social Media', 'nvreview' ); ?></h4>
								<ul>
									<?php
										foreach ($smLink AS $link ) {
											echo '<li><a href="' . $link['link'] . '" target="_blank">' . __( $link['title'], 'nvreview' ) . '</a></li>';
										}
									?>
								</ul>
							<?php endif; ?>
						</div><!-- .address -->
						<?php if( strlen( $signatureItems ) > 0 ) : ?>
						<div class="signatureItems">
							<h2><?php _e( 'Signature Items', 'nvreview' ); ?></h2>
							<?php _e( $signatureItems, 'nvreview' )?>
						</div><!-- .signatureItems -->
						<?php endif; ?>
						<?php if( strlen( $ourThoughts ) > 0 ) : ?>
						<div class="ourThoughts">
							<h2><?php _e( 'Our Thoughts', 'nvreview' ); ?></h2>
							<?php _e( $ourThoughts, 'nvreview' )?>
						</div><!-- .ourThoughts -->
						<?php endif; ?>
						<div class="gallery">
							<h2><?php _e( 'Gallery', 'nvreview' ); ?></h2>
							<?php
								$galShortcode = '[gallery orderby="menu_order" exclude="' . get_post_thumbnail_id( $post->ID ) . '"  columns="4"]';
								echo do_shortcode( $galShortcode );
							?>
						</div><!-- .gallery -->
					</div><!-- .entry-content -->
					<footer class="entry-meta">
						<?php twentytwelve_entry_meta(); ?>
						<?php edit_post_link( __( 'Edit', 'nvreview' ), '<span class="edit-link">', '</span>' ); ?>
						<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
							<div id="author-info">
								<div id="author-avatar">
									<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentytwelve_author_bio_avatar_size', 68 ) ); ?>
								</div><!-- #author-avatar -->
								<div id="author-description">
									<h2><?php printf( __( 'About %s', 'nvreview' ), get_the_author() ); ?></h2>
									<p><?php the_author_meta( 'description' ); ?></p>
									<div id="author-link">
										<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
											<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'nvreview' ), get_the_author() ); ?>
										</a>
									</div><!-- #author-link	-->
								</div><!-- #author-description -->
							</div><!-- #author-info -->
						<?php endif; ?>
					</footer><!-- .entry-meta -->
				</article>

				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'nvreview' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span>', 'nvreview' ) . ' %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title ' . __( '<span class="meta-nav">&rarr;</span>', 'nvreview' ) ); ?></span>
				</nav><!-- .nav-single -->

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php // get_sidebar( 'review' ); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
