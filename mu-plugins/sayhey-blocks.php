<?php
/*
 * Plugin name: Say Hey Allowed Blocks
 */

add_filter( 'allowed_block_types', 'sayhey_allowed_block_types' );

function sayhey_allowed_block_types( $allowed_blocks ) {

	return array(
    // Common
		'core/image',
		'core/paragraph',
		'core/heading',
    'core/gallery',
    'core/list',
    'core/quote',
    //'core/audio',
    //'core/cover',
    'core/file',
    //'core/video',

    // Formatting
    'core/table',
    'core/verse',
    //'core/code',
    'core/freeform', // Classic
    'core/html', // Custom HTML
    'core/preformatted',
    'core/pullquote',

    // Layout
    //'core/button',
    'core/text-columns', // Columns
    'core/media-text', // Media and Text
    //'core/more',
    //'core/nextpage', // Page break
    'core/separator',
    'core/spacer',

    // WP Widgets
    //'core/shortcode',
    //'core/archives',
    //'core/categories',
    //'core/latest-comments',
    //'core/latest-posts',
    //'core/calendar',
    //'core/rss',
    //'core/search',
    //'core/tag-cloud',

    // EMBEDS
    //'core/embed',
    'core-embed/twitter',
    'core-embed/youtube',
    'core-embed/facebook',
    'core-embed/instagram',
    //'core-embed/wordpress',
    'core-embed/soundcloud',
    //'core-embed/spotify',
    'core-embed/flickr',
    'core-embed/vimeo',
    //'core-embed/animoto',
    //'core-embed/cloudup',
    //'core-embed/collegehumor',
    //'core-embed/dailymotion',
    //'core-embed/funnyordie',
    //'core-embed/hulu',
    //'core-embed/imgur',
    //'core-embed/issuu',
    //'core-embed/kickstarter',
    //'core-embed/meetup-com',
    //'core-embed/mixcloud',
    //'core-embed/photobucket',
    //'core-embed/polldaddy',
    //'core-embed/reddit',
    //'core-embed/reverbnation',
    //'core-embed/screencast',
    //'core-embed/scribd',
    //'core-embed/slideshare',
    //'core-embed/smugmug',
    //'core-embed/speaker',
    //'core-embed/ted',
    //'core-embed/tumblr',
    //'core-embed/videopress',
    //'core-embed/wordpress-tv',

    // CUSTOM Blocks
    // 'acf/press'

	);

}
