<?php
/*
Plugin Name: Sanitize permalink with undescores
Plugin URI: http://www.melodycode.com
Description: Modifica il permalink affinch&egrave; ogni parola sia separata da un underscore "_" al posto del trattino "-" che Wordpress usa di default (si tratta solo di una leggera modifica alla funzione gi&agrave; esistente).
Version: 1
Author: Daniele Simonin
Author URI: http://www.melodycode.com
*/

function sanitize_permalink_with_undescores($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	$title = remove_accents($title);
	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '_', $title);
	$title = preg_replace('|_+|', '_', $title);
	$title = trim($title, '_');

	return $title;
}

remove_action('sanitize_title', 'sanitize_title_with_dashes');
add_action('sanitize_title', 'sanitize_permalink_with_undescores');
?>