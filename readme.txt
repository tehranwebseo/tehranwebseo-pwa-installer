=== Floating PWA Installer ===
Contributors: tehranwebseo
Tags: pwa, install button, web app, wordpress, woocommerce
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Turn your WordPress site into an installable app experience with a floating install button, custom triggers, and safe optional PWA endpoints.

Developed by Tehran Web SEO.

== Description ==

Floating PWA Installer helps you increase repeat visits and improve user retention by making your site easier to install as an app (where supported by the browser).

Designed for performance-conscious WordPress sites, this plugin gives you practical install UX controls without forcing aggressive overrides.

= Why site owners choose Floating PWA Installer =

* Floating install button with flexible position options (mobile/desktop)
* Custom trigger support for theme/page-builder buttons
* Optional manifest and service worker endpoints
* Conflict-aware behavior with existing PWA setups
* Clean admin settings with sanitization-focused implementation
* Works with plain and pretty permalinks
* Multisite-aware endpoint handling (subdirectory safe)

= Important behavior =

* No offline content caching is added by default
* Existing manifest links are respected when manifest management is disabled
* Overlapping foreign service workers are not forcefully overridden
* Installation eligibility depends on browser/platform criteria

== Features ==

* Floating PWA install UI
* Custom trigger ID support (example: `pwa-install-trigger`)
* Optional manifest endpoint
* Optional service worker endpoint
* Optional icon endpoint with fallback
* iOS instruction messaging support
* App-installed state handling (hide install UI after install)

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install via Plugins → Add New.
2. Activate **Floating PWA Installer**.
3. Open **Floating PWA Installer** from the main admin menu (after Plugins).
4. Ensure your site uses **HTTPS**.
5. Configure button style/position and optional endpoint settings.

== Setup ==

Use a square site icon of at least **512×512** for broad compatibility.

Custom trigger example:

`<button type="button" id="pwa-install-trigger">Install</button>`

If another PWA plugin/provider is active, disable manifest management in this plugin to avoid overlap.

Notes:

* Endpoints are generated using the site home path.
* On subdirectory multisite, the network root is never claimed.
* Plain permalinks use query-based endpoints.
* Pretty endpoints require normal WordPress URL routing.

== Frequently Asked Questions ==

= Does this plugin make my site fully offline-ready? =
No. This plugin does not add full offline caching by default.

= Will it conflict with my existing PWA plugin? =
It is designed to be conflict-aware. Existing manifest links and overlapping foreign service workers are preserved where applicable.

= Why is the install prompt not shown on some devices? =
Install prompts are controlled by browser/platform eligibility rules and HTTPS requirements.

= Can I use my own install button? =
Yes. Set a custom trigger ID and call the install flow from your own UI.

== QA Checklist ==

* Endpoint MIME types
* HTTPS installation flow
* Custom trigger behavior
* `appinstalled` hide behavior
* iOS instruction UX
* Keyboard focus and accessibility basics
* RTL and mobile position checks
* Settings sanitization checks
* Existing PWA provider compatibility
* Plain permalink and pretty permalink behavior
* Subdirectory multisite behavior
* Uninstall opt-in data deletion

== Changelog ==

= 1.0.1 =
* Security hardening and code quality cleanup
* WPCS alignment and documentation improvements
* Safer endpoint handling and uninstall flow refinements

== Upgrade Notice ==

= 1.0.1 =
Recommended update for improved hardening and standards compliance.
