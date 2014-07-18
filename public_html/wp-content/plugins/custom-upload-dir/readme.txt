=== Custom Upload Dir ===
Contributors: ulfben
Donate link: http://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=11&y=10
Tags: upload, organize, files, media
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 3.4

Keeps your uploaded files organized in smart folder structures.

== Description ==
WordPress allow you to put uploads in year- and month-based folders. Custom Upload Dir is somewhat smarter. You can construct paths from variables like post title, 
category, post author, post date [and many more](http://wordpress.org/extend/plugins/custom-upload-dir/screenshots/). 

I created Custom Upload Dir ensure my file system is always mirroring the site structure. This ensures I can always find files manually when needed. It also simplifies backups and plattform migrations. 

[The screenshots might help explain](http://wordpress.org/extend/plugins/custom-upload-dir/screenshots/).

If you value [my plugins](http://profiles.wordpress.org/users/ulfben/), please help me out by [Flattr-ing them](http://flattr.com/thing/367557/Support-my-WordPress-plugins)! Or perhaps [send me a book](http://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=11&y=10)? Used ones are fine! :)

//*[Ulf Benjaminsson](http://profiles.wordpress.org/users/ulfben/)*

= Changelog v. 3.4 (2013-02-09) =

1. Added %file_ext% to sort by file type
2. Added [tentative support for uploads from WordPress apps](http://wordpress.org/support/topic/search-and-mobile-apps-compatibility?replies=6#post-3837193).
3. Removed setting for "Base upload folder" as WP core no longer exposes this
4. Cleared out some PHP error on the admin page
5. Fixed leaking CSS on admin page

= Changelog v. 3.3.1 (2011-12-27) =

1. Fixed poor sanitization of post slugs (%post_name%)
2. Added %post_parent%
3. Fixed settings not always initializing

= Changelog v. 3.3 (2011-12-18) =

1. Support for WordPress 3.3
2. Support for tags and categories
3. Support for custom taxonomies
4. Added %current_user%, %permalink%
5. Added all permalink tags
6. Added test-function to admin page

[Older changelogs moved here.](http://wordpress.org/extend/plugins/custom-upload-dir/changelog/)

== Changelog ==

= Changelog v. 3.4 (2013-02-09) =

1. Added %file_ext% to sort by file type
2. Added [tentative support for uploads from WordPress apps](http://wordpress.org/support/topic/search-and-mobile-apps-compatibility?replies=6#post-3837193).
3. Removed setting for "Base upload folder" as WP core no longer exposes this
4. Cleared out some PHP error on the admin page
5. Fixed leaking CSS on admin page

= Changelog v. 3.3.2 (2011-12-29) =

1. Updated init to use WordPress API properly
2. Removed dead code

= Changelog v. 3.3.1 (2011-12-27) =

1. Fixed poor sanitization of post slugs (%post_name%)
2. Added %post_parent%
3. Fixed settings not always initializing

= Changelog v. 3.3 (2011-12-18) =

1. Support for WordPress 3.3
2. Support for tags and categories
3. Support for custom taxonomies
4. Added %current_user%, %permalink%
5. Added all permalink tags
6. Added test-function to admin page

= Changelog v. 3.0.2 (2011-07-29) =

1. Fix for post_author not working
2. Fix timestamps from the media uploader ([thanks; alx359](http://wordpress.org/support/topic/plugin-custom-upload-dir-unattached-images-upload-to-19700101?replies=8))

**Changelog v. 3.0.1 (2010-11-01)**

1. [Fix for "undefined function get_userdata()"](http://wordpress.org/support/topic/plugin-custom-upload-dir-fatal-error-call-to-undefined-function-get_userdata) (thanks somatic!)

**Changelog v. 3.0 (2010-10-21)**

1. Rewrote entire plugin for WordPress 3.0
2. A slew of new placeholders for building path's with
3. A new interface to allow arbitrary strings in path
4. Error messages when uploading to un-named or un-dated posts
5. Added translation support

**Changelog v.2.5.4 (2008-12-31)**

1. Adjusted the test case. (Pedja)

**Changelog v.2.5.3 (2008-12-30)**

More problems have crept up due to the [massive amount of dry runs of 'upload_dir'](http://trac.wordpress.org/ticket/8753) in WP 2.7.

1. Added quick bail for every call that's not for a file upload. (props to Pedja Ljubomirovic (Canada))
	
**Changelog v.2.5.2 (2008-12-15)**

1. Fixed problem with plugin blocking deletion/editing of attached files.

**Changes in v.2.5.1 (2008-12-14)**

1. Fixed Custom Upload Dir breaking WP 2.7 when called from the loop

**Changes in v.2.5 (2008-11-15)**

1. Support for WP 2.7-beta1
1. Added 'post_author'	
1. Fixed 'post_title' (broken since WP 2.4)
1. Fixed issue with use_yearmonth overriding post date	
1. Tons of small(ish) updates
1. Removed Mediacaster-support

**Changes in v.2.0** (2007-10-19)
 
1. Mediacaster compability.
1. Added proper configuration screen
1. Massive code overhaul
1. Name change (previously "Daily Upload Dir")

== Upgrade Notice ==

= 3.4 =
Added %file_ext%, support for uploads from WordPress apps and fixed some errors.

= 3.3.2 = 
Removed dead code and updated to use WordPress API properly

= 3.3.1 = 
Added %post_parent% and some fixes

= 3.3 =
Support for WordPress 3.3 and many improvements!

= 3.0.2 =
Fixes post_author and and post_date behaviour in the media uploader.

= 3.0.1 = 
[Fix for "undefined function get_userdata()"](http://wordpress.org/support/topic/plugin-custom-upload-dir-fatal-error-call-to-undefined-function-get_userdata) (thanks somatic!)

= 3.0 =
Lots of new features and more flexible interface.

== Installation ==

1. Extract the `custom-upload-dir`-folder and transfer it to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Customize your directory structure in `Settings -> Custom Upload Dir`


== Screenshots ==

1. The configuration screen.
2. Shows how the upload path corresponds to the post's timestamp.


== Other Notes ==
Copyright (C) 2007-2013 Ulf Benjaminsson (my first name at ulfben dot com).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA