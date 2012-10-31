<?php
/*
Plugin Name: Share my Tweet
Plugin URI: http://www.squidoo.com/share-my-tweet-wordpress-plugins
Description: Share my Tweet use TinyMCE to include various icons, button, cool effects and styles into your blog post.
Version: 1.1
Author: Gadgets Choose
Author URI: http://gadgets-code.com/category/how-to-tutorials
*/

/* Copyright 2013 Gadgets Choose (email : morning131@hotmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, please visit <http://www.gnu.org/licenses/>.
*/

/* Add Styles drop down box to the row of buttons */
add_filter( 'mce_buttons', 'add_extra_editor_styles' );

function add_extra_editor_styles($orig) {
    array_unshift ($orig, 'styleselect');
    return $orig;
}

/* Include new styles before tiny mce init */
add_filter( 'tiny_mce_before_init', 'share_my_tweet_editor_styles' );

function share_my_tweet_editor_styles( $init_array ) {

    $style_formats = array(
        array(
            'title' => 'Button Link',
            'selector' => 'a',
            'classes' => 'buttons'
        ),
        array(
            'title' => 'First Capital',
            'selector' => 'p',
            'classes' => 'capital'
        ),
        array(
            'title' => 'Division',
            'block' => 'div',
            'classes' => 'non-shadow-div',
            'wrapper' => true
        ),
        array(
            'title' => 'Shadow Division',
            'block' => 'div',
            'classes' => 'shadow-div',
            'wrapper' => true
        ),
         array(
            'title' => 'Round Division',
            'block' => 'div',
            'classes' => 'round-div',
            'wrapper' => true
        ),
        array(
            'title' => 'Gradient Division',
            'block' => 'div',
            'classes' => 'gradient-div',
            'wrapper' => true
        ),
         array(
            'title' => 'Hover Division',
            'block' => 'div',
            'classes' => 'hover-div',
            'wrapper' => true
        ),
        array(
            'title' => 'Video List',
            'selector' => 'li',
            'classes' => 'video'
        ),
        array(
            'title' => 'Photo List',
            'selector' => 'li',
            'classes' => 'photo'
        ),
        array(
            'title' => 'Folder List',
            'selector' => 'li',
            'classes' => 'folder'
        )
    );

    $init_array['style_formats'] = json_encode( $style_formats );

    return $init_array;
}

/* Include new style sheet to editor */
add_filter('mce_css', 'add_editor_new_css');

function add_editor_new_css() {
    return plugins_url( '/tinie-style.css' , __FILE__ );
}

/* Load style sheet */
add_action('wp_print_styles', 'add_share_my_tweet_stylesheet');

function add_share_my_tweet_stylesheet() {
    wp_register_style('shareMyTweetStyleSheets', plugins_url( '/tinie-style.css' , __FILE__ ));
    wp_enqueue_style( 'shareMyTweetStyleSheets');
}
?>