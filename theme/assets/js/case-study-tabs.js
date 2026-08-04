/**
 * Case Study Tabs block behavior.
 *
 * - Desktop / laptop: classic tab switching (click + keyboard arrows).
 * - Mobile (<= 767px): the whole panel area behaves as a swipeable slider
 *   (scroll-snap); swiping a panel updates the active tab and vice versa.
 * - Video: poster + play overlay, click toggles playback.
 */
( function () {
	'use strict';

	var MOBILE_QUERY = '(max-width: 767px)';

	function initBlock( root ) {
		var tablist = root.querySelector( '[data-cst-tablist]' );
		var panelsWrap = root.querySelector( '[data-cst-panels]' );
		if ( ! tablist || ! panelsWrap ) {
			return;
		}

		var tabs = Array.prototype.slice.call( tablist.querySelectorAll( '[data-cst-tab]' ) );
		var panels = Array.prototype.slice.call( panelsWrap.querySelectorAll( '[data-cst-panel]' ) );
		var mq = window.matchMedia( MOBILE_QUERY );
		var current = 0;
		var scrollLock = false;
		var scrollLockTimer = null;

		function isMobile() {
			return mq.matches;
		}

		function pauseAllVideos() {
			panels.forEach( function ( panel ) {
				var video = panel.querySelector( 'video.cst-tabs__video' );
				if ( video && ! video.paused ) {
					video.pause();
				}
			} );
		}

		function setActive( index, options ) {
			options = options || {};
			if ( index < 0 || index >= tabs.length ) {
				return;
			}
			current = index;
			pauseAllVideos();

			tabs.forEach( function ( tab, i ) {
				var active = i === index;
				tab.classList.toggle( 'is-active', active );
				tab.setAttribute( 'aria-selected', active ? 'true' : 'false' );
				if ( active ) {
					tab.removeAttribute( 'tabindex' );
				} else {
					tab.setAttribute( 'tabindex', '-1' );
				}
			} );

			panels.forEach( function ( panel, i ) {
				var active = i === index;
				panel.classList.toggle( 'is-active', active );
				if ( isMobile() ) {
					panel.removeAttribute( 'hidden' );
				} else if ( active ) {
					panel.removeAttribute( 'hidden' );
				} else {
					panel.setAttribute( 'hidden', '' );
				}
			} );

			// Keep the active tab visible inside the scrollable tab bar.
			var activeTab = tabs[ index ];
			if ( activeTab && tablist.scrollWidth > tablist.clientWidth ) {
				activeTab.scrollIntoView( { behavior: 'smooth', block: 'nearest', inline: 'center' } );
			}

			if ( isMobile() && ! options.fromScroll ) {
				scrollLock = true;
				clearTimeout( scrollLockTimer );
				scrollLockTimer = setTimeout( function () {
					scrollLock = false;
				}, 600 );
				panelsWrap.scrollTo( {
					left: panels[ index ].offsetLeft - panelsWrap.offsetLeft,
					behavior: options.instant ? 'auto' : 'smooth'
				} );
			}
		}

		// --- Tab clicks ---
		tabs.forEach( function ( tab, i ) {
			tab.addEventListener( 'click', function () {
				setActive( i );
			} );
		} );

		// --- Keyboard navigation (WAI-ARIA tabs pattern) ---
		tablist.addEventListener( 'keydown', function ( event ) {
			var dir = 0;
			if ( 'ArrowRight' === event.key ) {
				dir = 1;
			} else if ( 'ArrowLeft' === event.key ) {
				dir = -1;
			} else {
				return;
			}
			event.preventDefault();
			var next = ( current + dir + tabs.length ) % tabs.length;
			setActive( next );
			tabs[ next ].focus();
		} );

		// --- Mobile slider: sync active tab while swiping panels ---
		var scrollDebounce = null;
		panelsWrap.addEventListener( 'scroll', function () {
			if ( ! isMobile() || scrollLock ) {
				return;
			}
			clearTimeout( scrollDebounce );
			scrollDebounce = setTimeout( function () {
				var center = panelsWrap.scrollLeft + panelsWrap.clientWidth / 2;
				var index = current;
				panels.forEach( function ( panel, i ) {
					var left = panel.offsetLeft - panelsWrap.offsetLeft;
					if ( center >= left && center < left + panel.offsetWidth ) {
						index = i;
					}
				} );
				if ( index !== current ) {
					setActive( index, { fromScroll: true } );
				}
			}, 80 );
		}, { passive: true } );

		// --- Breakpoint switch: reset visibility rules ---
		function onBreakpointChange() {
			setActive( current, { instant: true } );
		}
		if ( mq.addEventListener ) {
			mq.addEventListener( 'change', onBreakpointChange );
		} else {
			mq.addListener( onBreakpointChange );
		}

		// --- Video play/pause ---
		panels.forEach( function ( panel ) {
			var videoCard = panel.querySelector( '[data-cst-video]' );
			if ( ! videoCard ) {
				return;
			}
			var video = videoCard.querySelector( 'video.cst-tabs__video' );
			var playBtn = videoCard.querySelector( '[data-cst-play]' );
			if ( ! video || ! playBtn ) {
				return;
			}

			function togglePlay() {
				if ( video.paused ) {
					video.play();
				} else {
					video.pause();
				}
			}

			playBtn.addEventListener( 'click', togglePlay );
			video.addEventListener( 'click', togglePlay );
			video.addEventListener( 'play', function () {
				videoCard.classList.add( 'is-playing' );
				video.setAttribute( 'controls', '' );
			} );
			video.addEventListener( 'pause', function () {
				videoCard.classList.remove( 'is-playing' );
				video.removeAttribute( 'controls' );
			} );
		} );

		setActive( 0, { instant: true } );
	}

	function init() {
		document.querySelectorAll( '[data-cst-tabs]' ).forEach( initBlock );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

	// Re-init inside the Gutenberg editor preview (ACF re-renders the block).
	if ( window.acf ) {
		window.acf.addAction( 'render_block_preview/type=case-study-tabs', function ( $el ) {
			var el = $el && $el[ 0 ] ? $el[ 0 ] : null;
			if ( el ) {
				el.querySelectorAll( '[data-cst-tabs]' ).forEach( initBlock );
			}
		} );
	}
} )();
