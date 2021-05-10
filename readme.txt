=== Ship To eCourier ===
Contributors: simongomes02
Donate link: https://simongomes.dev/
Tags: ecourier, ecourier-parcel, ship-to-ecourier, ecourier-booking, ecourier-parcel-booking, ecourier-booking-automation, ecourier-parcel-tracker
Requires at least: 4.0
Tested up to: 5.7.1
Requires PHP: 5.6
Stable tag: 1.1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

The Plugin gives you the ability to send a parcel booking request to eCourier directly from your WooCommerce order dashboard.

== Description ==

*Ship To eCourier* WordPress plugin gives you the ability to simply book your eCourier parcel request directly from your WooCommerce order dashboard.

You can use *WooCommerce* order information and automate your *eCourier* parcel booking only with some button click. You will only need to provide the eCourier API credentials for your account.

Other than the shipment and booking automation, the plugin will also provide you a shortcode that you can use for the parcel status tracking. You can add the shortcode to any of your pages and it will give your customer a neat interface for parcel tracking.

== Features ==

The plugin provides following features.

* eCourier parcel booking automation
* eCourier parcel tracking

== Installation ==

Follow the following steps to install the plugin and get it working.

1. Install the plugin from WordPress plugin library using the Plugins section from the WordPress dashboard, or download the zip file unzip and upload it inside `/wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu
3. Once installed, *eCourier Settings* menu will be available. Go to the *eCourier Settings* and provide your eCourier API Credentials.
4. Select the Environment you would like to use (Staging or Live)

== Frequently Asked Questions ==

= Do I need eCourier account to use the plugin? =

Yes you will need an eCourier account and API credentials to use this plugin.

= Do I need any configuration from eCourier? =

Yes, you will need API credentials for your eCourier account. You can get it from eCourier.

= What credentials do I need to setup the plugin? =

You will need your API credentials which include `USER-ID`, `API-KEY` and `API-SECRET`.

= How do I get the API credentials? =

You can contact eCourier for the API credentials for your account.

= Do I need WooCommerce for this plugin? =

Yes, the eCourier parcel booking module is dependent on WooCommerce

== Screenshots ==

1. eCourier API settings screen.
2. eCourier parcel booking form inside order dashboard.
3. Order status update and tracking information after successful parcel booking.
4. eCourier parcel tracking form.
5. eCourier parcel package information.
6. eCourier parcel shipment statuses.

== Privacy Policy ==

Ship To Ecourier uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

== Changelog ==

= 1.1.0 =
* Added: Label printing feature
* Added: Cancel parcel booking feature

= 1.0.3 =
* Added: Appsero tracker to track plugin analytics data

= 1.0.2 =
* CSRF validation added for settings form, to protect against unnecessary security breach.

= 1.0.1 =
* 1.0.1 is the initial release of Parcel Tracker eCourier plugin.
