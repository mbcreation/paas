<?php

/*

Plugin Name:  Post Type as a Plugin
Description:  Post Type Management
Version:      0.1

*/

if( !class_exists( 'my_post_type_as_a_plugin' ) ) {


			class my_post_type_as_a_plugin {
					
					protected $_post_type = 'my_post_type';
					protected $_post_type_icon = 'dashicons-businessman';
					protected $_post_type_category = 'my_custom_category';
					protected $_post_type_tag = 'my_custom_tag';
					protected $_plugin_path;


					function __construct(){

						$this->_plugin_path = dirname(__FILE__);
						
						register_activation_hook( __FILE__, array( &$this, 'install' ) );

						add_action('plugins_loaded', array(&$this, 'hooks' ) );


					}

					public function install(){

						$this->register_post_type();
						$this->register_tag();
						$this->register_category();

						flush_rewrite_rules(false);

					}

					public function hooks(){

						add_action( 'init', array(&$this, 'register_post_type' )  );
						add_action( 'init', array(&$this, 'register_tag' )  );
						add_action( 'init', array(&$this, 'register_category' )  );

						add_filter( 'template_include', array(&$this, 'template_include' ) );
					}
					
					public function register_post_type(){


						register_post_type( $this->_post_type , 
							
								array( 'labels' => array(
									'name' => __( 'Custom Types' ), /* This is the Title of the Group */
									'singular_name' => __( 'Custom Post' ), /* This is the individual type */
									'all_items' => __( 'All Custom Posts' ), /* the all items menu item */
									'add_new' => __( 'Add New' ), /* The add new menu item */
									'add_new_item' => __( 'Add New Custom Type' ), /* Add New Display Title */
									'edit' => __( 'Edit' ), /* Edit Dialog */
									'edit_item' => __( 'Edit Post Types' ), /* Edit Display Title */
									'new_item' => __( 'New Post Type' ), /* New Display Title */
									'view_item' => __( 'View Post Type' ), /* View Display Title */
									'search_items' => __( 'Search Post Type' ), /* Search Custom Type Title */
									'not_found' =>  __( 'Nothing found in the Database.' ), /* This displays if there are no entries yet */
									'not_found_in_trash' => __( 'Nothing found in Trash' ), /* This displays if there is nothing in the trash */
									'parent_item_colon' => ''
									), /* end of arrays */
									'description' => __( 'This is the example custom post type' ), /* Custom Type Description */
									'public' => true,
									'publicly_queryable' => true,
									'exclude_from_search' => false,
									'show_ui' => true,
									'query_var' => true,
									'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
									'menu_icon' => $this->_post_type_icon, /* the icon for the custom post type menu */
									'rewrite'	=> array( 'slug' => $this->_post_type , 'with_front' => false ), /* you can specify its url slug */
									'has_archive' => $this->_post_type , /* you can rename the slug here */
									'capability_type' => 'post',
									'hierarchical' => false,
									/* the next one is important, it tells what's enabled in the post editor */
									'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky', 'page-attributes')
								) /* end of options */
							); /* end of register post type */
					
					}


					public function register_category(){
							
							register_taxonomy( $this->_post_type_category,

								array($this->_post_type),
								 /* if you change the name of register_post_type( 'custom_type', then you have to change this */
								array('hierarchical' => true,   /* if this is true, it acts like categories */

									'labels' => array(
										'name' => __( 'Custom Categories' ), /* name of the custom taxonomy */
										'singular_name' => __( 'Custom Category' ), /* single taxonomy name */
										'search_items' =>  __( 'Search Custom Categories' ), /* search title for taxomony */
										'all_items' => __( 'All Custom Categories' ), /* all title for taxonomies */
										'parent_item' => __( 'Parent Custom Category' ), /* parent title for taxonomy */
										'parent_item_colon' => __( 'Parent Custom Category:' ), /* parent taxonomy title */
										'edit_item' => __( 'Edit Custom Category' ), /* edit custom taxonomy title */
										'update_item' => __( 'Update Custom Category' ), /* update title for taxonomy */
										'add_new_item' => __( 'Add New Custom Category' ), /* add new title for taxonomy */
										'new_item_name' => __( 'New Custom Category Name' ) /* name title for taxonomy */
									),

									'show_admin_column' => true,
									'show_ui' => true,
									'query_var' => true,
									'rewrite' => array( 'slug' => 'custom-slug' ),
								)
					 		);
					
					 }

					 public function register_tag(){

							register_taxonomy( $this->_post_type_tag ,

								array($this->_post_type), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
								
								array('hierarchical' => false,    /* if this is false, it acts like tags */
									
									'labels' => array(
										'name' => __( 'Custom Tags' ), /* name of the custom taxonomy */
										'singular_name' => __( 'Custom Tag' ), /* single taxonomy name */
										'search_items' =>  __( 'Search Custom Tags' ), /* search title for taxomony */
										'all_items' => __( 'All Custom Tags' ), /* all title for taxonomies */
										'parent_item' => __( 'Parent Custom Tag' ), /* parent title for taxonomy */
										'parent_item_colon' => __( 'Parent Custom Tag:' ), /* parent taxonomy title */
										'edit_item' => __( 'Edit Custom Tag' ), /* edit custom taxonomy title */
										'update_item' => __( 'Update Custom Tag' ), /* update title for taxonomy */
										'add_new_item' => __( 'Add New Custom Tag' ), /* add new title for taxonomy */
										'new_item_name' => __( 'New Custom Tag Name' ) /* name title for taxonomy */
									),

									'show_admin_column' => true,
									'show_ui' => true,
									'query_var' => true,
								)
							);

					 }


					 public function template_include( $template ){

						

					 	if ( is_single() && get_post_type() == $this->_post_type ) {
					 		
					 		

					 		if( file_exists($this->_plugin_path.'/single.php') )
					 			$template = $this->_plugin_path.'/single.php';

					 	}

					 	elseif ( is_tax( $this->_post_type_category ) || is_tax( $this->_post_type_tag ) ) {

					 		if( file_exists($this->_plugin_path.'/taxonomy.php') )
					 			$template = $this->_plugin_path.'/taxonomy.php';
					 	}

					 	elseif (is_post_type_archive( $this->_post_type ) ) {

					 		if( file_exists($this->_plugin_path.'/archive.php') )
					 			$template = $this->_plugin_path.'/archive.php';

					 	}

					 	return $template;

					 }

			}

			$my_post_type = new my_post_type_as_a_plugin();
}

