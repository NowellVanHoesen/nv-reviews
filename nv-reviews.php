<?php
	/*
		Plugin Name: NV Reviews
		Plugin URI: http://nvwebdev.com/
		Description: A custom post type for restaurant reviews
		Version: 0.1
		Author: Nowell VanHoesen
		Author URI: http://nvwebdev.com/
		License: GPL2
	*/
	
	require( 'inc/nv-helpers.php' );
	
	function create_nv_reviews() {
		register_post_type( 'review',
			array(
				'labels' => array(
					'name' => 'Restaurant Reviews',
					'singular_name' => 'Restaurant Review',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Restaurant Review',
					'edit' => 'Edit',
					'edit_item' => 'Edit Restaurant Review',
					'new_item' => 'New Restaurant Review',
					'view' => 'View',
					'view_item' => 'View Restaurant Review',
					'search_items' => 'Search Restaurant Reviews',
					'not_found' => 'No Restaurant Reviews found',
					'not_found_in_trash' => 'No Restaurant Reviews found in Trash',
				),
				'public' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
				'taxonomies' => array( '' ),
				'has_archive' => true,
			)
		);
	}
	
	/**
	 * Make sure post-thumbnails are supported for this Custom Post Type
	 */
	
	function nv_add_thumbs() {
		$thumbSupport = get_theme_support( 'post-thumbnails' );
		if ( false === $thumbSupport ) {
			add_theme_support( 'post-thumbnails', array( 'review' ) );
		} else if ( is_array( $thumbSupport ) ) {
			$thumbSupport[0][] = 'review';
			add_theme_support( 'post-thumbnails', $thumbSupport[0] );
		}
	}
	
	/**
	 * add meta box for extra data
	 */
	function nv_review_admin() {
		wp_enqueue_style( 'nv_admin_style', plugins_url('css/admin.css', __FILE__) );
		wp_enqueue_script( 'nv_admin_script', plugins_url('js/nv-review-admin.js', __FILE__) );
		add_meta_box( 'nv_review_meta_box',
			'Restaurant Review Details',
			'display_nv_review_meta_box',
			'review', 'normal', 'high'
		);
	}
	
	/**
	 * admin form functionality
	 */
	function display_nv_review_meta_box( $nv_review ) {
		$street = esc_html( get_post_meta( $nv_review->ID, 'street', true ) );
		$city = esc_html( get_post_meta( $nv_review->ID, 'city', true ) );
		$state = esc_html( get_post_meta( $nv_review->ID, 'state', true ) );
		$zip = esc_html( get_post_meta( $nv_review->ID, 'zip', true ) );
		$phone = esc_html( get_post_meta( $nv_review->ID, 'phone', true ) );
		echo '<h2>Location / Contact</h2>';
		echo '<div class="form-row"><div class="form-field">';
		echo '<label for="nvr_street">Street Address:</label>';
		echo '<input type="text" id="nvr_street" name="street" value="' . $street .'" size="75" />';
		echo '</div></div>';
		echo '<div class="form-row">';
		echo '<div class="form-field">';
		echo '<label for="nvr_city">City:</label>';
		echo '<input type="text" id="nvr_city" name="city" value="' . $city .'" size="30" />';
		echo '</div>';
		echo '<div class="form-field">';
		echo '<label for="nvr_state">State:</label>';
		echo nv_get_states_dd( 'nvr_state', 'state', $state );
		echo '</div>';
		echo '<div class="form-field">';
		echo '<label for="nvr_zip">Zip:</label>';
		echo '<input type="text" id="nvr_zip" name="zip" value="' . $zip .'" size="10" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-row">';
		echo '<div class="form-field">';
		echo '<label for="nvr_phone">Phone:</label>';
		echo '<input type="text" id="nvr_phone" name="phone" value="' . $phone .'" size="15" /> Will be displayed as entered';
		echo '</div>';
		echo '</div>';
		echo '<h2>Social Media / Web Sites</h2>';
		echo '<div class="clear-fix"></div>';
	}
	
	/**
	 * Change the title placeholder text
	 */
	function change_enter_title_text( $text, $post ) {
		if ( 'review' == get_post_type( $post ) ) {
			return "Enter Restaurant Name Here";
		}
	}

	/**
	 * Add Reviw count to the Right Now admin panel
	 */
	function nv_reviews_totals_rightnow() {    
		$post_types = get_post_types( array( '_builtin' => false ), 'objects' ); 
		if ( count( $post_types ) > 0 ) {
			foreach( $post_types as $pt => $args ) {
				$url = 'edit.php?post_type='.$pt;
				echo '<tr><td class="b"><a href="'. $url .'">'. wp_count_posts( $pt )->publish .'</a></td><td class="t"><a href="'. $url .'">'. $args->labels->name .'</a></td></tr>';
			}
		}
	}

	add_action( 'init', 'create_nv_reviews' );
	add_action( 'after_setup_theme', 'nv_add_thumbs', 11 );
	add_action( 'admin_init', 'nv_review_admin' );
	add_filter( 'enter_title_here', 'change_enter_title_text', 10, 2 );
	add_action( 'right_now_content_table_end', 'nv_reviews_totals_rightnow' );

?>

