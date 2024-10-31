<?php
/*
Plugin Name: No Spam At All
Plugin URI: mrparagon.me/no-spam-at-all/
Description: "No Spam At All" prevents spam comments on your wordpress website/blog and Provides you with options to manage comments. You can go from 3,000 Spam comments per day to zero spam comments. 
Author: Kingsley Paragon
Version: 1.3
Requires at least: 3.5
Requires PHP: 5.6
Tested up to: 5.2.3
Author URI: mrparagon.me
license: GPLV2
Text Domain: no-spam-at-all
Domain Path: /languages/
*/
if (!defined('ABSPATH')) {
    exit;
}

function nsActivation()
{
    require_once plugin_dir_path(__FILE__).'/_nsaa/NSActivation.php';
    NSActivation::doActivation();
}

function nsDeactivation()
{
    require_once plugin_dir_path(__FILE__).'/_nsaa/NSDeactivation.php';
    NSDeactivation::doDeactivation();
}

register_activation_hook(__FILE__, 'nsActivation');
register_deactivation_hook(__FILE__, 'nsDeactivation');

/**
 * Let's Do it.
 */
require_once plugin_dir_path(__FILE__).'/_nsaa/NoSpamAtAll.php';
new NoSpamAtAll();