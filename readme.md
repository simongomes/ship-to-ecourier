# Ship To eCourier

**Contributors:** [Simon Gomes](https://github.com/simongomes)  
**Donate link:** https://simongomes.dev/  
**Tags:** ecourier, ecourier-parcel, ship-to-ecourier, ecourier-booking, ecourier-parcel-booking, ecourier-booking-automation, ecourier-parcel-tracker  
**Requires at least:** 4.0  
**Tested up to:** 6.2.0  
**Requires PHP:** 5.6  
**Stable tag:** 1.1.1
**License:** GPLv3  
**License URI:** https://www.gnu.org/licenses/gpl-3.0.html

**Ship To eCourier** gives you the ability to send a parcel booking request to eCourier directly from your WooCommerce order dashboard.

[![Release](https://img.shields.io/badge/release-v1.1.1-blue.svg?style=flat-square)](https://github.com/simongomes/ship-to-ecourier/releases/)
[![GitHub license](https://img.shields.io/badge/license-GPLv3-green.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0.html)
[![Open Source](https://img.shields.io/badge/open%20source-yes-orange.svg?style=flat-square)](https://github.com/simongomes/ship-to-ecourier)
[![Made With](https://img.shields.io/badge/made%20with-php-darkgreen.svg?style=flat-square)](https://www.php.net/)
[![Maintaner](https://img.shields.io/badge/maintaner-Simon%20Gomes-darkred.svg?style=flat-square)](https://simongomes.dev/)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-1eb195.svg?style=flat-square)](https://github.com/simongomes/ship-to-ecourier/pulls)
[![Download Plugin](https://img.shields.io/badge/download-plugin-fbbc04.svg?style=flat-square)](https://wordpress.org/plugins/ship-to-ecourier)

## Description

_Ship To eCourier_ WordPress plugin gives you the ability to simply book your eCourier parcel request directly from your WooCommerce order dashboard.

You can use _WooCommerce_ order information and automate your _eCourier_ parcel booking only with some button click. You will only need to provide the eCourier API credentials for your account.

Other than the shipment and booking automation, the plugin will also provide you a shortcode that you can use for the parcel status tracking. You can add the shortcode to any of your pages and it will give your customer a neat interface for parcel tracking.

## Features

The plugin provides following features.

-   eCourier parcel booking automation
-   eCourier parcel tracking

## Installation

Follow the following steps to install the plugin and get it working.

1. Install the plugin from WordPress plugin library using the Plugins section from the WordPress dashboard, or download the zip file unzip and upload it inside `/wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu.
3. Once installed, _eCourier Settings_ menu will be available. Go to the _eCourier Settings_ and provide your eCourier API Credentials.
4. Select the Environment you would like to use (Staging or Live).

## Frequently Asked Questions

**Do I need eCourier account to use the plugin?**

Yes, you will need an eCourier account and API credentials to user this plugin.

**Do I need any configuration from eCourier?**

Yes, you will need API credentials for you eCourier account. You can get it from eCourier.

**What credentials do I need to setup the plugin?**

You will need your API credentials which include `USER-ID`, `API-KEY` and `API-SECRET`.

**How do I get the API credentials?**

You can contact eCourier for the API credentials for your account.

**Do I need WooCommerce for this plugin?**

Yes, the eCourier parcel booking module is dependent on WooCommerce

## Privacy Policy

Ship To Ecourier uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

## Changelog

_1.1.1_

> -   Fetch City and area from Ecourier API and display in Dropdown
> -   Add caching for storing API data to avoid redundancy
> -   Error handling for HTTP requests.
> -   Add order note when shipped and cancel
> -   added a filter to allow modification in shipping info by 3rd party plugin

_1.1.0_

> -   Added: Label printing feature
> -   Added: Cancel parcel booking feature

_1.0.3_

> Added: Appsero tracker to track plugin analytics data

_1.0.2_

> CSRF validation added for settings form, to protect against unnecessary security breach.

_1.0.1_

> 1.0.1 is the initial release of Parcel Tracker eCourier plugin.

## Contributors

<a href="https://github.com/simongomes">
  <img src="https://github.com/simongomes.png?size=50" style="border-radius: 50%" alt="Simon Gomes" title="Simon Gomes">
</a>
