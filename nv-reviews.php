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
	// ini_set('display_errors', '1');
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
				'supports' => array( 'title', 'author', 'thumbnail', 'comments' ),
				'taxonomies' => array( '' ),
				'menu_icon' => plugins_url( '71.png', __FILE__ ),
				'has_archive' => true,
			)
		);
		
		register_taxonomy(
			'restaurant_categories',
			'review',
			array(
				'labels' => array(
					'name' => 'Restaurant Categories',
					'add_new_item' => 'Add New Restaurant Category',
					'new_item_name' => "New Restaurant Category"
				),
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
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
		$smLink = get_post_meta( $nv_review->ID, 'smLink', true );
		$signatureItems = get_post_meta( $nv_review->ID, 'signatureItems', true );
		$ourThoughts = get_post_meta( $nv_review->ID, 'ourThoughts', true );
		wp_nonce_field( 'nvwd_review_cpt', 'nvwd_review_nonce' );
		echo '<h2>Description</h2>';
		echo wp_editor( $nv_review->post_content, 'content', array( 'textarea_rows' => 8 ) );
		echo '<h2>Location / Contact Information</h2>';
		echo '<div class="form-group">';
		echo '<div class="form-row"><div class="form-field">';
		echo '<label for="nvr_street">Street Address:</label>';
		echo '<input type="text" id="nvr_street" name="street" value="' . $street .'" size="75" />';
		echo '</div></div>';
		echo '</div>';
		echo '<div class="form-group">';
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
		echo '</div>';
		echo '<div class="form-group">';
		echo '<div class="form-row">';
		echo '<div class="form-field">';
		echo '<label for="nvr_phone">Phone:</label>';
		echo '<input type="text" id="nvr_phone" name="phone" value="' . $phone .'" size="15" /> Will be displayed as entered';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<h2>Social Media / Web Sites</h2>';
		echo '<div id="smLinks" class="form-group">';
		if ( is_array( $smLink ) && count( $smLink ) > 0 ) {
			foreach ( $smLink  AS $ind => $link ) {
				echo '<div class="form-row">';
				echo '<div class="form-field">';
				echo '<label>Link Title</label>';
				echo '<input type="text" name="smLink[' . $ind . '][title]" value="' . $link['title'] . '" size="25" />';
				echo '</div>';
				echo '<div class="form-field">';
				echo '<label>Link</label>';
				echo '<input type="text" name="smLink[' . $ind . '][link]" value="' . $link['link'] . '" size="70" />';
				echo '</div>';
				echo '<div class="form-field">';
				echo '<a href="#" class="btnRemove button-secondary" title="Remove this link">X</a>';
				echo '</div>';
				echo '</div>';
			}
		} else {
			echo '<div class="form-row">';
			echo '<div class="form-field">';
			echo '<label>Link Title</label>';
			echo '<input type="text" name="smLink[0][title]" value="" size="25" />';
			echo '</div>';
			echo '<div class="form-field">';
			echo '<label>Link</label>';
			echo '<input type="text" name="smLink[0][link]" value="" size="70" />';
			echo '</div>';
			echo '<div class="form-field">';
			echo '<a href="#" class="btnRemove button-secondary" title="Remove this link">X</a>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		echo '<div class="form-group">';
		echo '<div class="form-row">';
		echo '<div class="form-field">';
		echo '<input type="button" class="button-secondary" id="addSMLink" value=" Add another link " />';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<h2>Signature Items</h2>';
		echo wp_editor( $signatureItems, 'signatureItems', array( 'media_buttons' => false, 'textarea_rows' => 5 ) );
		echo '<h2>Our Thoughts</h2>';
		echo wp_editor( $ourThoughts, 'ourThoughts', array( 'media_buttons' => false, 'textarea_rows' => 5 ) );
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
	
	function nv_reviews_include_template( $template_path ) {
		// check to see if the requested page is a single Custom Post Type
		if ( is_singular( 'review' ) ) {
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( 'single-review.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '/templates/single-review.php';
				wp_enqueue_style( 'nv_admin_style', plugins_url('css/display.css', __FILE__), array( 'twentytwelve-style' ) );
			}
		} else if ( is_post_type_archive( 'review' ) ) {
			// else is the request an archive of the Custom Post Type
			if ( $theme_file = locate_template( array ( 'archive-review.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				//$template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-review.php';
				wp_enqueue_style( 'nv_admin_style', plugins_url('css/display.css', __FILE__), array( 'twentytwelve-style' ) );
			}
		}
		// Return the path of the template to use
		return $template_path;
	}
	
	function nv_add_review_fields( $reviewID, $review ) {
		if ( empty( $_POST ) || !wp_verify_nonce( $_POST['nvwd_review_nonce'], 'nvwd_review_cpt' ) ) {
			echo 'nonce failure';
			exit;
		} else {
			if ( !current_user_can( 'edit_post', $reviewID ) ) {
				return;
			}
		
			if ( 'review' == $review->post_type && !wp_is_post_revision( $reviewID ) && isset( $_POST['street'] ) ) {
				update_post_meta( $reviewID, 'street', esc_attr( $_POST['street'] ) );
				update_post_meta( $reviewID, 'city', esc_attr( $_POST['city'] ) );
				update_post_meta( $reviewID, 'state', esc_attr( $_POST['state'] ) );
				update_post_meta( $reviewID, 'zip', esc_attr( $_POST['zip'] ) );
				update_post_meta( $reviewID, 'phone', esc_attr( $_POST['phone'] ) );
				update_post_meta( $reviewID, 'signatureItems', $_POST['signatureItems'] );
				update_post_meta( $reviewID, 'ourThoughts', $_POST['ourThoughts'] );
				$smLinkArray = array();
				if ( isset( $_POST['smLink'] ) ) {
					foreach ( $_POST['smLink'] AS $link ) {
						$smLinkArray[] = array(
							'title' => esc_attr( $link['title'] ),
							'link' => esc_attr( $link['link'] ),
						);
					}
				}
				update_post_meta( $reviewID, 'smLink', $smLinkArray );
				update_alphabatized_link_list();
			}
		}
	}
	
	function update_alphabatized_link_list() {
		
	}
	
	add_action( 'init', 'create_nv_reviews' );
	add_action( 'after_setup_theme', 'nv_add_thumbs', 11 );
	add_action( 'admin_init', 'nv_review_admin' );
	add_filter( 'enter_title_here', 'change_enter_title_text', 10, 2 );
	add_action( 'right_now_content_table_end', 'nv_reviews_totals_rightnow' );
	add_action( 'save_post', 'nv_add_review_fields', 10, 2 );
	add_filter( 'template_include', 'nv_reviews_include_template', 1 );
?>
