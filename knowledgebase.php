<?php

/**
 * Plugin Name: Knowledgebase
 * Plugin URI: http://hybopressthemes.com/plugins/knowledgebase
 * Description: A base plugin for building knowledgebase Web sites.
 * Version: 0.0.2
 * Author: Syed Abrar Ahmed Shah
 * Author URI: http://hybopressthemes.com
 * Text Domain: knowledgebase
 * Domain Path: /languages
 *
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    Knowledgebase
 * @version    0.0.1
 * @author     Syed Abrar Ahmed Shah <syed@hybopressthemes.com>
 * @copyright  Copyright (c) 2015, Syed Abrar Ahmed Shah
 * @link       http://hybopressthemes.com/plugins/knowledgebase
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit();
}

require_once( 'inc/class-knowledgebase.php' );

function Knowledgebase_get_instance() {
		return Knowledgebase::get_instance( __FILE__ );
}

Knowledgebase_get_instance();
