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
Occasio WordPress Plugin

Occasio is a custom WordPress plugin that adds extra functionality to your WordPress website. This README will guide you through installation, activation, and usage.

📥 Installation

You can install the plugin in two ways:

1. Install via ZIP file

Clone or download this repository.

git clone https://github.com/ShreyaNanaware/Occasio-WordPress-Plugin.git


Or simply download the repository as a ZIP from GitHub.

Locate the plugin folder Occasio-WordPress-Plugin on your computer.

Compress the folder into a ZIP file (if not already in ZIP format).

Open your WordPress Admin Dashboard.

Go to Plugins > Add New > Upload Plugin.

Upload the ZIP file and click Install Now.

After installation, click Activate Plugin.

2. Install manually (without ZIP)

Clone the repository directly into your WordPress plugins folder:

cd wp-content/plugins/
git clone https://github.com/ShreyaNanaware/Occasio-WordPress-Plugin.git


Go to your WordPress Admin Dashboard.

Navigate to Plugins > Installed Plugins.

Find Occasio and click Activate.

⚙️ Usage

Once activated, the plugin will add its functionality to your WordPress site.

Depending on the plugin’s purpose (custom features, widgets, or enhancements), you can configure it from the WordPress Admin Panel.

Check for any new menu options added by Occasio in the sidebar.

🛠 Requirements

WordPress 5.0 or later

PHP 7.4 or later

📌 Notes

If you update the plugin manually, deactivate and delete the old version before uploading the new one.

Always keep backups of your WordPress site before installing or updating plugins.

📜 License

This project is licensed under the MIT License – feel free to modify and use it.
