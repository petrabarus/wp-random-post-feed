<?php
/**
 * Plugin Name: Random Post Feed
 * Plugin URI: https://github.com/petrabarus/wp-random-post-feed
 * Description: Generate random post feed for wordpress
 * Version: 0.1
 * Author: Petra Barus
 * Author URI: http://petrabarus.net
 * License: GPL
 * @copyright Copyright (c) 2013 Petra Barus
 *
 * GNU General Public License, Free Software Foundation
 * <http://creativecommons.org/licenses/GPL/2.0/>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$wprpf_is_random = false;

register_activation_hook( __FILE__, 'wprpf_activate' );
function wprpf_activate(){
    add_option( 'wprpf_random_varname', 'random', '', 'yes' );
}

register_deactivation_hook ( __FILE__, 'wprpf_deactivate' );
function wprpf_deactivate(){
    delete_option( 'wprpf_random_varname' );
}

add_filter( 'query_vars', 'wprpf_query_vars' );
function wprpf_query_vars( $query_vars ){
    $query_vars[] =  get_option( 'wprpf_random_varname' );
    return $query_vars;
}

add_action( 'parse_request', 'wprpf_parse_request' );
function wprpf_parse_request( &$wp )
{
    global $wprpf_is_random;
    if ( array_key_exists( get_option( 'wprpf_random_varname' ), $wp->query_vars ) ) {
        $wprpf_is_random = true;
    }
    return;
}

add_action( 'pre_get_posts', 'wprpf_pre_get_posts' );
function wprpf_pre_get_posts( $query ){
    global $wprpf_is_random;
    if ( $wprpf_is_random ) {
        $query->set( 'orderby', 'rand' );
    }
}