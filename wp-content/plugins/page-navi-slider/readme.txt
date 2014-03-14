=== Page navi slider ===
Contributors : Iznogood1, stranger-jp
Tags: pagenavi, navigation, pagination, paging, pages, jQuery, jQuery ui, slider, responsive, touch
Requires at least: 2.7
Tested up to: 3.7.1
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An advanced and fully customizable navigation plugin using jQuery slider.

== Description ==
Unsatisfied by the most common navigation plugins ?

Need a cool one, __fully and easyly customizable__ with __direct preview__ in setting page...

Want an actually __responsive__ plugin...

Just get it !

This plugin generates cool pagination links

* Easy to customize with its setting page
* See what you get thanks to its preview feature
* Actually responsive as it displays a slider if the page numbers exceed the available width
* Touchscreens compliant
* Caption ready to be localized for multilingual sites

== Installation ==
You can either install it automatically from the WordPress admin, or do it manually.

1. Unzip the archive and put the `page-navi-slider` folder into your plugins folder (/wp-content/plugins/)
2. Activate the plugin through the `Plugins` menu in WordPress
3. Keep defaults settings or customize and preview through the `Setting / Page navi slider` menu
4. Place `<?php if(function_exists('page_navi_slider')){page_navi_slider();}?>` in your templates.
5. Or activate the `auto display` feature in the settings (not recommanded).

== Frequently Asked Questions ==

= Where must I insert the code ? =

Page navi slider is displayed by the following instruction :
`<?php if(function_exists('page_navi_slider')){page_navi_slider();}?>`
	
You should think to insert that code in every templates likely to display several pages :

* index.php
* category.php 
* tag.php 
* search.php 
* pages.php...

Example : 

In the twentythirteen theme, file index.php, replace the line 28 (`<?php twentythirteen_paging_nav(); ?>`) by the code above.
	
In the twentytwelve theme, replace both the following lines:
`<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">?</span> Older posts', 'twentyten' ) ); ?></div>`
`<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">?</span>', 'twentyten' ) ); ?></div>`
	
= Page numbers are displayed. But not the slider ! =	

The slider does appear only when the page numbers exceeds the plugin width !

Look at the following FAQ to reduce the plugin width.

= How to set the plugin width ? =

Just set the `margin` in the settings.

* e.g. : 1em 20% -> top and bottom margins of 1em / left and right margins of 20%
* e.g. : 10px 50px 10px 0 -> top margin of 10px / right margin of 50 px / right margin of 10px / no bottom margin

You can also define a `width` or `max-width` for the `wpns_wrapper` class in the CSS.

= Why do page numbers move when I hover them ? =

You have specified different font sizes for "normal" numbers and "on hover" numbers in the settings.
Then the total width is changing.

= I have a multilingual site - How do I localize the caption text ? =

1. Just copy your customized caption in the 'you_strings_to_translate.php' file as follow : `$a=__('you cusomized string','page-navi-slider');`

2. Copy the `/lang/page-navi-slider-fr_FR.mo` to `/lang/page-navi-slider-ISOSTANDARD.mo` (ISOSTANDARD = language code)

3. Open it with PoEdit, Update,  find your customized string, translate, Validate and Save !

You can just translate you customized caption and let the other strings empty as they are only used in the setting panel.

But if you want to translate all the strings, share your file !

= How does the Auto Display work ? =

Auto display adds an action that will echo the plugin.

The action can be hooked at the get_footer event or at the wp-footer event.

If the first case, the plugin is displayed at the top of the footer.

If the second, the plugin is displayed at the end top of the footer.

Note that Wordpress recommends plugins not to echo with theses actions !

= How to use icons as page number background ? =

1. Save your icons files in /wp-content/plugins/page-navi-slider/style

2. In the settings, set the background colors (for page and/or current and/or on hover) to
`url('your_file.ext')`

Of course change `your_file.ext` to the actual file name.

== Screenshots ==

1. Standard settings
2. Rounded pages
3. Gradient background
4. Preview options
5. General settings
6. Caption
7. Step by step font definition
8. Easy colors settings
9. Easy borders definition
10. Autodisplay

== Changelog ==

= 1.2.2 =
* Minor bug fixed (`show total pages` option was automatically checked when the plugin was deacitated/reactivated)
* Tested on WP 3.7.1

= 1.2.1 =
* Fatal error depending on PHP version fixed

= 1.2 =
* Settings page style improved
* Ability to save settings without preview
* Code optimization for easy maintenance

= 1.1.1 =
* Minor bug of the previous version fixed: messages when saving settings actually displayed

= 1.1 =
* Preview settings without apply them
* Settings can be reverted
* Code optimization

DEACTIVATE previous version in order to keep your settings.

Unfortunately, some settings are lost ! Check fonts and colors.This will be improved in the next release.

= 1.0.1 =
* Updated version as the initial release was not the actual one !
* Japanese language file added - Thanks to stranger-jp.
* Tested with WP 3.6.1

= 1.0 =
* Initial release - Unfortunately I made a mistake when importing the files !

== Upgrade Notice ==

= 1.2.2 =
* Just update

= 1.2.1 =
* Just update

= 1.2 =
* Update
* Check fonts setttings

= 1.1.1 =
* Deactivate
* Update
* Reactivate 

= 1.1 =
* DEACTIVATE previous version in order to keep your settings.
* Download, install and reactivate.
* Unfortunately, some settings are losts ! Check fonts and colors.
This will be improved in the next release.

= 1.0.1 =
Just upgrade and check your settings (page numbers width can now be set up).

== CSS ==

CSS is located in /wp-content/plugins/page-navi-slider/style/page-navi-slider.css

= List of used class =

* .wpns_wrapper : the wrapper of the plugin
* .wpns_container : a 'sub-wrapper' needed to set alignment, margins, ...
* .wpns_title : the caption
* .wpns_selector : everything but the caption
* .wpns_window : everyting of the selector but the slider
* .wpns_sliding_list : the list of page numbers
* .wpns_selector .wpns_element : elements of the list
* .wpns_selector .page-numbers : page numbers (the text inside each element)
* .wpns_first : first element of the list
* .wpns_last : last element of the list
* .wpns_active : the current element
* .wpns_inactive : the other elements (all except the current one)

== References ==

= Faitmain-Faitcoeur =
[Faitmain-Faitcoeur](http://www.faitmain-faitcoeur.fr/) The first site that has ever used page-navi-slider

= Stranger-JP =
Thanks to [stranger-jp](http://profiles.wordpress.org/stranger-jp) who translated in Japanese (and found some bug !)

= Jonhathan @ Geekpress =
Thanks to [Jonathan](http://www.geekpress.fr/wordpress/astuce/pagination-wordpress-sans-plugin-52/) for the pagination links tuto

= Wordpress Codex =
Settings page built according to theses [Wordpress Codex instructions](http://codex.wordpress.org/Creating_Options_Pages)

= PixToEm =
CSS sizes calculation made with [PxToEm](http://pxtoem.com/)

= StyleNeat =
CSS optimized with [StyleNeat](http://www.styleneat.com/)

= Write code online =
PHP tested with [Write code online](http://writecodeonline.com/php/)

= JS Fiddle =
js scripts tested with [JS Fiddle](http://jsfiddle.net/)

= JS compress =
JS Script minimized with [JS Compress](http://jscompress.com/)

= JSColor =
Color picker of the settings page by [JS Color](http://jscolor.com/)

= PoEdit =
Localization with [PoEdit](http://www.poedit.net/)

= jQuery Touch Punch =
jQuery slider adaptated for touchscreens by that [jQuery Touch Punch](http://touchpunch.furf.com/)

= jQuery and Co =
jQuery, jQuery UI, jQuery UI slider and accordion






