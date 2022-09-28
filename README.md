# WP Edit Username

**Contributors:** sajjad67 \
**Tags:** user,user-profile,profile-edit,edit,ajax,update,change-username,username \
**Requires at least:** 5.6 \
**Tested up to:** 6.0 \
**Stable tag:** trunk \
**License:** GPLv2 \
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Easily Edit User Profile Username clicking a button.

## Description

This plugin adds feature to edit/change user username.

### Features:

- Edit UserName
- Only Who can edit_other_users() capability can edit username.
- On change username a email would send to the user email about username change if send email is checked!
- You can modify email text & subject in admin dashboard & via filter hook
- Email Subject Change via filter `wp_username_changed_email_subject`.
- Email Body Text Change via filter ($new_username & $old_username are always prepended to the email text) `wp_username_changed_email_body`.
### Hooks Usage:

`<?php

add_filter( "wp_username_changed_email_subject", "your_function" );
your_function($subject){
	$subject = 'Your customized subject';
	return $subject;
}

add_filter( "wp_username_changed_email_body", "your_function" );
function your_function($$old_username,$new_username){
	
	$email_body = "Your custom email text body.";
	return $email_body;
}

?>`

**Interested in contributing to WP Edit Username?**
Contact me sagorh672(at)gmail.com

## Installation

To add a WordPress Plugin using the built-in plugin installer:

Go to Plugins > Add New.

1. Type in the name "WP Edit Username" in Search Plugins box
2. Find the "WP Edit Username" Plugin to install.
3. Click Install Now to begin the plugin installation.
4. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

To add a WordPress Plugin from github repo / plugin zip file :
1. Go to wordpress plugin page
2. Click Add New & Upload Plugin
3. Drag / Click upload the plugin zip file
4. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

## Frequently Asked Questions

### How to use this plugin?

Just after installing WP Edit Username plugin, Go to user profile and edit user username by clicking Edit button.

Update inputs according to your requirement and you are good to go.

## Screenshots

### 1. Settings panel for WP Edit Username Plugin.

![Settings panel for WP Edit Username Plugin.](https://ps.w.org/wp-edit-username/assets/screenshot-1.png)

### 2. Username Edit Button.

![Username Edit Button.](https://ps.w.org/wp-edit-username/assets/screenshot-2.png)

### 3. New Username Input Field.

![New Username Input Field.](https://ps.w.org/wp-edit-username/assets/screenshot-3.png)

### 4. After Username Changed Message.

![After Username Changed Message.](https://ps.w.org/wp-edit-username/assets/screenshot-4.png)


## Changelog

### 1.0.3

- Checked for latest wp version & updated coding styles... major changes nothing
### 1.0.2

- Checked for latest wp version & updated coding styles... major changes nothing
### 1.0.1

- Checked for latest wp version & updated coding styles... major changes nothing
### 1.0.0

- Initial release.

## Upgrade Notice

Always try to keep your plugin update so that you can get the improved and additional features added to this plugin up to date.
