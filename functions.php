<?php

// Disable gutenberg
add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
function dm_remove_wp_block_library_css() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'dm_remove_wp_block_library_css' );

// Add animations
add_action( 'wp_enqueue_scripts', 'add_aos_animation' );
function add_aos_animation() {
     wp_enqueue_style('AOS_animate', get_template_directory_uri() . '/dist/css/aos.css', false, null);
     wp_enqueue_script('AOS', get_template_directory_uri() . '/dist/js/aos.js', false, null, true);
}

// svg
function svg($svg){
  echo file_get_contents( get_stylesheet_directory_uri() . '/assets/img/' . $svg );
}

// Cleaning
function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
remove_action('wp_head', 'wp_resource_hints', 2);
add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins))
    {
        return array_diff($plugins, array(
            'wpemoji'
        ));
    }
    else
    {
        return array();
    }
}
function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type)
    {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array(
            $emoji_svg_url
        ));
    }
    return $urls;
}

// Compress html
function compressHTML($str)
{
    return preg_replace(array(
        '/<!--(.*?)-->/s', // html comments
        '@\/\*(.*?)\*\/@s', // js comments
        '/\>[^\S ]+/s', // after ">"
        '/[^\S ]+\</s', // beofre ">"
        '/\>\s+\</', // between "><"
        '/\;[^\S ]+/s', // after ;
        '/\{[^\S ]+/s', // before {
        '/\}[^\S ]+/s', // before {
        '/[^\S ]+\}/s'
        // after }
        
    ) , array(
        '', /// html comments
        '', // js comments
        '>', // after ">"
        '<', // strips before tags, except space
        '><', // between "><"
        ';', // after ;
        '{', // before {
        '}', // before }
        '}'
        // after }
        
    ) , $str);
}

add_action('template_redirect', 'htmlStart', 0);
function htmlStart()
{
    ob_start('compress');
}

function compress($buffer)
{
    return compressHTML($buffer);
}
