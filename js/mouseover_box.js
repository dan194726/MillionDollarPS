/*
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

$(function () {
	const defaultContent = $('.tooltip-source').html();

	tippy('area', {
		theme: 'light',
		content: defaultContent,
		duration: 50,
		delay: 50,
		trigger: 'click',
		allowHTML: true,
		followCursor: true,
		hideOnClick: true,
		interactive: true,
		maxWidth: 350,
		placement: 'auto',
		touch: true,
		appendTo: 'parent',
		onCreate(instance) {
			instance._isFetching = false;
			instance._content = null;
			instance._error = null;
		},
		onShow(instance) {
			if (instance._isFetching || instance._content || instance._error) {
				return;
			}

			instance._isFetching = true;

			async function postData(url = '', data = {}) {
				return await fetch(url, {
					method: 'POST',
					mode: 'cors',
					cache: 'force-cache',
					credentials: 'same-origin',
					headers: {
						'Content-Type': 'application/json'
					},
					redirect: 'follow',
					referrerPolicy: 'no-referrer',
					body: JSON.stringify(data)
				});
			}

			const data = $(instance.reference).data('data');

			postData(window.mds_data.ajax, {
				aid: data.id,
				bid: data.banner_id,
				action: 'ga'
			})
				.then((response) => response.text())
				.then(function (text) {
					instance.setContent(text);
					instance._content = true;

				})
				.catch((error) => {
					instance._error = error;
					instance.setContent(`Request failed. ${error}`);
				})
				.finally(() => {
					instance._isFetching = false;
				});

		},
		onHidden(instance) {
			instance.setContent(defaultContent);
			instance._content = null;
			instance._error = null;
		}
	});
});