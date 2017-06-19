<?php

    function modify_list_row_actions( $actions, $post ) {
        $render = Render_Helper::get_instance();
	$actions = array_merge( $actions, [
		'cache_purge' => $render->delete_current_page( $post )
	]); 
        return $actions;
    }
    add_filter( 'post_row_actions', 'modify_list_row_actions', 10, 2 );
    add_filter( 'page_row_actions', 'modify_list_row_actions', 10 ,2 );
    
    function delete_current_page_cache() {
      if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
	  if ( $_GET["post"] ) {
	      $permalink = get_permalink( $_GET['post'] );
	      $path = get_option( 'nginx_cache_sniper_path' );
	      $filesystem = Filesystem_Helper::get_instance();
              $cache_path = $filesystem->get_nginx_cache_path( $path, $permalink );
	      $directory_deleted = $filesystem->delete( $cache_path );
	      die(json_encode([$directory_deleted]));
	  }    
      }
    }
    add_action( 'wp_ajax_delete_current_page_cache', 'delete_current_page_cache' );


