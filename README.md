ACF Sync
===============

ACF Sync is a little WordPress plugin that let you keep your ACF field groups synchronized between different environments.

It ensures you to always have up-to-date fields across different development and production environments without the need to export/import your ACF fields manually.

This was inspired from this [nice article](http://seanbutze.com/automated-exporting-for-advanced-custom-fields/) from Sean Butze about deploying ACF field groups, except it has been adapted to work with ACF 5 and its new [local JSON Feature](http://www.advancedcustomfields.com/resources/local-json/).


### Installation

- install this plugin along with ACF > 5.0.0 
- define a `ACF_FIELDS_VERSION` constant somewhere in your code. You might want to put this with your Custom Post Type definitions, for example : 
-- in a project core plugin
-- in a project config file
-- in your theme functions.php


### How does it work ?

To sync your fields groups across your dev team, you need to version the JSON files produced by ACF.

See [here](http://www.advancedcustomfields.com/resources/local-json/) to learn more about local JSON functionality and how to activate it on your website.

Whenever you update your fields in the WordPress admin, update the `ACF_FIELDS_VERSION` constant to a new version ; for example `1.0.0` to `1.1.0`.

When another developer of your team will fetch your changes and new JSON files, the plugin will know they are newer than the ones in database, and will automatically import them back in database when accessing the WordPress Admin.

Of course fields groups can also be synchronized between development and production environments.

This plugin also optionnally let you use a WP_ENV constant to disable saving to JSON and hide the ACF UI on staging and production environment to avoid mistakes on non-development environments.

If you use the [Bedrock](http://roots.io/wordpress-stack/) WordPress stack, WP_ENV is already defined in the config, otherwise you can define it yourself in your wp-config.php.


### Licence

MIT


