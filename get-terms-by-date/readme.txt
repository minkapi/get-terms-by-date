=== get_terms_by_date() ===
Contributors: minkapi
Tags: category, post-tag, taxonomy, get_terms
Requires at least: 4.9
Tested up to: 4.9.6
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This function gets terms in the order of the posting date of the latest post related to term.


== Description ==

= Description =
You can use the function called get_terms_by_date().
This function gets terms in the order of the posting date of the latest post related to term.
For example: Series Taxonomy

= Usage =
`<?php get_terms_by_date( $taxonomies, $args ); ?>`

= Parameters =
### $taxonomies
_(string|array)(required)_ Taxonomy name, or array of taxonomies, to which results should be limited.
Default: _None_

### $args
_(array)(Optional)_ Array of query parameters.

* __'order'__
	_(string)_ Whether to order terms in ascending or descending order. Accepts 'ASC' (ascending) or 'DESC' (descending).
	Default: _DESC_

* __'number'__
	_(int)_ Maximum number of terms to return. Accepts ''|0(all) or any positive number.
	Default: _''|0(all)_

* __'offset'__
	_(int)_ The number by which to offset the query.

* __'post_status'__
	_(string|array)_ Array or string of post status types to include.
	Default: _publish_

* __'post_type'__
	_(string|array)_ Array or string of post types to include.
	Default: _post_

= Return =
_(array|WP_Error)_ List of WP_Term instances and their children. Will return WP_Error, if any of $taxonomies do not exist.


== Installation ==

1. Upload `get-terms-by-date` to the `/wp-content/plugins/` directory.
2. Activate `get_terms_by_date()` through the 'Plugins' menu in WordPress.


== Changelog ==
= 1.0 =
* Publish this plugin
* Query parameters that can be specified: order, number, offset, post_status, post_type
