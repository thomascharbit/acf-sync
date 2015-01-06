ACF Sync
===============

> **Author Note** : Since 5.1.5, ACF now includes its own [manual syncing feature](http://www.advancedcustomfields.com/blog/acf-pro-v5-1-5-update/) !
> I recommend using this feature instead of the *ACF Sync* plugin for manual synchronization.
> *ACF Sync* is still relevant if you need automatic synchronization between different environments, especially on production instances where the ACF UI shouldn't be accessible to users.
> I will release soon a 2.0 version that will take advantage of the 5.1.5 features and will drop the manual synchronization feature

*ACF Sync* is a little WordPress plugin that let you keep your ACF field groups synchronized between different environments.

It ensures you to always have up-to-date fields across different development and production environments without the need to export/import your ACF fields manually.

This was inspired from this [nice article](http://seanbutze.com/automated-exporting-for-advanced-custom-fields/) from Sean Butze about deploying ACF field groups, except it has been adapted to work with ACF 5 and its new [local JSON Feature](http://www.advancedcustomfields.com/resources/local-json/).


### Installation

Install this plugin along with ACF > 5.0.0.

- Using git clone : `git clone https://github.com/FreshFlesh/ACF-Sync.git`
- Using composer : `composer require freshflesh/acf-sync`



### How does it work ?

To synchronize your field groups across your dev team, you need to version the JSON files produced by ACF with GIT or any other SCM tool.

See [here](http://www.advancedcustomfields.com/resources/local-json/) to learn more about the local JSON functionality and how to activate it on your website.

##### Auto Sync

You can automatically synchronize your field groups by defining a `ACF_FIELDS_VERSION` constant somewhere in your code.

It should contain your field groups version, following the [version_compare](http://php.net/manual/en/function.version-compare.php) php function format.

Example : `define('ACF_FIELDS_VERSION', '1.0.0');`

You might want to put this constant with your Custom Post Type definitions, for example : 
 - in a project core plugin
 - in a project config file
 - in your theme functions.php

Whenever you update your fields in the WordPress admin, update the `ACF_FIELDS_VERSION` constant to a new version ; for example `1.0.0` to `1.1.0`.

When another developer of your team will fetch your changes and new JSON files, *ACF Sync* will know they are newer than the ones in database, and will automatically import them back in database when accessing the WordPress Admin.

Of course *ACF Sync* can also be used to synchronize fields groups between development and production environments.


##### Manual Sync

Aside from the Auto Sync feature, *ACF Sync* lets you synchronize your field groups manually if your prefer to have more control on how and when field groups should be imported.

When activated, it simply add a new option on the Import/Export setting page of ACF.


### Environment based configuration

*ACF Sync* also optionally lets you use a `WP_ENV` constant to disable saving to JSON and hide the ACF UI on staging and production environment to avoid mistakes on non-development environments.

If you use the [Bedrock WordPress stack](http://roots.io/wordpress-stack/), `WP_ENV` is already defined in the config, otherwise you can define it yourself in your wp-config.php.


### Licence

MIT
