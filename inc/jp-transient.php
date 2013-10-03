<?php
/**
* @author Josh Pollock (http://JoshPress.net)
*/


/**
* Class To Store and Retrieve Results Of Functions In WordPress Transient Cache
*
* http://codex.wordpress.org/Transients_API
*
* @author Josh Pollock
* @since 0.1
*/

class jp_transient{

	/**
	* Save To Transient Cache
	*
	* @param $name Name of what we are saving.
	* @param $value Value to be saved.
	* @param string|int $reset (optional) How long to keep in cache. Options string: minute|hour|day|week|year|none|new_post|new_page or exact number of seconds as integer
	* @todo Set expiration time using reset method
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	
	static function set( $name, $value, $reset = false ) {
	
		/** prepare value for $reset **/
		/*If its a string that is a time value*/
		//translate value of $reset into seconds
		if ( $reset == 'minute' ) {
			$reset = MINUTE_IN_SECONDS;
		}
		elseif ( $reset == 'hour' ) {
			$reset = HOUR_IN_SECONDS;
		}
		elseif ( $reset == 'day' ) {
			$reset = DAY_IN_SECONDS;
		}
		elseif ( $reset == 'week' ) {
			$reset = WEEK_IN_SECONDS;
		}
		elseif ( $reset == 'year' ) {
			$reset = YEAR_IN_SECONDS;
		}
		/*reset on post or page publication*/
		//Will set rest to false so it does not expire otherwise
		//Also set the $action_reset param which would otherwise be false.
		elseif ( $reset == 'new_post' ) {
			$reset = false;
			$action_reset = 'post';
		}
		elseif ( $reset == 'new_page' ) {
			$reset = false;
			$action_reset = 'page';
		}
		/*other situations*/
		elseif ( is_int( $reset ) ) {
			//if its an integer leave it alone
			$reset = $reset;
		}
		elseif ( empty( $reset ) ) {
			//if its empty set it to be false
			$reset = false;
		}
		else {
			//for safety's sake
			$reset = false;
		}
		
		/** Set the transient **/
		//test if we have anything to set.
		if ( ! empty( $value ) ) {
			//test for multisite, use appropriate function if is_multisite
			if ( is_multisite() ) {
				if ( $reset != false ) {
					set_site_transient( $name, $value, $reset );
				}
				else {
					set_site_transient( $name, $value );
				}
			} //is_multisite
			else {
				//skip setting a rest time if we don't have one to set.
				if ( $reset != false ) {
						set_transient( $name, $value, $reset );
					}
				else {
					set_transient( $name, $value );
				}
			} // if ! is_multisite
		} // ! empty( $value )
		echo $reset;
	}
	
	/**
	* Get From Transient Cache
	*
	* @param $name Name of value (saved with set method) to be retrieved
	* @returns $data Vale from transient cache.
	* @todo more params to customize what we get out, array vs object, specific keys etc.
	* 
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/
	public function get( $name ) {
		
		//get the transient
		if ( is_multisite() ) {
				$transient = get_site_transient( $name );
			}
		else {
			$transient = get_transient( $name );
		}
		
		//return value if possible.
		if ( ! empty( $transient ) ){
			//we have a transient so return value
			$data = $transient;
		}
		else {
			//nothing to return so return false
			$data = false;
		}
		
		//output our data.
		return $data;

	}
	
	/**
	* Arrays to store names of transients to be deleted on post or page publish.
	*
	* @param array $post_publish_reset All transients in array deleted on publish_post
	* @param array $page_publish_reset All transients in array deleted on publish_page
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.2
	*/
	public $post_publish_reset = array();
	public $page_publish_reset = array();
	
	/**
	* Adds new names of transients to the $post_publish_reset and $page_publish_reset arrays
	* 
	* @param string $post_reset Names of transients to be deleted on publish_post.
	* @param string $page_reset Names of transients to be deleted on publish_page.
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.2
	*/
	public add_reset( $post_reset = false, $page_reset = false ) {
		//check if there is a transient to add to $post_publish_reset
		if ( $post_reset != false ) {
			//add to array
			array_push($post_publish_reset, $post_reset);
			//return array so auto_reset can delete item
			return $post_publish_reset;
		}
		//check if there is a transient to add to $page_publish_reset
		if ( $page_reset != false ) {
			//add to array
			array_push($page_publish_reset, $page_reset);
			//return array so auto_reset can delete item
			return $page_publish_reset;
		}
	}
		
	/**
	* Reset Transients
	*
	* @todo This. Idea is to be able to use it to more easily set reset times for transients.
	* @todo Reset at specific time.
	* @todo Put site into maintenance mode while rebuilding cache.
	* @package jp-multisite-links
	* @author Josh Pollock
	* @since 0.1
	*/

	public function auto_reset( $what_reset ) {
		
		
		if ( is_multisite() ) {
			delete_site_transient( $what_reset );
		}
		else {
			delete_transient( $what_reset );
		}
			
	}



}