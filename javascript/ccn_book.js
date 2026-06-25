(function ($) {
	'use strict';

	function optionPage(book) {
		var wanted = parseInt(book.attr('data-page'), 10);
		return Number.isFinite(wanted) && wanted > 0 ? wanted : 1;
	}

	function computeSize(book, pages) {
		var stage = book.find('.ccn-book__stage');
		var available = Math.max(280, stage.width() - 24);
		var wide = window.matchMedia('(min-width: 761px)').matches;
		var width = wide ? Math.min(1060, available) : Math.min(available, 520);
		var height = wide ? Math.min(Math.max(width * 0.68, 540), 720) : Math.min(Math.max(width * 1.28, 460), 640);

		pages.css({
			width: Math.round(width),
			height: Math.round(height)
		});

		return {
			width: Math.round(width),
			height: Math.round(height),
			display: wide ? 'double' : 'single'
		};
	}

	function updateControls(book, pages) {
		var page = pages.turn('page') || 1;
		var total = pages.turn('pages') || book.find('.ccn-book__page').length;

		book.find('.ccn-book__current').text(page);
		book.find('.ccn-book__total').text(total);
		book.find('.ccn-book__prev').prop('disabled', page <= 1);
		book.find('.ccn-book__next').prop('disabled', page >= total);
	}

	function initBook(book) {
		var pages = book.find('.ccn-book__pages');
		var pageCount = book.find('.ccn-book__page').length;

		if (!pageCount) {
			return;
		}

		if (!$.isFunction($.fn.turn)) {
			book.addClass('ccn-book--fallback');
			return;
		}

		var size = computeSize(book, pages);

		pages.turn({
			width: size.width,
			height: size.height,
			display: size.display,
			autoCenter: true,
			elevation: 70,
			gradients: true,
			acceleration: true,
			duration: 950,
			page: Math.min(optionPage(book), pageCount),
			when: {
				turned: function () {
					updateControls(book, pages);
				}
			}
		});

		book.find('.ccn-book__prev').on('click.ccnBook', function () {
			pages.turn('previous');
		});

		book.find('.ccn-book__next').on('click.ccnBook', function () {
			pages.turn('next');
		});

		$(document).on('keydown.ccnBook', function (event) {
			if ($(event.target).is('input, textarea, select, button, [contenteditable="true"]')) {
				return;
			}

			if (event.key === 'ArrowLeft') {
				pages.turn('previous');
				event.preventDefault();
			} else if (event.key === 'ArrowRight') {
				pages.turn('next');
				event.preventDefault();
			}
		});

		var resizeTimer;
		$(window).on('resize.ccnBook', function () {
			window.clearTimeout(resizeTimer);
			resizeTimer = window.setTimeout(function () {
				var nextSize = computeSize(book, pages);
				pages.turn('display', nextSize.display);
				pages.turn('size', nextSize.width, nextSize.height);
				updateControls(book, pages);
			}, 160);
		});

		updateControls(book, pages);
	}

	$(function () {
		$('.ccn-book').each(function () {
			initBook($(this));
		});
	});
}(jQuery));
