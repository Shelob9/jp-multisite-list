<?php
/*
* @package jp-multisite-links
* @author Josh Pollock
* @since 0.1
*/

/*
Plugin Name: Multisite Lists
Plugin URI: 
Description: Creates lists of things in a multisite network, like blogs, posts, pages etc. Uses transient cache to speed up list display.
Version: 0.1
Author: Josh Pollock
Author URI: http://JoshPress.net
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
* Include the jp-transient class
*
* @returns jp-transient class
* @since 0.1
* @author Josh Pollock
*/
include( 'inc/jp-transient.php' );


class jp_multisite_list {

	/**
	* Creates the array of blog information, posts, pages
	*
	* @returns array @site_info {
			'blog_id'
			'blog_url'
			'blog_name'	
			'post'
			'pages'
	* }
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function blog_info() {
		global $blog_id;
		$sites = wp_get_sites();
		$sites_info = array();
		$current_site = $blog_id;

		//run throught the sites. switch to blogs, get pages and posts as well as blog name, ID and url
		foreach ( $sites as $site ) {
			switch_to_blog( $site[ 'blog_id' ] );
			$posts = get_posts();
			$pages = get_pages();
			$sites_info[] = array(
				'blog_id'		=> $site[ 'blog_id' ],
				'blog_url'		=> get_home_url(),
				'blog_name'		=> get_bloginfo( 'name'),
				'posts'		=> $posts,
				'pages'			=> $pages
			);
		}
	
		//return the array
		return $sites_info;
	}

	/**
	* List all posts from network
	*
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function posts() {
		$test = jp_transient::get('jp_posts');
		//if there is nothing in cache assemble output
		if ( $test == false) {
			//create out put for posts
			$list = $this->posts_list();
			//cache it for next time
			jp_transient::set( 'posts', $list );
			//prepare to return $list
			$out = $list;
		}
		else {
			$out = jp_transient::get( 'jp_posts' );
		}
		return $out;
	}
	
	/**
	* Create post list
	*
	* @returns string $posts_list the formatted post list
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function posts_list() {
		$data = '';
		$blogs = $this->blog_info();
		foreach ($blogs as $blog) {
			$name = $blog[ 'blog_name' ];
			$url = $blog[ 'blog_url' ];
			$data .= '<h5><a href="'.$url.'">'.$name.'</a></h5>';
			switch_to_blog( 'blog_id' );
			$posts = $blog[ 'posts' ];
			
			$data .= '<ul>';
			foreach ($posts as $post ) {
				$id = $post;
				$data .= '<li>';
				$title = get_the_title( $id );
				$link = get_permalink( $id );
				$data .= '<a href="'.$link.'">'.$title.'</a>';
				$data .= '</li>';
			}
			$data .= '</ul>';
		}
		$posts_list = $data;
		return $data;
	}
	
	/**
	* List all pages from network
	*
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function pages() {
		$test = jp_transient::get('jp_pages');
		//if there is nothing in cache assemble output
		if ( $test == false) {
			//create out put for pages
			$list = $this->pages_list();
			//cache it for next time
			jp_transient::set( 'pages', $list );
			//prepare to return $list
			$out = $list;
		}
		else {
			$out = jp_transient::get( 'jp_pages' );
		}
		return $out;
	}
			
		/**
	* Create page list
	*
	* @returns string $pages_list the formatted page list
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function pages_list() {
		$data = '';
		$blogs = $this->blog_info();
		foreach ($blogs as $blog) {
			$name = $blog[ 'blog_name' ];
			$url = $blog[ 'blog_url' ];
			$data .= '<h5><a href="'.$url.'">'.$name.'</a></h5>';
			switch_to_blog( 'blog_id' );
			$pages = $blog[ 'pages' ];
			
			$data .= '<ul>';
			foreach ($pages as $page ) {
				$id = $page;
				$data .= '<li>';
				$title = get_the_title( $id );
				$link = get_permalink( $id );
				$data .= '<a href="'.$link.'">'.$title.'</a>';
				$data .= '</li>';
			}
			$data .= '</ul>';
		}
		$pages_list = $data;
		return $data;
	}
}

function pluginslug_get_foo() {
    $foo = new pluginslug_foo();
    return $foo;
}

function pluginslug_bar() {
    $foo = pluginslug_get_foo();
    $bar = $foo->bar();
}


function jp_msl_get_lists() {
	$lists = new jp_multisite_list();
	return $lists;
}

function jp_msl_pages() {
	$lists = jp_msl_get_lists();
	$pages = $lists->pages();
	echo $pages;
}

function jp_msl_posts() {
	$lists = jp_msl_get_lists();
	$posts = $lists->posts();
	echo $posts;
}


