=== Occasio Events ===
Contributors: yourname
Tags: events, calendar, shortcode, custom post type
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple event management plugin with a custom post type, meta fields (date, time, venue, link) and a shortcode for display.

== Description ==
- Adds "Events" custom post type.
- Meta fields: Date, Time, Venue, External Link.
- Shortcode: [occasio_events posts="5" order="ASC" upcoming="1"]

== Installation ==
1. Upload the `occasio-events` folder to `/wp-content/plugins/`.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Add events under "Events".
4. Display on any page/post using `[occasio_events]`.

== Frequently Asked Questions ==
= How do I show only upcoming events? =
Use: [occasio_events upcoming="1"]

= How to change number of events? =
Use: [occasio_events posts="10"]

= Can I show latest events first? =
Use: [occasio_events order="DESC"]

== Changelog ==
= 1.0.0 =
* Initial release.
