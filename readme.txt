=== Floating PWA Installer ===
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.1
License: GPLv2 or later

Floating installation button, custom triggers, and optional PWA endpoints.
Developed by Tehran Web SEO.

== Setup ==
Activate and open Floating PWA Installer in the main admin menu after Plugins. Use HTTPS.
Custom trigger: <button type="button" id="pwa-install-trigger">Install</button>
Use a square site icon of at least 512px for broad browser compatibility.
Disable manifest management when using another PWA provider.
Existing manifest links and overlapping foreign workers are preserved.
Endpoints use the site's home path; subdirectory multisite sites never claim the network root.
Plain permalinks use query endpoints. Pretty endpoints require WordPress URL routing.
No offline content is cached. Browser installation eligibility varies.

== QA ==
Check endpoint MIME types, HTTPS installation, custom trigger, and appinstalled hiding.
Check iOS instructions, keyboard focus, RTL, mobile positions, and settings sanitization.
Check existing PWA providers, plain permalinks, subdirectory multisite, and uninstall opt-in.
