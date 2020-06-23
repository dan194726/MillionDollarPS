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

// Type of AJAX call can be POST or JSON input

require_once __DIR__ . "/include/init.php";

// Handle WP integration calls
if ( WP_ENABLED == "YES" && ! empty( WP_URL ) ) {
	header( 'Access-Control-Allow-Origin: ' . WP_URL );
	header( 'Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS' );
	header( 'Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, X-Requested-With' );
}

// Handle POST input
if ( isset( $_POST ) ) {

	// Get input
	$input = file_get_contents( 'php://input' );

	// Try to json_decode the input
	$data = json_decode( $input );
	$data_array = array();

	if ( empty($data) ) {
		// Invalid JSON sent so try parsing it

		parse_str( $input, $data_array );
		if ( ! isset( $data_array['action'] ) ) {
			die;
		}
	}

	$action = isset( $data_array['action'] ) ? $data_array['action'] : ( isset( $data->action ) ? $data->action : null );

	// Handle core MDS AJAX calls
	if ( isset( $action ) ) {
		switch ( $action ) {
			case "show_grid":
				show_grid();
				die;
				break;
			case "show_stats":
				show_stats();
				die;
				break;
			case "ga":
				get_ad( $data_array );
				die;
				break;
			default:
				break;
		}

		// Handle WP integration calls
		if ( WP_ENABLED == "YES" && ! empty( WP_URL ) ) {
			if ( ! isset( $data->grid_id ) ) {
				die;
			}

			$_REQUEST['BID'] = $data->grid_id;

			switch ( $data->action ) {
				case "ajax_grid":
					$_REQUEST['type'] = 'grid';
					require_once( BASE_PATH . "/include/mds_ajax.php" );
					$mds_ajax = new Mds_Ajax();
					$mds_ajax->show( 'grid', $data->grid_id, 'grid' );
					break;
				case "ajax_stats":
					$_REQUEST['type'] = 'stats';
					require_once( BASE_PATH . "/include/mds_ajax.php" );
					$mds_ajax = new Mds_Ajax();
					$mds_ajax->show( 'stats', $data->grid_id, 'stats' );
					break;
				case "users":
					require_once( BASE_PATH . "/users/index.php" );
					break;
				default:
					break;
			}
		}

		global $ajax_call;
		$ajax_call = true;
	}

	die;
}

function show_grid() {
	global $f2;

	$BID = $f2->bid();

	if ( ! is_numeric( $BID ) ) {
		die;
	}

	$banner_data = load_banner_constants( $BID );

	if ( BANNER_DIR == 'BANNER_DIR' ) {
		$BANNER_DIR = "banners/";
	} else {
		$BANNER_DIR = BANNER_DIR;
	}

	$BANNER_PATH = BASE_PATH . "/" . $BANNER_DIR;

	$map_file = get_map_file_name( $BID );

	if ( ! file_exists( $map_file ) ) {
		process_map( $BID, $map_file );
	}

	include_once( $map_file );

	$ext = 'png';
	if ( OUTPUT_JPEG == 'Y' ) {
		$ext = "jpg";
	} else if ( OUTPUT_JPEG == 'N' ) {
		$ext = 'png';
	} else if ( OUTPUT_JPEG == 'GIF' ) {
		$ext = 'gif';
	}

	if ( file_exists( $BANNER_PATH . "main" . $BID . ".$ext" ) ) {
		$available_block_window = 'return false;';
		if ( REDIRECT_SWITCH == 'YES' ) {
			$available_block_window = "parent.window.open('" . REDIRECT_URL . "', '', '');return false;";
		}
		?><img <?php if ( REDIRECT_SWITCH == 'YES' ) { ?>onclick="if (!block_clicked) {<?php echo $available_block_window; ?> }block_clicked=false;" <?php } ?> id="theimage" src="<?php echo BASE_HTTP_PATH . '/' . $BANNER_DIR; ?>main<?php echo $BID; ?>.<?php echo $ext; ?>?time=<?php echo( $banner_data['TIME'] ); ?>" width="<?php echo $banner_data['G_WIDTH'] * $banner_data['BLK_WIDTH']; ?>" height="<?php echo $banner_data['G_HEIGHT'] * $banner_data['BLK_HEIGHT']; ?>" border="0" usemap="#main" />
		<?php
	} else {
		echo "<b>The file: " . $BANNER_PATH . "main" . $BID . ".$ext" . " doesn't exist.</b><br>";
		echo "<b>Please process your pixels from the Admin section (Look under 'Pixel Admin')</b>";
	}

	?>
    <div class="tooltip-source">
        <img src="<?php echo BASE_HTTP_PATH; ?>images/periods.gif" alt=""/>
    </div>
	<?php
}

function show_stats() {
	global $f2, $label;

	$BID = $f2->bid();

	$banner_data = load_banner_constants( $BID );

	$sql    = "select count(*) AS COUNT FROM blocks where status='sold' and banner_id='$BID' ";
	$result = mysqli_query( $GLOBALS['connection'], $sql );
	$row    = mysqli_fetch_array( $result );
	$sold   = $row['COUNT'] * ( $banner_data['BLK_WIDTH'] * $banner_data['BLK_HEIGHT'] );

	$sql    = "select count(*) AS COUNT FROM blocks where status='nfs' and banner_id='$BID' ";
	$result = mysqli_query( $GLOBALS['connection'], $sql );
	$row    = mysqli_fetch_array( $result );
	$nfs    = $row['COUNT'] * ( $banner_data['BLK_WIDTH'] * $banner_data['BLK_HEIGHT'] );

	$available = ( ( $banner_data['G_WIDTH'] * $banner_data['G_HEIGHT'] * ( $banner_data['BLK_WIDTH'] * $banner_data['BLK_HEIGHT'] ) ) - $nfs ) - $sold;

	if ( $label['sold_stats'] == '' ) {
		$label['sold_stats'] = "Sold";
	}

	if ( $label['available_stats'] == '' ) {
		$label['available_stats'] = "Available";
	}

	?>
    <div class="status_body">
        <div class="status">
            <b><?php echo $label['sold_stats']; ?>:</b> <span class="status_text"><?php echo number_format( $sold ); ?></span><br/>
            <b><?php echo $label['available_stats']; ?>:</b> <span class="status_text"><?php echo number_format( $available ); ?></span><br/>
        </div>
    </div>
	<?php
}

function get_ad( $data ) {
	require_once( BASE_PATH . '/include/ads.inc.php' );

	global $prams;

	$prams = load_ad_values( $data['aid'] );

	if ( $prams !== false ) {
		echo assign_ad_template( $prams );
	}
}