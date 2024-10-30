<?php

/*
Plugin Name: WordPress Mobile by Mobify (<a href="http://www.mobify.com">http://www.mobify.com</a>)
Plugin URI: http://www.mobify.com
Description: Unmaintained plugin for Mobify Studio.  Please visit www.mobify.com for our latest solutions for Wordpress and all other CMS & e-Commerce engines.
Version: 2.0
Author: Peter McLachlan
Author URI: http://www.mobify.com
*/

/*  
    Derived from work by Robin Jewsbury and Mike Rowehl
    Copyright 2009 (email : admin@mobify.me)

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


/*
plugin based on: Mobilize by Mippin Wordpress Plugin.  Copyright for mippin components : 
	Copyright 2008 (email : info@Mippin.com)

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

define(CNAME_UNSET, '##########');
$MOBIFY_REDIRECT_DEFAULT=CNAME_UNSET;
$MOBIFY_PARAM_NAME='mobify';
$MOBIFY_COOKIE_NAME='mobify';
$MOBIFY_COOKIE_EXPIRE=time() + 60 * 60 * 2; # * 14;  # default 2 hour expiry

## Debugging:
$LOGFILENAME='/tmp/mobify_wp.log';
$DEBUG=0;

function mobify_is_mobile_device() { 
	$isMobile = false;

	$op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
	$no = strtolower($_SERVER['HTTP_X_MOBILE_GATEWAY']);
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ac = strtolower($_SERVER['HTTP_ACCEPT']);
	$ip = $_SERVER['REMOTE_ADDR'];

    ## first check for simple mobile-only headers:
    if ($op != '' || $no != '') {
        return true;
    }
        
	if (strpos($ua, 'intel mac os x') !== false
		|| strpos($ua, 'ppc mac os x') !== false
        || strpos($ua, 'mac_powerpc') !== false
        || strpos($ua, 'sunos') !== false
        || strpos($ua, 'windows nt') !== false
        || strpos($ua, 'windows 98') !== false
        || strpos($ua, 'ipad') !== false
        || strpos($ua, 'winnt') !== false ) {
            ## filter known not-mobile UAs
			return false;    
		}
    
	$isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
		|| strpos($ua, 'alcatel') !== false 
		|| strpos($ua, 'android') !== false 
		|| strpos($ua, 'audiovox') !== false 
		|| strpos($ua, 'au-mic') !== false 
		|| strpos($ua, 'avantgo') !== false 
		|| strpos($ua, 'bolt') !== false 
		|| strpos($ua, 'blackberry') !== false 
		|| strpos($ua, 'blazer') !== false 
		|| strpos($ua, 'cldc-') !== false 
		|| strpos($ua, 'danger') !== false 
		|| strpos($ua, 'docomo') !== false 
		|| strpos($ua, 'dopod') !== false 
		|| strpos($ua, 'epoc') !== false 
		|| strpos($ua, 'ericsson') !== false 
		|| strpos($ua, 'Google Wireless Transcoder') !== false 
		|| strpos($ua, 'hiptop') !== false 
		|| strpos($ua, 'htc') !== false 
		|| strpos($ua, 'huawei') !== false 
		|| strpos($ua, 'iemobile') !== false 
		|| strpos($ua, 'ipaq') !== false 
		|| strpos($ua, 'iphone') !== false 
		|| strpos($ua, 'ipod') !== false 
		|| strpos($ua, 'j2me') !== false 
		|| strpos($ua, 'lg') !== false 
		|| strpos($ua, 'midp') !== false 
		|| strpos($ua, 'mobile') !== false 
		|| strpos($ua, 'mot') !== false 
		|| strpos($ua, 'moto') !== false 
		|| strpos($ua, 'motorola') !== false 
		|| strpos($ua, 'nec-') !== false 
		|| strpos($ua, 'netfront') !== false 
		|| strpos($ua, 'netfront') !== false 
		|| strpos($ua, 'nitro') !== false 
		|| strpos($ua, 'nokia') !== false 
		|| strpos($ua, 'novarra-vision') !== false 
		|| strpos($ua, 'opera mini') !== false 
		|| strpos($ua, 'palm') !== false 
		|| strpos($ua, 'palmsource') !== false 
		|| strpos($ua, 'panasonic') !== false 
		|| strpos($ua, 'philips') !== false 
		|| strpos($ua, 'pocketpc') !== false 
		|| strpos($ua, 'portalmmm') !== false 
		|| strpos($ua, 'rover') !== false 
		|| strpos($ua, 'sagem') !== false 
		|| strpos($ua, 'samsung') !== false 
		|| strpos($ua, 'sanyo') !== false 
		|| strpos($ua, 'sec') !== false 
		|| strpos($ua, 'series60') !== false 
		|| strpos($ua, 'sharp') !== false 
		|| strpos($ua, 'sie-') !== false 
		|| strpos($ua, 'smartphone') !== false 
		|| strpos($ua, 'sony') !== false 
		|| strpos($ua, 'symbian') !== false 
		|| strpos($ua, 't-mobile') !== false 
		|| strpos($ua, 'untrusted') !== false 
		|| strpos($ua, 'up.browser') !== false 
		|| strpos($ua, 'up.link') !== false 
		|| strpos($ua, 'vodafone/') !== false 
		|| strpos($ua, 'wap1.') !== false 
		|| strpos($ua, 'wap2.') !== false 
		|| strpos($ua, 'webOS') !== false
		|| strpos($ua, 'windows ce') !== false;

    return $isMobile;
}


function mobify_url() {
    # strip out mobify_param_name
    global $MOBIFY_PARAM_NAME;
    $url = 'http://' . get_option('mobify_redirect_base') . $_SERVER['REQUEST_URI'];
    $url = str_replace($MOBIFY_PARAM_NAME . '=1', '', $url); 
    $url = str_replace($MOBIFY_PARAM_NAME . '=0', '', $url);
    return $url;
}

function mobify_log_to_file($msg)
{ 
    global $DEBUG;
    global $LOGFILENAME;
  
    if ($DEBUG < 1) {
        return;
    }
    
	// open file
	$fd = fopen($LOGFILENAME, "a");
	
	// write string
	fwrite($fd, $msg . "\n");
	
	// close file
	fclose($fd);
}


function mobify_cookie_value() { 
    global $MOBIFY_COOKIE_NAME;
    if (isset($_COOKIE[$MOBIFY_COOKIE_NAME])) {
        if ($_COOKIE[$MOBIFY_COOKIE_NAME] == '1') {
            return 1;
        } else { 
            return 0;
        }
    } else { 
        return -1;
    }
}

function mobify_param_value() {
    global $MOBIFY_PARAM_NAME;
    if (isset($_GET[$MOBIFY_PARAM_NAME])) {
        if ($_GET[$MOBIFY_PARAM_NAME] == '1') { 
            return 1;
        } else { 
            return 0;
        }
    } else { 
        return -1;
    }
}

function mobify_redirect() {
    global $MOBIFY_COOKIE_NAME;
    global $MOBIFY_COOKIE_EXPIRE;
    
    if (CNAME_UNSET == get_option('mobify_redirect_base')) { 
        return;
    }
    
    if (strpos($_SERVER['REQUEST_URI'], "xmlrpc.php")) {
        return;
    }
        
    $mobify_param = mobify_param_value();
    $mobify_cookie = mobify_cookie_value();
    // get parameter has highest priority
    if ($mobify_param == 1) { 
        go_mobile();
    } elseif ($mobify_param == 0) { 
        mobify_go_desktop();
        return;
    } 
    // mobify_param is not set, check out cookie setting
    if ($mobify_cookie == 1 ) { 
        go_mobile();
    } elseif ($mobify_cookie == 0) { 
        mobify_go_desktop();
        return;
    }
    // nothing is set, do autodetection
    if (mobify_is_mobile_device()) { 
        go_mobile();
    } 
    
    mobify_go_desktop();
    return;
}

function mobify_get_cookie_domain() { 
    $groups = null;
    preg_match("/(?P<domain>[\w+]+\.[\w]+)$/", get_option('mobify_redirect_base'), $groups);    
    return "." . $groups[0];
}

function go_mobile() { 
    global $MOBIFY_COOKIE_NAME;
    global $MOBIFY_COOKIE_EXPIRE;
    $cookie_domain = mobify_get_cookie_domain();
    
    // set cookie, then redirect
    # mobify_log_to_file('set mobify cookie for domain' . $cookie_domain . " cookie name: " . $MOBIFY_COOKIE_NAME . ' to value 1');
    setcookie($MOBIFY_COOKIE_NAME, '1', $MOBIFY_COOKIE_EXPIRE, '/', $cookie_domain);
    header('Location: ' . mobify_url());
    exit();        
}

function mobify_go_desktop() { 
    global $MOBIFY_COOKIE_NAME;
    global $MOBIFY_COOKIE_EXPIRE;
    $cookie_domain = mobify_get_cookie_domain();    
    
    # mobify_log_to_file('set mobify cookie for domain ' . $cookie_domain . " cookie name: " . $MOBIFY_COOKIE_NAME . ' to value 0');
    setcookie($MOBIFY_COOKIE_NAME, '0', $MOBIFY_COOKIE_EXPIRE, '/', $cookie_domain);
}


function mobify_admin() {
	if (function_exists('add_submenu_page')) {
		add_options_page('Mobify Setup', 'Mobify', 10, basename(__FILE__), 'mobify_admin_page');
	}
}

function mobify_admin_page() {
	if (isset($_POST['mobify_options_submit'])) {
		update_option('mobify_redirect_base', $_POST['mobify_redirect_base']);

		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Options saved.');
		echo '</strong></p></div>';
	}

?>
	<div class="wrap">
	<h2>Mobify Options Page</h2>

	<form name="mobify_options_form" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>" method="post">

		The Mobify wordpress plugin requires that you set the mobile domain name (CNAME) that you configured with Mobify.  This is typically 'm.yoursite.com'.  
	<ul style="width:75%">

    <li><strong>CNAME</strong>: <input type="text" name="mobify_redirect_base" value="<?php echo get_option('mobify_redirect_base');?>" /></li>

	</ul>

	<div class="submit" style="float:right">
	<input type="submit" name="mobify_options_submit" value="<?php _e('Update Options &raquo;') ?>"/>
	</div>
	</form>
<?php
}

function mobify_widget_init() {
    global $MOBIFY_REDIRECT_DEFAULT;
	if ( !function_exists('register_sidebar_widget') ) {
		return;
	}
	$mobify_redirect_base = get_option('mobify_redirect_base');
	if ($mobify_redirect_base == false or empty($mobify_redirect_base) or $mobify_redirect_base == '' ) { 
	    add_option('mobify_redirect_base', $value = $MOBIFY_REDIRECT_DEFAULT, $deprecated = '', $autoload = 'yes');
	    update_option('mobify_redirect_base', $MOBIFY_REDIRECT_DEFAULT);
	}

	function mobify_widget($args) {
	    extract($args);
	    echo $before_widget;
	    echo $before_title.'Mobile Version'.$after_title;
	    echo "<a href='" . mobify_url() . "'>Switch to mobile view:</a>";
	    echo "<a href='" . mobify_url() . "'><img style='border:none;'  src='http://www.mippin.com/app/images/blogger_button.gif' /></a><br />";
	    echo $after_widget;
	}
	
	register_sidebar_widget('Mobify Widget', 'mobify_widget');
}

# add_action('template_redirect', 'mobify_redirect', 0);
add_action('init', 'mobify_redirect', 0);
add_action('admin_menu', 'mobify_admin');
add_action('plugins_loaded', 'mobify_widget_init');

?>
