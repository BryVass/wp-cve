=== codoc ===
Contributors: codoc
Donate link: https://codoc.jp
Tags: codoc, paywall, editor, subscription
Requires at least: 4.6
Tested up to: 6.3.1
Stable tag: 0.9.51.11
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin for monetizing websites by enabling paid articles, subscriptions(memberships), and tipping.

== Description ==

With this plugin, you can quickly implement the sale of paid articles, subscription services, and tipping on your WordPress website.

After configuring the plugin, it provides the codoc block※ on the post editor screen for selling articles.
※The codoc block is compatible with both the Gutenberg block editor and the Classic Editor (TinyMCE).

The sales mechanism is simple. Blocks (or text) placed below the codoc block in the post content can only be accessed by users who have made a payment (credit card transaction). Conversely, blocks placed above are accessible to all users for free. The codoc block not only displays product information for purchasing articles but also manages authentication for users who have purchased the articles. Pricing, subscription specifications, and other sales conditions can be specified on a per-article basis within the block settings.

Additionally, all the necessary features for content sales, such as revenue management and customer management, are available on the codoc website. Purchase history and subscription management for customers who have made article purchases are also provided on the codoc website.

== Installation ==

1. Search for the "codoc" plugin from "Plugins → Add New" in your WordPress dashboard.

2. Install and activate the codoc plugin.

3. Go to "Settings → codoc Settings" and log in or register for a codoc account.

4. Proceed to the codoc blog integration feature and click "Authenticate".

5. Go to the codoc Settings page in WordPress and confirm the display of "your@address authenticated" message.

Once the above settings are complete, you can start using the codoc block by searching for "codoc" in the "Add Block" section of the post editor※.
※For users of the Classic Editor, a codoc configuration button will be added.

== FAQ ==

= What are the conditions for creators to sell content using codoc? =

The current requirement is to have a residence and a bank account within Japan.

= What are the conditions for users to purchase content?

Users who can use a credit card, including those outside of Japan, can make purchases. The currency used is only yen.

= Where are the free and paid parts of the articles stored? =

All article content is saved in both WordPress and codoc.

= Will the publication status be reflected in codoc? =

Yes, it will be reflected. It will only be published on the codoc side if it is published on the WordPress side. (Otherwise, it will be set as unpublished on codoc.)

= What happens to the codoc articles if I delete them in WordPress? =

They will become unpublished.

= How can I see how the article appears to buyers and non-buyers? =

The view for buyers will be the same as when they log in with their codoc account by clicking "Already purchased, log in" displayed in the article. (The purchase button will disappear, and they can access the paid area.) The view for non-buyers will be the same as the state before logging in (not logged in). When you log in with an account other than your own, only the login button will not be displayed.

= I can see the paid area of the article even though I haven't purchased it. Why is that? =

It is because you are logged in as the codoc creator, who is the author of the relevant article being sold on the website.

= Can I use the plugin in the Classic Editor? =

Yes, it is possible. After completing the integration settings, a codoc logo button will be added to the editor toolbar. You can use this button to add codoc blocks and configure article settings such as pricing in the main text. We also recommend enabling the "keep paragraph tags in the Classic block and the Classic Editor" option in the settings of the TinyMCE Advanced plugin that is installed for use.

= Can I specify a featured image? =

The featured image specified in the WordPress post will be used as the codoc featured image.

= Can I customize the CSS? =

You can override the CSS by specifying the custom CSS path in the codoc settings screen. Please import the original CSS file from https://codoc.jp/css/paywall.css and overwrite the relevant parts. Note that the HTML output generated by codoc may be subject to changes without prior notice, so please be aware of that.

= Can I change the permalink registered in codoc? =

The links are obtained from get_permalink(). If it is different from what is expected, such as when running WordPress under a reverse proxy, you can change it using the replacement settings in the codoc settings screen.

= The codoc block displays "This block contains unexpected or invalid content." =

Please perform "Recover Block" from the settings button in the upper right corner of the block.

= Does JavaScript code work? =

Since April 22, 2020, the specification has been changed to allow script tags for external JavaScript loading to work. This supports embedding blocks for platforms like Twitter and Instagram in Gutenberg.

= I want to add a button at the end of the article body. =

If you add a block below the codoc tags, it will be hidden. Therefore, if you want to add social sharing buttons or advertisements, etc., please use the "Insert HTML before and after codoc tags" feature in the "Settings → codoc" section, instead of doing it directly on the post editor.

= Shortcodes are being displayed as they are in the paid part. =

In the "Settings → codoc" section, specify the following in the debug parameter. If it doesn't work, increase the number:

{ "the_content_filter_priority": 1000000 }

== Screenshots ==

== Changelog ==

= 0.9.51 =

* Support for i18n (internationalization)

= 0.9.3 =

* Added support for limited publication.

= 0.9.13 =

* Added a setting to add attributes to the codoc script tags.

* If a password is specified when posting an article, the article status on codoc will be set as "limited publication".

= 0.9.10 =

* Added support for external service integration.

= 0.9.9 =

* Added support for flexible pricing.

= 0.9.8 =

* Revised the design of the settings page.

* Added functionality to show or hide "Powered By" for likes and codoc in the paywall.

* Added functionality to change the button text in the paywall.

= 0.9.7 =

* Added theme settings.

= 0.9.6 =

* Fixed an issue where the paid part would be displayed without the execution of filters by other plugins.

* Added the option to customize the support button description for the support button auto-insertion.

= 0.9.5 =

* Added an option to automatically insert a support button at the end of the content if the codoc tags are not inserted in the article.

= 0.9.4 =

* Modified to display a "Read More" link when displayed in AMP and redirect to the main site.

= 0.9.3 =

* Fixed a bug where user code and token could not be entered directly.

= 0.9.2 =

* Disabled the codoc button in the Classic Editor (TinyMCE).

= 0.9.1 =

* Fixed a bug where codoc integration was not possible.

* Fixed a bug where authentication could not be canceled.

= 0.9 =

* Refactored program source code.

= 0.8.9 =

* Added the ability to insert HTML before and after codoc tags in the settings page.

= 0.8.8 =

* Fixed an issue causing errors in WordPress versions below 5.

* Fixed an issue where codoc tags did not work in the LP template of the THETHOR theme.

* Changed the attribute position of the cms.js script tag to make the codoc tags work.

= 0.8 =

* Added support for the Classic Editor (TinyMCE).

* Changed the Gutenberg block format accordingly.

= 0.7 =

* Added a settings link to the codoc area in the plugin list.

= 0.6 =

* Added a visual boundary between the free and paid parts in the preview.

= 0.5 =

* Added URL replacement function for registration in codoc.

= 0.4 =

* Minor fixes.

= 0.3 =

* Added codoc block functionality.

= 0.2 =

* Added a setting item for custom CSS path.

= 0.1 =

* Initial release.

== Upgrade Notice ==


`<?php code(); // goes in backticks ?>`
