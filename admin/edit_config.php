<?php
/**
 * @package       mds
 * @copyright     (C) Copyright 2020 Ryan Rhode, All rights reserved.
 * @author        Ryan Rhode, ryan@milliondollarscript.com
 * @version       2020.05.13 12:41:15 EDT
 * @license       This program is free software; you can redistribute it and/or modify
 *        it under the terms of the GNU General Public License as published by
 *        the Free Software Foundation; either version 3 of the License, or
 *        (at your option) any later version.
 *
 *        This program is distributed in the hope that it will be useful,
 *        but WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *        GNU General Public License for more details.
 *
 *        You should have received a copy of the GNU General Public License along
 *        with this program;  If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 *
 *  * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *        Million Dollar Script
 *        A pixel script for selling pixels on your website.
 *
 *        For instructions see README.txt
 *
 *        Visit our website for FAQs, documentation, a list team members,
 *        to post any bugs or feature requests, and a community forum:
 *        https://milliondollarscript.com/
 *
 */

require( 'admin_common.php' );

foreach ( $_REQUEST as $key => $val ) {
	$_REQUEST[ $key ] = $val;
}

$rootpathinfo = pathinfo( '../' );
$BASE_PATH    = $rootpathinfo['dirname'];

require_once __DIR__ . '/../include/version.php';
$version = get_mds_version();

$defaults = array(
	'DEBUG'                       => false,
	'MDS_LOG'                     => false,
	'MDS_LOG_FILE'                => $BASE_PATH . '/.mds.log',
	'VERSION_INFO'                => $version,
	'BASE_HTTP_PATH'              => '/',
	'BASE_PATH'                   => $BASE_PATH . '/',
	'SERVER_PATH_TO_ADMIN'        => __DIR__,
	'UPLOAD_PATH'                 => $BASE_PATH . '/upload_files/',
	'UPLOAD_HTTP_PATH'            => $BASE_PATH . '/upload_files/',
	'SITE_CONTACT_EMAIL'          => 'test@example.com',
	'SITE_LOGO_URL'               => 'https://milliondollarscript.com/logo.gif',
	'SITE_NAME'                   => 'Million Dollar Script ' . $version,
	'SITE_SLOGAN'                 => 'This is the Million Dollar Script Example. 1 pixel = 1 cent',
	'MDS_RESIZE'                  => 'YES',
	'MYSQL_HOST'                  => '',
	'MYSQL_USER'                  => '',
	'MYSQL_PASS'                  => '',
	'MYSQL_DB'                    => '',
	'MYSQL_PORT'                  => 3306,
	'MYSQL_SOCKET'                => '',
	'ADMIN_PASSWORD'              => 'ok',
	'DATE_FORMAT'                 => 'Y-M-d',
	'GMT_DIF'                     => date_default_timezone_get(),
	'DATE_INPUT_SEQ'              => 'YMD',
	'OUTPUT_JPEG'                 => 'N',
	'JPEG_QUALITY'                => '75',
	'INTERLACE_SWITCH'            => 'YES',
	'USE_LOCK_TABLES'             => 'Y',
	'BANNER_DIR'                  => 'pixels/',
	'DISPLAY_PIXEL_BACKGROUND'    => 'NO',
	'EMAIL_USER_ORDER_CONFIRMED'  => 'YES',
	'EMAIL_ADMIN_ORDER_CONFIRMED' => 'YES',
	'EMAIL_USER_ORDER_COMPLETED'  => 'YES',
	'EMAIL_ADMIN_ORDER_COMPLETED' => 'YES',
	'EMAIL_USER_ORDER_PENDED'     => 'YES',
	'EMAIL_ADMIN_ORDER_PENDED'    => 'YES',
	'EMAIL_USER_ORDER_EXPIRED'    => 'YES',
	'EMAIL_ADMIN_ORDER_EXPIRED'   => 'YES',
	'EM_NEEDS_ACTIVATION'         => 'YES',
	'EMAIL_ADMIN_ACTIVATION'      => 'YES',
	'EMAIL_ADMIN_PUBLISH_NOTIFY'  => 'YES',
	'USE_PAYPAL_SUBSCR'           => 'NO',
	'EMAIL_USER_EXPIRE_WARNING'   => '',
	'EMAILS_DAYS_KEEP'            => '30',
	'DAYS_RENEW'                  => '7',
	'DAYS_CONFIRMED'              => '7',
	'HOURS_UNCONFIRMED'           => '1',
	'DAYS_CANCEL'                 => '3',
	'ENABLE_MOUSEOVER'            => 'POPUP',
	'ENABLE_CLOAKING'             => 'YES',
	'VALIDATE_LINK'               => 'NO',
	'ADVANCED_CLICK_COUNT'        => 'YES',
	'USE_SMTP'                    => '',
	'EMAIL_SMTP_SERVER'           => '',
	'EMAIL_SMTP_USER'             => '',
	'EMAIL_SMTP_PASS'             => '',
	'EMAIL_SMTP_AUTH_HOST'        => '',
	'SMTP_PORT'                   => '465',
	'POP3_PORT'                   => '995',
	'EMAIL_TLS'                   => '1',
	'EMAIL_POP_SERVER'            => '',
	'EMAIL_POP_BEFORE_SMTP'       => 'NO',
	'EMAIL_DEBUG'                 => 'NO',
	'EMAILS_PER_BATCH'            => '12',
	'EMAILS_MAX_RETRY'            => '15',
	'EMAILS_ERROR_WAIT'           => '20',
	'USE_AJAX'                    => 'SIMPLE',
	'ANIMATION_SPEED'             => '50',
	'MAX_BLOCKS'                  => '',
	'MEMORY_LIMIT'                => '128M',
	'REDIRECT_SWITCH'             => 'NO',
	'REDIRECT_URL'                => 'http://www.example.com',
	'HIDE_TIMEOUT'                => '500',
	'MDS_AGRESSIVE_CACHE'         => 'NO',
	'ERROR_REPORTING'             => 0,
	'WP_ENABLED'                  => 'NO',
	'WP_URL'                      => '',
);

$values = array_replace( $defaults, $_REQUEST );

if ( isset( $_REQUEST['save'] ) && $_REQUEST['save'] != '' ) {
	echo "Updating config....";
	$config_str = "<?php

#########################################################################
# CONFIGURATION
# Note: Please do not edit this file. Edit the config from the admin section.
#########################################################################

error_reporting( " . $values['ERROR_REPORTING'] . " );
define( 'DEBUG', " . ( $values['DEBUG'] ? 'true' : 'false' ) . " );
define( 'MDS_LOG', " . ( $values['MDS_LOG'] ? 'true' : 'false' ) . " );
define( 'MDS_LOG_FILE', '" . $values['MDS_LOG_FILE'] . "' );
define( 'VERSION_INFO', '" . $values['VERSION_INFO'] . "' );
define( 'BASE_HTTP_PATH', '" . $values['BASE_HTTP_PATH'] . "' );
define( 'BASE_PATH', '" . $values['BASE_PATH'] . "' );
define( 'SERVER_PATH_TO_ADMIN', '" . $values['SERVER_PATH_TO_ADMIN'] . "' );
define( 'UPLOAD_PATH', '" . $values['UPLOAD_PATH'] . "' );
define( 'UPLOAD_HTTP_PATH', '" . $values['UPLOAD_HTTP_PATH'] . "' );
define( 'SITE_CONTACT_EMAIL', '" . $values['SITE_CONTACT_EMAIL'] . "' );
define( 'SITE_LOGO_URL', '" . $values['SITE_LOGO_URL'] . "' );
define( 'SITE_NAME', '" . $values['SITE_NAME'] . "' );
define( 'SITE_SLOGAN', '" . $values['SITE_SLOGAN'] . "' );
define( 'MDS_RESIZE', '" . $values['MDS_RESIZE'] . "' );
define( 'MYSQL_HOST', '" . $values['MYSQL_HOST'] . "' );
define( 'MYSQL_USER', '" . $values['MYSQL_USER'] . "' );
define( 'MYSQL_PASS', '" . $values['MYSQL_PASS'] . "' );
define( 'MYSQL_DB', '" . $values['MYSQL_DB'] . "' );
define( 'MYSQL_PORT', " . $values['MYSQL_PORT'] . " );
define( 'MYSQL_SOCKET', '" . $values['MYSQL_SOCKET'] . "' );
define( 'ADMIN_PASSWORD', '" . $values['ADMIN_PASSWORD'] . "' );
define( 'DATE_FORMAT', '" . $values['DATE_FORMAT'] . "' );
define( 'GMT_DIF', '" . $values['GMT_DIF'] . "' );
define( 'DATE_INPUT_SEQ', '" . $values['DATE_INPUT_SEQ'] . "' );
define( 'OUTPUT_JPEG', '" . $values['OUTPUT_JPEG'] . "' );
define( 'JPEG_QUALITY', '" . $values['JPEG_QUALITY'] . "' );
define( 'INTERLACE_SWITCH', '" . $values['INTERLACE_SWITCH'] . "' );
define( 'BANNER_DIR', '" . $values['BANNER_DIR'] . "' );
define( 'DISPLAY_PIXEL_BACKGROUND', '" . $values['DISPLAY_PIXEL_BACKGROUND'] . "' );
define( 'EMAIL_USER_ORDER_CONFIRMED', '" . $values['EMAIL_USER_ORDER_CONFIRMED'] . "' );
define( 'EMAIL_ADMIN_ORDER_CONFIRMED', '" . $values['EMAIL_ADMIN_ORDER_CONFIRMED'] . "' );
define( 'EMAIL_USER_ORDER_COMPLETED', '" . $values['EMAIL_USER_ORDER_COMPLETED'] . "' );
define( 'EMAIL_ADMIN_ORDER_COMPLETED', '" . $values['EMAIL_ADMIN_ORDER_COMPLETED'] . "' );
define( 'EMAIL_USER_ORDER_PENDED', '" . $values['EMAIL_USER_ORDER_PENDED'] . "' );
define( 'EMAIL_ADMIN_ORDER_PENDED', '" . $values['EMAIL_ADMIN_ORDER_PENDED'] . "' );
define( 'EMAIL_USER_ORDER_EXPIRED', '" . $values['EMAIL_USER_ORDER_EXPIRED'] . "' );
define( 'EMAIL_ADMIN_ORDER_EXPIRED', '" . $values['EMAIL_ADMIN_ORDER_EXPIRED'] . "' );
define( 'EM_NEEDS_ACTIVATION', '" . $values['EM_NEEDS_ACTIVATION'] . "' );
define( 'EMAIL_ADMIN_ACTIVATION', '" . $values['EMAIL_ADMIN_ACTIVATION'] . "' );
define( 'EMAIL_ADMIN_PUBLISH_NOTIFY', '" . $values['EMAIL_ADMIN_PUBLISH_NOTIFY'] . "' );
define( 'USE_PAYPAL_SUBSCR', '" . $values['USE_PAYPAL_SUBSCR'] . "' );
define( 'EMAIL_USER_EXPIRE_WARNING', '" . $values['EMAIL_USER_EXPIRE_WARNING'] . "' );
define( 'EMAILS_DAYS_KEEP', '" . $values['EMAILS_DAYS_KEEP'] . "' );
define( 'DAYS_RENEW', '" . $values['DAYS_RENEW'] . "' );
define( 'DAYS_CONFIRMED', '" . $values['DAYS_CONFIRMED'] . "' );
define( 'HOURS_UNCONFIRMED', '" . $values['HOURS_UNCONFIRMED'] . "' );
define( 'DAYS_CANCEL', '" . $values['DAYS_CANCEL'] . "' );
define( 'ENABLE_MOUSEOVER', '" . $values['ENABLE_MOUSEOVER'] . "' );
define( 'ENABLE_CLOAKING', '" . $values['ENABLE_CLOAKING'] . "' );
define( 'VALIDATE_LINK', '" . $values['VALIDATE_LINK'] . "' );
define( 'ADVANCED_CLICK_COUNT', '" . $values['ADVANCED_CLICK_COUNT'] . "' );
define( 'USE_SMTP', '" . $values['USE_SMTP'] . "' );
define( 'EMAIL_SMTP_SERVER', '" . $values['EMAIL_SMTP_SERVER'] . "' );
define( 'EMAIL_SMTP_USER', '" . $values['EMAIL_SMTP_USER'] . "' );
define( 'EMAIL_SMTP_PASS', '" . $values['EMAIL_SMTP_PASS'] . "' );
define( 'EMAIL_SMTP_AUTH_HOST', '" . $values['EMAIL_SMTP_AUTH_HOST'] . "' );
define( 'SMTP_PORT', '" . $values['SMTP_PORT'] . "' );
define( 'POP3_PORT', '" . $values['POP3_PORT'] . "' );
define( 'EMAIL_TLS', '" . $values['EMAIL_TLS'] . "' );
define( 'EMAIL_POP_SERVER', '" . $values['EMAIL_POP_SERVER'] . "' );
define( 'EMAIL_POP_BEFORE_SMTP', '" . $values['EMAIL_POP_BEFORE_SMTP'] . "' );
define( 'EMAIL_DEBUG', '" . $values['EMAIL_DEBUG'] . "' );
define( 'EMAILS_PER_BATCH', '" . $values['EMAILS_PER_BATCH'] . "' );
define( 'EMAILS_MAX_RETRY', '" . $values['EMAILS_MAX_RETRY'] . "' );
define( 'EMAILS_ERROR_WAIT', '" . $values['EMAILS_ERROR_WAIT'] . "' );
define( 'USE_AJAX', '" . $values['USE_AJAX'] . "' );
define( 'ANIMATION_SPEED', '" . $values['ANIMATION_SPEED'] . "' );
define( 'MAX_BLOCKS', '" . $values['MAX_BLOCKS'] . "' );
define( 'MEMORY_LIMIT', '" . $values['MEMORY_LIMIT'] . "' );
define( 'REDIRECT_SWITCH', '" . $values['REDIRECT_SWITCH'] . "' );
define( 'REDIRECT_URL', '" . $values['REDIRECT_URL'] . "' );
define( 'HIDE_TIMEOUT', '" . $values['HIDE_TIMEOUT'] . "' );
define( 'MDS_AGRESSIVE_CACHE', '" . $values['MDS_AGRESSIVE_CACHE'] . "' );
define( 'ERROR_REPORTING', " . $values['ERROR_REPORTING'] . " );
define( 'WP_ENABLED', '" . $values['WP_ENABLED'] . "' );
define( 'WP_URL', '" . $values['WP_URL'] . "' );
";
	// write out the config..

	$file = fopen( "../config.php", "w" );
	fwrite( $file, $config_str );
}

require_once __DIR__ . "/../include/init.php";

?>

<h3>Main Configuration</h3>
<p>Options on this page affect the running of the pixel advertising system.</p>
<p>Note: <i>Make sure that config.php has write permissions <b>turned on</b> when editing this form. You should turn off write permission after editing this form.</i></p>
<p><b>Tip:</b> Looking for where to settings for the grid? It is set in 'Pixel Inventory' -> <a href="inventory.php">Manage Grids</a>. Click on Edit to edit the grid parameters.</p>
<p>
	<?php
	if ( is_writable( "../config.php" ) ) {
		echo "- config.php is writeable.";
	} else {
		echo "- <font color='red'> Note: config.php is not writable. Give write permissions to config.php if you want to save the changes</font>";
	}

	require( __DIR__ . '/config_form.php' );
	?>
</p>
