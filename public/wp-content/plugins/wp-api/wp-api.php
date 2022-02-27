<?php 
  /*
   Plugin Name: THECB Core
   Plugin URI: https://thecb-wp.agencypartner.com/
   description: a plugin for thecb core
   Version: 1.2
   Author: Muhammad Raza
   Author URI: http://muhammad-raza.com
   License: GPL2
   */

// Show GET All Categories API Start
function wl_get_all_categories()
{
	global $wpdb;

	$categories_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' AND term_id != 1" );

	$sorting = $_GET['sorting'] ?? '';
	$per_page = $_GET['per_page'] ?? 9999;
	$paged = $_GET['current_page'] ?? 1;
	$offset = ($per_page * $paged) - $per_page;

	$categories = get_categories( array(
		'type'    => 'post',
	    'orderby' => 'name',		
	    'order'   => $sorting,
	    'hide_empty' => false,
	    'exclude' => [1],
	    'number'  => $per_page,
	    'posts_per_page' => $per_page,
		'paged' => $paged,
		'offset' => $offset
	) );

	$data = [];
	$i = 0;

	foreach($categories as $key => $parent) {
		if($parent->parent == 0) {
			$data['items'][$i]['id'] =  $parent->term_id;  
			$data['items'][$i]['parent_id'] =  $parent->parent; 
			$data['items'][$i]['name'] =  $parent->name;   
			$data['items'][$i]['slug'] =  $parent->slug;
			$term = get_term_meta($parent->term_id,'category-image-id');
			$image_attributes = (isset($term[0])) ? wp_get_attachment_image_src( $term[0] ) : '' ;  
			$data['items'][$i]['image'] =  (isset($image_attributes[0]) && $image_attributes[0]) ? $image_attributes[0] : 'No image found' ;
			$j = 0;
			foreach($categories as $key => $child) {			
				if($data['items'][$i]['id'] === $child->parent) {
					$data['items'][$i]['child'][$j]['id'] = $child->term_id;  
					$data['items'][$i]['child'][$j]['parent_id'] = $child->parent;  
					$data['items'][$i]['child'][$j]['name'] =  $child->name;  
					$data['items'][$i]['child'][$j]['slug'] =  $child->slug;
					$term = get_term_meta($child->term_id,'category-image-id');
					$image_attributes = (isset($term[0])) ? wp_get_attachment_image_src( $term[0] ) : '' ;  
					$data['items'][$i]['child'][$j]['image'] =  (isset($image_attributes[0]) && $image_attributes[0]) ? $image_attributes[0] : 'No image found' ; 
					$j++;

					$k=0;
					foreach($categories as $grandchildkey => $grandchild) {
						if($child->term_id == $grandchild->parent) {
							$data['items'][$i]['child'][$j]['grandChild'][$k]['id'] = $grandchild->term_id;  
							$data['items'][$i]['child'][$j]['grandChild'][$k]['parent_id'] = $grandchild->parent;  
							$data['items'][$i]['child'][$j]['grandChild'][$k]['name'] =  $grandchild->name;  
							$data['items'][$i]['child'][$j]['grandChild'][$k]['slug'] =  $grandchild->slug;
							$term = get_term_meta($grandchild->term_id,'category-image-id');
							$image_attributes = (isset($term[0])) ? wp_get_attachment_image_src( $term[0] ) : '' ;  
							$data['items'][$i]['child'][$j]['grandChild'][$k]['image'] =  (isset($image_attributes[0]) && $image_attributes[0]) ? $image_attributes[0] : 'No image found' ; 
							$k++;
						}
					}
				}
			}	
		$i++;
		}
	}	
	
	$data['per_page'] = count($categories);
	$data['total_categories'] = $categories_count;
	$data['current_page'] = $paged;

	if(!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	} else {
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}	
}

add_action('rest_api_init', function(){
	register_rest_route('wl/v1','get_all_categories',[
		'methods' => 'GET',
		'callback' => 'wl_get_all_categories',
	]);
});
// Show GET All Categories API END


// Show All Categories API Start
function wl_all_categories()
{
	global $wpdb;

	$categories_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' AND term_id != 1" );

	$sorting = $_GET['sorting'] ?? '';
	$per_page = $_GET['per_page'] ?? 9999;
	$paged = $_GET['current_page'] ?? 1;
	$offset = ($per_page * $paged) - $per_page;

	$categories = get_categories( array(
		'type'    => 'post',
	    'orderby' => 'name',		
	    'order'   => $sorting,
	    'hide_empty' => false,
	    'exclude' => [1],
	    'number'  => $per_page,
	    'posts_per_page' => $per_page,
		'paged' => $paged,
		'offset' => $offset
	) );

	$data = [];
	$i = 0;

	foreach ($categories as $category) {		 
		$data['items'][$i]['id'] = $category->term_id;
		$data['items'][$i]['name'] = $category->name;
		$data['items'][$i]['parent_id'] = $category->parent;
		$data['items'][$i]['slug'] = $category->category_nicename;
		$term = get_term_meta($category->term_id,'category-image-id');
		$image_attributes = (isset($term[0])) ? wp_get_attachment_image_src( $term[0] ) : '' ;
		$data['items'][$i]['image'] = (isset($image_attributes[0]) && $image_attributes[0]) ? $image_attributes[0] : 'No image found' ;
		$i++;
	}

	$data['per_page'] = count($categories);
	$data['total_categories'] = $categories_count;
	$data['current_page'] = $paged;

	if(!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	} else {
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}
}

add_action('rest_api_init', function(){
	register_rest_route('wl/v1','all_categories',[
		'methods' => 'GET',
		'callback' => 'wl_all_categories',
	]);
});
// Show All Categories API END


// Show All Parent Catogories API Start
function wl_all_parent_categories()
{
	global $wpdb;

	$categories_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE parent = 0 AND taxonomy = 'category' AND term_id != 1" );

	$sorting = $_GET['sorting'] ?? '';
	$per_page = $_GET['per_page'] ?? 8;
	$paged = $_GET['current_page'] ?? 1;
	$offset = ($per_page * $paged) - $per_page;

	$categories = get_categories( array(
		'type'    => 'post',
	    'orderby' => 'name',
		'number'  => $per_page,
	    'order'   => $sorting,
	    'hide_empty' => false,
	    'exclude' => [1],
	    'parent' => 0,
		'posts_per_page' => $per_page,
		'paged' => $paged,
		'offset' => $offset
	) );

	$data = [];

	foreach ($categories as $key => $category) {
		$data['items'][$key]['id'] = $category->cat_ID;
		$data['items'][$key]['name'] = $category->name;		
		$data['items'][$key]['parent'] = $category->parent;		
		$data['items'][$key]['slug'] = $category->category_nicename;
		$term = get_term_meta($category->term_id,'category-image-id');
		$image_attributes =  (isset($term[0])) ? wp_get_attachment_image_src( $term[0] ) : '';
		$data['items'][$key]['image'] = isset($image_attributes[0]) && ($image_attributes[0]) ? $image_attributes[0] : 'No image found' ;
	}

	$data['per_page'] = count($categories);
	$data['total_categories'] = $categories_count;
	$data['current_page'] = $paged;

	if( !empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	} else {
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}
}

add_action('rest_api_init', function(){
	register_rest_route('wl/v1','all_parent_categories',[
		'methods' => 'GET',
		'callback' => 'wl_all_parent_categories',
	]);
});
// Show All Parent Categories API END

// Show All Filtered Posts API Start
function wp_post_by_category($request)
{

	$orderByTitleDate = isset($orderByTitleDate) ? $orderByTitleDate : 'title';
	$category_id = $_GET['category_id'] ?? '';
	$sorting = $_GET['sorting'] ?? 'ASC';
	$orderby = $_GET['orderby'] ?? '';
	$search = $_GET['search'] ?? '';
	$category_id = json_decode($category_id);
	$paged = $_GET['current_page'] ?? 1;
	$limit = isset($_GET['per_page']) ? $_GET['per_page'] : 4;	
	
	$args = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
		'search_post_title' => $search,
	    'posts_per_page' => $limit,
		'orderby' => $orderby,
	    'order' => $sorting,
		'category__in' => $category_id,
		'paged' => $paged
	);

	if($orderby == 'date')
	{
		$args['orderby'] = 'ID';
		$args['order'] = ($sorting == 'DESC') ? 'ASC' : 'DESC';	
	}
	

	if($orderby == 'link')
	{
		$args['meta_key'] = 'toggle_resource_link_btn';
		$args['meta_value'] = '1';
		$args['orderby'] = 'title';
		$args['order'] = $sorting;
	}
	else if($orderby == 'articles')
	{
		$args['meta_key'] = 'toggle_resource_link_btn';
		$args['meta_value'] = '0';
		$args['meta_compare'] = '=';
		$args['orderby'] = 'title';
		$args['order'] = $sorting;
	}

	add_filter( 'posts_where', 'title_filter', 10, 2 );	
	$posts = new WP_Query( $args );	
	remove_filter( 'posts_where', 'title_filter', 10, 2 );

	$data = [];
	$i = 0;

	foreach ($posts->posts as $key => $post) {
		$data['items'][$i]['id'] = $post->ID;
		$data['items'][$i]['post_date'] = $post->post_date;
		$data['items'][$i]['post_date_gmt'] = $post->post_date_gmt;
		$data['items'][$i]['post_content'] = $post->post_content;
		$data['items'][$i]['post_title'] = $post->post_title;
		$data['items'][$i]['post_excerpt'] = $post->post_excerpt;
		$data['items'][$i]['post_slug'] = $post->post_name;
		$data['items'][$i]['category_name'] = get_the_category( $post->ID )[0]->name;
		$data['items'][$i]['post_image']['large'] = (get_the_post_thumbnail_url($post->ID,'large')) ? get_the_post_thumbnail_url($post->ID,'large') : 'No image found' ;
		$data['items'][$i]['post_image']['medium'] = (get_the_post_thumbnail_url($post->ID,'medium')) ? get_the_post_thumbnail_url($post->ID,'medium') : 'No image found' ;
		$data['items'][$i]['post_image']['thumbnail'] = (get_the_post_thumbnail_url($post->ID,'thumbnail')) ? get_the_post_thumbnail_url($post->ID,'thumbnail') : 'No image found' ;
		$data['items'][$i]['post_image']['full'] = (get_the_post_thumbnail_url($post->ID,'full')) ? get_the_post_thumbnail_url($post->ID,'full') : 'No image found' ;
		$meta = get_post_meta($post->ID, 'toggle_resource_link_btn', true );
		
		$data['items'][$i]['post_link'] = ($meta) ? $meta : '0' ;
		$i++;		
	}
	$data['per_page'] = $posts->post_count;
	$data['total_posts'] = $posts->found_posts;
	$data['current_page'] = $paged;

	if (!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	}
	else
	{
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}

	wp_reset_query();

}

function title_filter( $where, $wp_query ){
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_post_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}

add_action( 'rest_api_init', function () {
  // register a new endpoint 
  register_rest_route( 'wl/v1', '/posts/categories', array(
    'methods' => 'GET',
    'callback' => 'wp_post_by_category',
  ) );
} );

// Show All Filtered Posts API END

// Show Single Post API By Slug Start
function wp_get_post_by_slug($request)
{

	$slug = $_GET['slug'] ?? '';
	
	$args = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
		'name' => $slug,
		'meta_query' => [
			'key' => '_thumbnail_id'
		]
	);

	$posts = new WP_Query( $args );

	$data = [];
	$i = 0;

	foreach ($posts->posts as $key => $post) {
		$data['items'][$i]['id'] = $post->ID;
		$data['items'][$i]['post_date'] = $post->post_date;
		$data['items'][$i]['post_date_gmt'] = $post->post_date_gmt;
		$data['items'][$i]['post_content'] = $post->post_content;
		$data['items'][$i]['post_title'] = $post->post_title;
		$data['items'][$i]['post_excerpt'] = $post->post_excerpt;
		$data['items'][$i]['post_slug'] = $post->post_name;
		$data['items'][$i]['category_name'] = get_the_category( $post->ID )[0]->name;
		$data['items'][$i]['post_image']['large'] = (get_the_post_thumbnail_url($post->ID,'large')) ? get_the_post_thumbnail_url($post->ID,'large') : 'No image found' ;
		$data['items'][$i]['post_image']['medium'] = (get_the_post_thumbnail_url($post->ID,'medium')) ? get_the_post_thumbnail_url($post->ID,'medium') : 'No image found' ;
		$data['items'][$i]['post_image']['thumbnail'] = (get_the_post_thumbnail_url($post->ID,'thumbnail')) ? get_the_post_thumbnail_url($post->ID,'thumbnail') : 'No image found' ;
		$data['items'][$i]['post_image']['full'] = (get_the_post_thumbnail_url($post->ID,'full')) ? get_the_post_thumbnail_url($post->ID,'full') : 'No image found' ;
		$meta = get_post_meta($post->ID, 'toggle_resource_link_btn', true );
		$data['items'][$i]['post_link'] = ($meta) ? $meta : '0' ;
		$i++;
	}

	if (!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	}
	else
	{
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}

	wp_reset_query();

}


add_action( 'rest_api_init', function () {
  // register a new endpoint 
  register_rest_route( 'wl/v1', '/posts', array(
    'methods' => 'GET',
    'callback' => 'wp_get_post_by_slug',
  ) );
} );

// Show Single Post API By Slug END


// Show Related Post API Start
function wp_get_related_post_by_id($request)
{
	$post_id = $_GET['post_id'] ?? '';
	$per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 2;
	
	$args = array(
	    'post_type' => 'post',
		'category__in' => wp_get_post_categories($post_id),
		'post__not_in' => array($post_id),
		'posts_per_page' => $per_page,
		'order' => 'rand'
	);

	$posts = new WP_Query( $args );

	$data = [];
	$i = 0;

	foreach ($posts->posts as $key => $post) {
		$data['items'][$i]['id'] = $post->ID;
		$data['items'][$i]['post_date'] = $post->post_date;
		$data['items'][$i]['post_date_gmt'] = $post->post_date_gmt;
		$data['items'][$i]['post_content'] = $post->post_content;
		$data['items'][$i]['post_title'] = $post->post_title;
		$data['items'][$i]['post_excerpt'] = $post->post_excerpt;
		$data['items'][$i]['post_slug'] = $post->post_name;
		$data['items'][$i]['category_name'] = get_the_category( $post->ID )[0]->name;
		$data['items'][$i]['post_image']['large'] = (get_the_post_thumbnail_url($post->ID,'large')) ? get_the_post_thumbnail_url($post->ID,'large') : 'No image found' ;
		$data['items'][$i]['post_image']['medium'] = (get_the_post_thumbnail_url($post->ID,'medium')) ? get_the_post_thumbnail_url($post->ID,'medium') : 'No image found' ;
		$data['items'][$i]['post_image']['thumbnail'] = (get_the_post_thumbnail_url($post->ID,'thumbnail')) ? get_the_post_thumbnail_url($post->ID,'thumbnail') : 'No image found' ;
		$data['items'][$i]['post_image']['full'] = (get_the_post_thumbnail_url($post->ID,'full')) ? get_the_post_thumbnail_url($post->ID,'full') : 'No image found' ;
		$meta = get_post_meta($post->ID, 'toggle_resource_link_btn', true );
		$data['items'][$i]['post_link'] = ($meta) ? $meta : '0' ;
		$i++;
	}

	if (!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	}
	else
	{
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}

	wp_reset_query();

}


add_action( 'rest_api_init', function () {
  // register a new endpoint 
  register_rest_route( 'wl/v1', '/related/post', array(
    'methods' => 'GET',
    'callback' => 'wp_get_related_post_by_id',
  ) );
} );

// Show Related Post API END

// Show Single Post API By ID Start
function wp_get_post_by_id($request)
{
	$post_id = $_GET['post_id'] ?? '';
	$post_id = json_decode($post_id);
	$per_page = $_GET['per_page'] ?? 6;
	$paged = $_GET['current_page'] ?? 1;	
	
	$args = array(
	    'post_type' => 'post',
	    'post_status' => 'publish',
		'posts_per_page' => $per_page,
		'post__in' => $post_id,
		'paged' => $paged,
		'meta_query' => [
			'key' => '_thumbnail_id'
		]
	);

	$posts = new WP_Query( $args );

	$data = [];
	$i = 0;

	foreach ($posts->posts as $key => $post) {
		$data['items'][$i]['id'] = $post->ID;
		$data['items'][$i]['post_date'] = $post->post_date;
		$data['items'][$i]['post_date_gmt'] = $post->post_date_gmt;
		$data['items'][$i]['post_content'] = $post->post_content;
		$data['items'][$i]['post_title'] = $post->post_title;
		$data['items'][$i]['post_excerpt'] = $post->post_excerpt;
		$data['items'][$i]['post_slug'] = $post->post_name;
		$data['items'][$i]['category_name'] = get_the_category( $post->ID )[0]->name;
		$data['items'][$i]['post_image']['large'] = (get_the_post_thumbnail_url($post->ID,'large')) ? get_the_post_thumbnail_url($post->ID,'large') : 'No image found' ;
		$data['items'][$i]['post_image']['medium'] = (get_the_post_thumbnail_url($post->ID,'medium')) ? get_the_post_thumbnail_url($post->ID,'medium') : 'No image found' ;
		$data['items'][$i]['post_image']['thumbnail'] = (get_the_post_thumbnail_url($post->ID,'thumbnail')) ? get_the_post_thumbnail_url($post->ID,'thumbnail') : 'No image found' ;
		$data['items'][$i]['post_image']['full'] = (get_the_post_thumbnail_url($post->ID,'full')) ? get_the_post_thumbnail_url($post->ID,'full') : 'No image found' ;
		$meta = get_post_meta($post->ID, 'toggle_resource_link_btn', true );
		$data['items'][$i]['post_link'] = ($meta) ? $meta : '0' ;
		$i++;
	}
	$data['per_page'] = $posts->post_count;
	$data['total_posts'] = $posts->found_posts;
	$data['current_page'] = $paged;

	if (!empty($data)) {
		return [
			'status' => 200,
			'message' => 'Successfully Fetch',
			'data' => $data
		];
	}
	else
	{
		return [
			'status' => 404,
			'message' => 'NOT FOUND',
			'data' => []
		];
	}

	wp_reset_query();

}


add_action( 'rest_api_init', function () {
  // register a new endpoint 
  register_rest_route( 'wl/v1', '/post_by_id', array(
    'methods' => 'GET',
    'callback' => 'wp_get_post_by_id',
  ) );
} );

// Show Single Post API By ID END







?>