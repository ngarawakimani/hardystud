/* global jQuery:false */
/* global HAPPYRIDER_GLOBALS:false */

jQuery(document).ready(function () {
	"use strict";

	HAPPYRIDER_GLOBALS['reviews_user_accepted'] = false;

	happyrider_add_hidden_elements_handler('init_reviews', happyrider_init_reviews);

	happyrider_init_reviews(jQuery('body'));
});


// Init reviews elements
function happyrider_init_reviews(cont) {
	"use strict";

	// Drag slider - set new rating
	cont.find('.reviews_editable .reviews_slider:not(.inited)').each(function() {
		"use strict";
		if (typeof(HAPPYRIDER_GLOBALS['reviews_allow_user_marks'])=='undefined' || !HAPPYRIDER_GLOBALS['reviews_allow_user_marks']) return;
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this).addClass('inited');
		var row  = jQuery(this).parents('.reviews_item');
		var wrap = jQuery(this).parents('.reviews_stars_wrap');
		var rangeMin = 0;
		var rangeMax = parseInt(row.data('max-level'));
		var step  = parseFloat(row.data('step'));
		var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
		var grid  = Math.max(1, (wrap.width()-jQuery(this).width()) / (rangeMax - rangeMin) / prec);
		// Move slider to init position
		var val = parseFloat(row.find('input[type="hidden"]').val());
		var x = Math.round((val - rangeMin) * (wrap.width()-jQuery(this).width()) / (rangeMax - rangeMin));
		happyrider_reviews_set_current_mark(row, val, x, false);
		jQuery(this).draggable({
			axis: 'x',
			grid: [grid, grid],
			containment: '.reviews_stars_wrap',
			scroll: false,
			drag: function (e, ui) {
				"use strict";
				var pos = ui.position.left >= 0 ? ui.position.left : ui.originalPosition.left + ui.offset.left;
				var val = Math.min(rangeMax, Math.max(rangeMin, Math.round(pos * prec * (rangeMax - rangeMin) / (wrap.width()-jQuery(this).width())) / prec + rangeMin));
				happyrider_reviews_set_current_mark(row, val);
			}
		});
	});


	// Click on stars - set new rating
	cont.find('.reviews_editor .reviews_editable .reviews_stars_wrap:not(.inited),.reviews_editor .reviews_max_level_100 .reviews_criteria:not(.inited)').each(function() {
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this).addClass('inited');
		jQuery(this).click(function (e) {
			"use strict";
			if (typeof(HAPPYRIDER_GLOBALS['reviews_allow_user_marks'])=='undefined' || !HAPPYRIDER_GLOBALS['reviews_allow_user_marks']) return;
			if (jQuery(this).hasClass('reviews_criteria') && !jQuery(this).next().hasClass('reviews_editable')) return;
			var wrap = jQuery(this).hasClass('reviews_criteria') ? jQuery(this).next() : jQuery(this);
			var row  = wrap.parents('.reviews_item');
			var wrapWidth = wrap.width()-wrap.find('.reviews_slider').width();
			var rangeMin = 0;
			var rangeMax = parseInt(row.data('max-level'));
			var step  = parseFloat(row.data('step'));
			var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
			var grid  = wrapWidth / (rangeMax - rangeMin + 1) / step;
			var wrapX = e.pageX - wrap.offset().left;
			if (wrapX <= 1) wrapX = 0;
			if (wrapX > wrapWidth) wrapX = wrapWidth;
			var val = Math.min(rangeMax, Math.max(rangeMin, Math.round(wrapX * prec * (rangeMax - rangeMin) / wrapWidth) / prec + rangeMin));
			happyrider_reviews_set_current_mark(row, val, wrapX);
		});
	});


	// Save user's marks
	cont.find('.reviews_accept:not(.inited)').each(function() {
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this).addClass('inited');
		jQuery(this).find('a').click(function(e) {
			"use strict";
			if (typeof(HAPPYRIDER_GLOBALS['reviews_allow_user_marks'])=='undefined' || !HAPPYRIDER_GLOBALS['reviews_allow_user_marks']) return;
			var marks_cnt = 0;
			var marks_sum = 0;
			var marks_accept = jQuery(this).parents('.reviews_accept');
			var marks_panel = marks_accept.siblings('.reviews_editor');
			marks_panel.find('input[type="hidden"]').each(function (idx) {
				"use strict";
				var row  = jQuery(this).parents('.reviews_item');
				var step  = parseFloat(row.data('step'));
				var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
				var mark = parseFloat(jQuery(this).val());
				if (isNaN(mark)) mark = 0;
				HAPPYRIDER_GLOBALS['reviews_marks'][idx] = Math.round(((HAPPYRIDER_GLOBALS['reviews_marks'].length>idx && HAPPYRIDER_GLOBALS['reviews_marks'][idx]!=''
					? parseFloat(HAPPYRIDER_GLOBALS['reviews_marks'][idx])*HAPPYRIDER_GLOBALS['reviews_users']
					: 0) + mark) / (HAPPYRIDER_GLOBALS['reviews_users']+1)*prec)/prec;
				jQuery(this).val(HAPPYRIDER_GLOBALS['reviews_marks'][idx]);
				marks_cnt++;
				marks_sum += mark;
			});
			if (marks_sum > 0) {
				if (HAPPYRIDER_GLOBALS['reviews_marks'].length > marks_cnt)
					HAPPYRIDER_GLOBALS['reviews_marks'] = HAPPYRIDER_GLOBALS['reviews_marks'].splice(marks_cnt, HAPPYRIDER_GLOBALS['reviews_marks'].length-marks_cnt)
				HAPPYRIDER_GLOBALS['reviews_users']++;
				marks_accept.fadeOut();
				jQuery.post(HAPPYRIDER_GLOBALS['ajax_url'], {
					action: 'reviews_users_accept',
					nonce: HAPPYRIDER_GLOBALS['ajax_nonce'],
					post_id: HAPPYRIDER_GLOBALS['post_id'],
					marks: HAPPYRIDER_GLOBALS['reviews_marks'].join(','),
					users: HAPPYRIDER_GLOBALS['reviews_users']
				}).done(function(response) {
					var rez = JSON.parse(response);
					if (rez.error === '') {
						HAPPYRIDER_GLOBALS['reviews_allow_user_marks'] = false;
						happyrider_set_cookie('happyrider_votes', HAPPYRIDER_GLOBALS['reviews_vote'] + (HAPPYRIDER_GLOBALS['reviews_vote'].substr(-1)!=',' ? ',' : '') + HAPPYRIDER_GLOBALS['post_id'] + ',', 365);
						marks_panel.find('.reviews_item').each(function (idx) {
							jQuery(this).data('mark', HAPPYRIDER_GLOBALS['reviews_marks'][idx])
								.find('input[type="hidden"]').val(HAPPYRIDER_GLOBALS['reviews_marks'][idx]).end()
								.find('.reviews_value').html(HAPPYRIDER_GLOBALS['reviews_marks'][idx]).end()
								.find('.reviews_stars_hover').css('width', Math.round(HAPPYRIDER_GLOBALS['reviews_marks'][idx]/HAPPYRIDER_GLOBALS['reviews_max_level']*100) + '%');
						});
						happyrider_reviews_set_average_mark(marks_panel);
						marks_panel.find('.reviews_stars').removeClass('reviews_editable');
						marks_panel.siblings('.reviews_summary').find('.reviews_criteria').html(HAPPYRIDER_GLOBALS['strings']['reviews_vote']);
					} else {
						marks_panel.siblings('.reviews_summary').find('.reviews_criteria').html(HAPPYRIDER_GLOBALS['strings']['reviews_error']);
					}
				});
			}
			e.preventDefault();
			return false;
		});
	});
}


// Set current mark value
function happyrider_reviews_set_current_mark(row, val) {
	"use strict";
	var x = arguments[2]!=undefined ? arguments[2] : -1;
	var clear = arguments[3]!=undefined ? arguments[3] : true;
	var rangeMin = 0;
	var rangeMax = parseInt(row.data('max-level'));
	row.find('.reviews_value').html(val);
	row.find('input[type="hidden"]').val(val).trigger('change');
	row.find('.reviews_stars_hover').css('width', Math.round(row.find('.reviews_stars_bg').width()*val/(rangeMax-rangeMin))+'px');
	if (x >=0) row.find('.reviews_slider').css('left', x+'px');
	// Clear user marks and show Accept Button
	if (!HAPPYRIDER_GLOBALS['admin_mode'] && !HAPPYRIDER_GLOBALS['reviews_user_accepted'] && clear) {
		HAPPYRIDER_GLOBALS['reviews_user_accepted'] = true;
		row.siblings('.reviews_item').each(function () {
			"use strict";
			jQuery(this).find('.reviews_stars_hover').css('width', 0);
			jQuery(this).find('.reviews_value').html('0');
			jQuery(this).find('.reviews_slider').css('left', 0);
			jQuery(this).find('input[type="hidden"]').val('0');
		});
		// Show Accept button
		row.parent().next().fadeIn();
	}
	happyrider_reviews_set_average_mark(row.parents('.reviews_editor'));
}

// Show average mark
function happyrider_reviews_set_average_mark(obj) {
	"use strict";
	var avg = 0;
	var cnt = 0;
	var rangeMin = 0;
	var rangeMax = parseInt(obj.find('.reviews_item').eq(0).data('max-level'));
	var step = parseFloat(obj.find('.reviews_item').eq(0).data('step'));
	var prec = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
	obj.find('input[type="hidden"]').each(function() {
		avg += parseFloat(jQuery(this).val());
		cnt++;
	});
	avg = cnt > 0 ? avg/cnt : 0;
	avg = Math.min(rangeMax, Math.max(rangeMin, Math.round(avg * prec) / prec + rangeMin));
	var summary = obj.siblings('.reviews_summary');
	summary.find('.reviews_value').html(avg);
	summary.find('input[type="hidden"]').val(avg).trigger('change');
	summary.find('.reviews_stars_hover').css('width', Math.round(summary.find('.reviews_stars_bg').width()*avg/(rangeMax-rangeMin))+'px');
}

// Convert percent to rating marks level
function happyrider_reviews_marks_to_display(mark) {
	"use strict";
	if (HAPPYRIDER_GLOBALS['reviews_max_level'] < 100) {
		mark = Math.round(mark / 100 * HAPPYRIDER_GLOBALS['reviews_max_level'] * 10) / 10;
		if (String(mark).indexOf('.') < 0) {
			mark += '.0';
		}
	}
	return mark;
}

// Get word-value review rating
function happyrider_reviews_get_word_value(r) {
	"use strict";
	var words = HAPPYRIDER_GLOBALS['reviews_levels'].split(',');
	var k = HAPPYRIDER_GLOBALS['reviews_max_level'] / words.length;
	r = Math.max(0, Math.min(words.length-1, Math.floor(r/k)));
	return words[r];
}
