=== PerfectWP - WC Omnibus ===
Contributors: perfectwp
Tags: woo, ecommerce, shop, omnibus, eu, law, woocommerce, history, price, e-commerce, product
Requires at least: 5.4
Tested up to: 6.1.1
Stable tag: {VERSION}
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Plugin for [WooCommerce](https://wordpress.org/plugins/woocommerce/) with correct implemented EU Directive Omnibus [2019/2161 of the European Parliament](https://eur-lex.europa.eu/eli/dir/2019/2161/oj)

- Showing the lowest price in the last 30 days before a product has been marked as on sale
- Works correctly with taxable products
- Works correctly with duplicating product function
- Translation for message, supports WPML and Polylang plugin
- Tracking all price changes

Shortcode:
- For developers who need to display information on custom place use ``[pwp_wco_omnibus_information]`` shortcode (Not working with variant products).

Works correctly with common product types:
- Simple products
- Download
- Virtual
- Variable products

== Screenshots ==
1. Additional fields for simple products
2. The information is showing on a simple product page
3. Additional fields for variations
4. The information is showing on a variable product page
5. Plugin settings

== Changelog ==

= 1.0.4 2023-01-09 =

* Correct implemented EU Directive Omnibus, thanks to [piotrczapla2jb](https://wordpress.org/support/users/piotrczapla2jb/)
* Fix issue with duplicate products
* Skip saving prices for draft products
* Showing woocommerce prices when product history not exists
* Setup number of days allowed in configuration
* Show only for sale option in config
* Custom omnibus message (Support for WPML and Polylang)

= 1.0.3 2023-01-02 =

* Fix issue with taxable products
* Add shortcode pwp_wco_omnibus_information
* Fix issue with polish translations
* Add settings options
* Default settings, showing the omnibus information only on product page

= 1.0.1 2022-06-01 =

* First version of the plugin