<?php 

	define('THEMEROOT', get_stylesheet_directory_uri());
	define('STYLE', THEMEROOT. '/assets/css/');
	define('IMAGE', THEMEROOT. '/assets/images/');

	if (!function_exists('thecb_enqueue_scripts')) {
		function thecb_enqueue_scripts()
		{
			wp_enqueue_style('style.css', STYLE.'style.css',null,null,null);
		}

		add_action('wp_enqueue_scripts','thecb_enqueue_scripts');
	}


	if (!function_exists('thecb_theme_setup')) {
		function thecb_theme_setup()
		{
			add_theme_support('custom-logo');
			add_theme_support('title-tags');
			add_theme_support('automatic-feed-links');
			add_theme_support('post-thumbnails');

		}

		add_action('after_setup_theme','thecb_theme_setup');
	}
		add_action( 'add_meta_boxes', 'thecb_toggle_button_meta_boxes' );

		function thecb_toggle_button_meta_boxes() {
		    add_meta_box( 'toggle_button_link', esc_html__( 'Post Link', 'thecb' ), 'thecb_toggle_button_callback_link_save', 'post', 'normal' , 'low' );
		}

		function thecb_toggle_button_callback_link_save( $post_id ) {
			get_template_part('meta_boxes_form/toggle_button_form');
			add_action('save_post','thecb_toggle_button_callback_link_save');
			
		}
		



		function my_meta_init()
		{
			function thecb_toggle_button_callback_link_update( $post_id ) {
			    
			     global $post;

			      if ( ! isset( $_POST['mytheme_control_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['mytheme_control_meta_box_nonce'] ), 'mytheme_control_meta_box' ) ) { // Input var okay.
						return $post_id;
					}

					if ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) { // Input var okay.
						if ( ! current_user_can( 'edit_page', $post_id ) ) {
							return $post_id;
						}
					} else {
						if ( ! current_user_can( 'edit_post', $post_id ) ) {
							return $post_id;
						}
					}

					if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
						return $post_id;
					}

		      update_post_meta($post->ID,'toggle_resource_link_btn',(isset($_POST["toggle_resource_link_btn"])) ? 1 : 0);
			}
			
			add_action('save_post','thecb_toggle_button_callback_link_update');
		}

		add_action('admin_init','my_meta_init');


		include('inc/category_image.php');


		function edit_wp_menu_thecb()
		{
			global $menu;
			global $submenu;
			// print_r($submenu);
			$menu[5][0] = 'Articles';
			$submenu['edit.php'][5][0] = 'All Articles';
			$submenu['edit.php'][10][0] = 'Add new Article';
			$submenu['edit.php'][15][0] = 'Article Categories';
			$submenu['edit.php'][16][0] = 'Article Tags';
		}

		function change_post_labels_thecb()
		{
			global $wp_post_types;		
			// print_r($wp_post_types);
			$labels = $wp_post_types['post']->labels;
			$labels->name = 'Articles';
	        $labels->singular_name = 'Article';
	        $labels->add_new = 'Add New Article';
	        $labels->add_new_item = 'Add New Article';
	        $labels->edit_item = 'Edit Article';
	        $labels->new_item = 'New Article';
	        $labels->view_item = 'View Article';
	        $labels->view_items = 'View Articles';
	        $labels->search_items = 'Search Articles';
	        $labels->not_found = 'No Articles found.';
	        $labels->not_found_in_trash = 'No Articles found in Trash.';
	        $labels->all_items = 'All Articles';
	        $labels->archives = 'Article Archives';
	        $labels->attributes = 'Article Attributes';
	        $labels->insert_into_item = 'Insert into Article';
	        $labels->uploaded_to_this_item = 'Uploaded to this Article';
	        $labels->filter_items_list = 'Filter Articles list';
	        $labels->items_list_navigation = 'Articles list navigation';
	        $labels->items_list = 'Articles list';
	        $labels->menu_name = 'Articles';
	        $labels->name_admin_bar = 'Article';
		}
		add_action( 'init', 'change_post_labels_thecb' );
		add_action( 'admin_menu', 'edit_wp_menu_thecb' );






?>