=== Plugin Name ===
Contributors: tsuijten
Tags: columns, column, magazine, newspaper, layout, pages, posts
Requires at least: 2.7
Tested up to: 3.1
Stable tag: 1.1

Easily create one or more columns in your posts or pages. Easy styling with a complete set of generated CSS classes.

== Description ==

There are similar plugins but they did not provide the ease of use I wanted.

Creating columns is super easy, just include the column separator text in your page or post (by default `/---/`, configurable in the WP Column Settings page). 

HTML code will be generated with various CSS classes so you can easily style the columns in your Stylesheet.

**Example**

The following text in your page or post:

	This text will be in the first column.
	/---/
	This text will be in the second column.
	/---/
	This text will be in the third column.


Will result in the following HTML code:

	<div class="wpcolumn-wrapper wpcolumn-wrapper-3">
	<div class="wpcolumn wpcolumn-1 wpcolumn-first">This text will be in the first column.</div>
	<div class="wpcolumn wpcolumn-2 wpcolumn-other">This text will be in the second column.</div>
	<div class="wpcolumn wpcolumn-3 wpcolumn-last">This text will be in the third column.</div>
	<div class="wpcolumn-clearfix"></div>
	</div>


Using the various CSS classes assigned to the columns and the columns-wrapper you can style different number of columns in different ways.

The column separator text as well as the CSS class prefix can be customized in the WP Columns Settings Panel (see screenshots).

This CSS was used to accomplish the example seen in the screenshots section:

	.wpcolumn-clearfix {
		clear: both;
	}
	.wpcolumn-wrapper-3 .wpcolumn {
		float: left;
		margin: 10px 4% 15px 0;
		width: 46%;
	}
	.wpcolumn-wrapper-3 .wpcolumn-last {
		margin-right: 0;
	}
	.wpcolumn-wrapper-3 .wpcolumn-1 {
		color:#888888;
		font-family:'Lucida Grande',Verdana,Arial,sans-serif;
		font-size:16px;
		float: none;
		width: auto;
	}

== Installation ==
1. Upload `wpcolumns.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is this plugin supported? =

Yes, just send me an e-mail and I will see to your enhancement request / bug report as soon as possible.

= I don't want to see the column separator text when the plugin is disabled. =

If you set the column separator to something like `<!-- column -->` the separator will not be visible when the WP Columns plugin is not activated.

*Please note that the separator text will not be visible Wysiwyg editor*

== Screenshots ==

1. WP Columns settings page
1. WP Columns in action 

== Changelog ==

= 1.1 =
Added an option to realize that content before the first and after the last separator will not be part of the columns. 
Example: With the following content, `a` and `d` will not be part of the columns when the option is selected and you will end up with two columns for `b` and `c`

	a
	/---/
	b
	/---/
	c
	/---/
	d

If you have this option enable and you want include `a`, `b`, `c` and `d` as a column you must use tho following content:

	/---/
	a
	/---/
	b
	/---/
	c
	/---/
	d
	/---/

= 1.0 =
* First release