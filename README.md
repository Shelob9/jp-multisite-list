jp-multisite-list
=================
Adds the ability to load all post or all pages from all network sites. Uses transient cache to save results in order to avoid having to recalculate on each page load, which could be a very resource intensive process on a large site.

This plugin is under development. See known limitations/issues below before using.

Use
===
* List Posts
In your theme use:

`<?php
	if ( function_exists( 'jp_msl_posts') :
		jp_msl_posts();
	endif;
?>`

* List Pages
In your theme use:

`<?php
	if ( function_exists( 'jp_msl_pages') :
		jp_msl_pages();
	endif;
?>`

Planed Features For Version 1.0
===============================
* Experation controls

	Write jp_transient::reset
	
	Ability to pass time or other reset params from out of class functions to jp_transient::set.
	
	Ability to set reset time to a WP cron job.
	
	Ability to set reset time to a real cron job.
	
	Control cron time from options page.
	
	Ability to expire on certain actions (like when a post or page is published.)
	
	Ability to reset on expiration.
	
* Use WP_Query instead of get_posts() and get_pages
	Ability to pass WP_Query args to jp_multisite_list::posts and jp_multisite_list::pages
	
	Pass those args from out of class functions as well.
	
* Option to show/ not show blog title.
* Option to show posts and pages together.
* Widget With Options
* Merge jp_multisite_list::posts and jp_multisite_list::pages into one method to do either or both.
* Documentation
	
Current Known Limitations and Issues
====================================
* No automatic reset.
	
	Once list is created it has to be manually deleted from the transient cache.
* Deprecated function/ Beta Function
	
	To get the list of blogs wp_get_sites() a new function added in 3.7 beta 1 is used.
	
	Backwards compatibility is offered via the deprecated and not so good function get_blog_list. I will probably get rid of this backwards compatibility once 3.7 is released.


License and Contributions
====================
Copyright 2013 Josh Pollock. Licensed under The GNU General Public License version 2 or later.

http://www.gnu.org/licenses/gpl-2.0.html

Pull requests and other contributions are welcome.
