<?php
/*
Plugin Name: Special Text Boxes
Plugin URI: http://www.simplelib.com/archives/wordpress-plugin-wp-special-textboxes/
Description: Adds simple colored text boxes to highlight some portion of post text. Use it for highlights warnings, alerts, infos and downloads in your blog posts. Visit <a href="http://www.simplelib.com/">SimpleLib blog</a> for more details.
Version: 6.2.1
Author: minimus
Author URI: http://blogcoding.ru
Text Domain: wp-special-textboxes
*/

/*  Copyright 2009 - 2011, minimus  (email : minimus@simplelib.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
const STB_MAIN_FILE = __FILE__;

include_once('stb-block.php');
if (is_admin()) {
    include_once('stb-admin-class.php');
    if (class_exists("SpecialTextBoxes") && class_exists('SpecialTextBoxesAdmin')) {
        $stbObject = new SpecialTextBoxesAdmin();
    }
} else {
    include_once('stb-class.php');
    if (class_exists("SpecialTextBoxes")) {
        $stbObject = new SpecialTextBoxes();
        function stbHighlightText(string $content = null, string $id = 'warning', string $caption = '', array $atts = null): string
        {
            $block = new StbBlock(esc_html($content), esc_attr($id), esc_attr($caption), $atts);
            return $block->block;
        }
    }
}

if (class_exists("special_text")) {
    add_action('widgets_init', function () {
        register_widget("special_text");
    });
}

