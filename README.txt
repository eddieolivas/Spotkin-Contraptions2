=== Spotkin Contraptions ===
Contributors: Eddie Olivas
Donate link: http://contraptionmaker.com
Tags: comments
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin allows use of the contraptions shortcode which allows you to list contraptions, puzzles, and mods in the Contraption Maker database.ble, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the contents of `spotkin-contraptions.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php do_shortcode('[contraption]'); ?>` in your templates or simply add [contraption] to your pages

== Frequently Asked Questions ==

Contraption Shortcode Documentation

Quick examples:

[contraption] - Returns a list of 20 contraptions. 

[contraption type=”puzzle”] - Returns a list of 20 puzzles. 

[contraption date_range=”1/1/15, 4/20/15” limit=50] - Returns a list of 50 contraptions created between 1/1/15 and 4/20/15.


Arguments:

type - Can be: all, contraption, puzzle, mod. eg. [contraption type=”puzzle”]

size - Can be: small, medium, large. eg. [contraption size=”large”]

date_range - Can be a string with two dates separated by comma. eg. [contraption date_range=”1/2/14, 2/5/15”]

curation - Can be: editorschoice, recent, specific. eg. [contraption curation=”editorschoice”]

name - Only used if curation is set to “specific”. eg. [contraption curation=”specific” name=”Contraption1”]

user - The username of the item’s author. eg. [contraption user=”steamsync”]

sort_by - Can be: date, rating, user, name. eg. [contraption sort_by=”date”]

limit - A whole number. The max number of results returned. eg. [contraption limit=10]


2 use case examples:

1.) List the last 20 contraptions that were marked as editor’s choice.

[contraption curation=”editorschoice” type=”contraption” limit=20 sort_by=”date”]

2.) List the 50 highest rated puzzles created in March of 2014.

[contraption curation=”popular” date_range=”3/1/14, 3/31/14”]


