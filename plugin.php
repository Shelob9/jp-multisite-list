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
function bl_blog_info() {
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
function bl_network_posts() {
	$test = jp_transient::get('bl_posts');
	//if there is nothing in cache assemble output
	if ( $test == false) {
		$out = '';
		$blogs = bl_blog_info();
		foreach ($blogs as $blog) {
			$name = $blog[ 'blog_name' ];
			$url = $blog[ 'blog_url' ];
			switch_to_blog( 'blog_id' );
			$posts = $blog[ 'posts' ];
			$out .= '<h5><a href="'.$url.'">'.$name.'</a></h5>';
			$out .= '<ul>';
			foreach ($posts as $post ) {
				$id = $post;
				$out .= '<li>';
				$title = get_the_title( $id );
				$link = get_permalink( $id );
				$out .= '<a href="'.$link.'">'.$title.'</a>';
				$out .= '</li>';
			}
			$out .= '</ul>';
			//cache it for next time
			jp_transient::set( 'bl_posts', $out );
		}
	}
	else {
		$out =jp_transient::get( 'bl_posts' );
	}
	//echo
	echo $out;
}

/**
* List all pages from network
*
* @package jp-multisite-links
* @author Josh Pollock
* @since 0.1
*/
function bl_network_pages() {
	$blogs = bl_blog_info();
	foreach ($blogs as $blog) {
		$name = $blog[ 'blog_name' ];
		$url = $blog[ 'blog_url' ];
		switch_to_blog( 'blog_id' );
		$pages = $blog[ 'pages' ];
		echo '<h5><a href="'.$url.'">'.$name.'</a></h5>';
		echo '<ul>';
		foreach ($pages as $page ) {
			$id = $page;
			echo '<li>';
			$title = get_the_title( $id );
			$link = get_permalink( $id );
			echo '<a href="'.$link.'">'.$title.'</a>';
			echo '</li>';
		}
		echo '</ul>';
	}
}

/**
* List all posts and pages from network
*
* @package jp-multisite-links
* @author Josh Pollock
* @since 0.1
*/
function bl_network_posts_pages() {
	$blogs = bl_blog_info();
	foreach ($blogs as $blog) {
		$name = $blog[ 'blog_name' ];
		$url = $blog[ 'blog_url' ];
		switch_to_blog( 'blog_id' );
		$posts = $blog[ 'posts' ];
		$pages = $blog[ 'pages' ];
		echo '<h5><a href="'.$url.'">'.$name.'</a></h5>';
		echo '<h6>Posts</h6>';
		echo '<ul>';
		foreach ($posts as $post ) {
			$id = $post;
			echo '<li>';
			$title = get_the_title( $id );
			$link = get_permalink( $id );
			echo '<a href="'.$link.'">'.$title.'</a>';
			echo '</li>';
		}
		echo '</ul></h6>';
		echo '<h6>pages</h6>';
		echo '<ul>';
		foreach ($pages as $page ) {
			$id = $page;
			echo '<li>';
			$title = get_the_title( $id );
			$link = get_permalink( $id );
			echo '<a href="'.$link.'">'.$title.'</a>';
			echo '</li>';
		}
		echo '</ul></h6>';
	}
}
