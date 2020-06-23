<?php
/**
 * @package       mds
 * @copyright     (C) Copyright 2020 Ryan Rhode, All rights reserved.
 * @author        Ryan Rhode, ryan@milliondollarscript.com
 * @version       2020.05.08 17:42:17 EDT
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

require_once __DIR__ . "/include/init.php";

global $label, $purifier;

require_once( __DIR__ . '/html/header.php' );
require_once( __DIR__ . '/include/top_ads_js.php' );
require_once( __DIR__ . '/html/mouseover_box.htm' );
require_once( __DIR__ . '/include/ads.inc.php' );

?>
    <div class="list">
        <div class="table-row header">
            <div class="list-heading"><?php echo $label['list_date_of_purchase']; ?></div>
            <div class="list-heading"><?php echo $label['list_name']; ?></div>
            <div class="list-heading"><?php echo $label['list_ads']; ?></div>
            <div class="list-heading"><?php echo $label['list_pixels']; ?></div>
        </div>
		<?php
		$sql = "SELECT * FROM banners ORDER BY banner_id";
		$banners = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
		while ( $banner = mysqli_fetch_array( $banners ) ) {
			?>
            <div class="table-row header">
                <div class="list-heading" style="width:100%;"><?php echo $purifier->purify( $banner['name'] ); ?></div>
            </div>
			<?php
			$sql = "SELECT *, MAX(order_date) as max_date, sum(quantity) AS pixels FROM orders where status='completed' AND approved='Y' AND published='Y' AND banner_id='" . intval( $banner['banner_id'] ) . "' GROUP BY user_id, banner_id, order_id order by pixels desc ";
			$result = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
			while ( $row = mysqli_fetch_array( $result ) ) {
				$q = "SELECT FirstName, LastName FROM users WHERE ID=" . intval( $row['user_id'] );
				$q = mysqli_query( $GLOBALS['connection'], $q ) or die( mysqli_error( $GLOBALS['connection'] ) );
				$user = mysqli_fetch_row( $q );
				?>
                <div class="table-row">
                    <div class="list-cell">
						<?php echo $purifier->purify( get_formatted_date( get_local_time( $row['max_date'] ) ) ); ?>
                    </div>
                    <div class="list-cell">
						<?php echo $purifier->purify( $user['0'] . " " . $user['1'] ); ?>
                    </div>
                    <div class="list-cell">
						<?php

						$br  = "";
						$sql = "Select * FROM  `ads` as t1, `orders` AS t2 WHERE t1.ad_id=t2.ad_id AND t1.banner_id='" . intval( $BID ) . "' and t1.order_id='" . intval( $row['order_id'] ) . "' AND t1.user_id='" . intval( $row['user_id'] ) . "' AND status='completed' AND approved='Y' ORDER BY `ad_date`";
						$m_result = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
						while ( $prams = mysqli_fetch_array( $m_result, MYSQLI_ASSOC ) ) {

							$blocks   = explode( ',', $prams['blocks'] );
							$block_id = $blocks[0];

							$ALT_TEXT = get_template_value( 'ALT_TEXT', 1 );
							$ALT_TEXT = str_replace( "'", "", $ALT_TEXT );
							$ALT_TEXT = ( str_replace( "\"", '', $ALT_TEXT ) );
							echo $br . '<a target="_blank" data-block-id="' . $block_id . '" data-id="' . $prams['ad_id'] . '" data-alt-text="' . $ALT_TEXT . '" class="list-link" href="http://' . get_template_value( 'URL', 1 ) . '">' . get_template_value( 'ALT_TEXT', 1 ) . '</a>';
							$br = '<br>';
						}

						?>
                    </div>
                    <div class="list-cell">
						<?php echo $row['pixels']; ?>
                    </div>
                </div>
				<?php
			}
		}
		?>
        <div class="table-row header">
            <div class="list-heading"><?php echo $label['list_date_of_purchase']; ?></div>
            <div class="list-heading"><?php echo $label['list_name']; ?></div>
            <div class="list-heading"><?php echo $label['list_ads']; ?></div>
            <div class="list-heading"><?php echo $label['list_pixels']; ?></div>
        </div>
    </div>
<?php
include_once( __DIR__ . "/html/footer.php" );
