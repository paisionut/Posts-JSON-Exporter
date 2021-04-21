<?php
/**
 * Plugin Name: Export to JSON
 * Plugin URI: https://github.com/paisionut/f64_wp_export
 * Description: Export all WordPress posts to a JSON file.
 * Author: Pais Ionut
 * Author URI: https://www.linkedin.com/in/paisionut/
 * Version: 1.0.3
 **/

add_action( 'rest_api_init', 'custom_api_get_all_posts' );

function custom_api_get_all_posts() {
    register_rest_route( 'pais-feeds/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'custom_api_get_all_posts_callback'
    ));
}

function custom_api_get_all_posts_callback( $request ) {
    // Initialize the array that will receive the posts' data.
    $posts_data = array();
    // Receive and set the page parameter from the $request for pagination purposes
    $paged = $request->get_param( 'page' );
    $paged = ( isset( $paged ) || ! ( empty( $paged ) ) ) ? $paged : 1;

    $order = $request->get_param( 'order' );
    $order = ( isset( $order ) || ! ( empty( $order ) ) ) ? $order : DESC;

    $items = $request->get_param( 'items' );
    $items = ( isset( $items ) || ! ( empty( $items ) ) ) ? $items : 10;

    // Get the posts using the 'post' and 'news' post types
    $posts = get_posts( array(
            'paged' => $paged,
            'post__not_in' => get_option( 'sticky_posts' ),
            'posts_per_page' => $items,
            'order' => $order,
            'orderby' => 'ID',
            'depth', '1',
            'post_type' => 'post', //array( 'post', 'books', 'movies' )
            'post_status' => 'publish' //array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
        )
    );
    // Loop through the posts and push the desired data to the array we've initialized earlier in the form of an object
    foreach( $posts as $post ) {
        $id = $post->ID;

        $post_thumbnail = (has_post_thumbnail($id)) ? get_the_post_thumbnail_url($id) : null;
        if (!$post_thumbnail) {
            $post_thumbnail = (has_post_thumbnail($id)) ? get_the_post_thumbnail_url($id) : null;
        }

        $permalink = get_permalink($id);
        $categories = get_the_category($id);
        $tags = get_the_tags($id);
        $yoast_desc = get_post_meta($id, '_yoast_wpseo_metadesc', true );

        //$content = $post->post_content;

        if ($tags) {
            foreach( $tags as $tag ) {
                $tagList[] = $tag->name;
            }
        }
        if ($categories) {
            foreach ($categories as $cat) {
                $catList[] = $cat->name;
            }
        }
        $posts_data[] = (object) array(
            'id' => $id,
            'slug' => $post->post_name,
            //'type' => $post->post_type,
            'title' => $post->post_title,
            'yoast_desc' => $yoast_desc,
            //'name' => $post->post_name,
            //'content' => $content,
            'guid' => $post->guid,
            'permalink' => $permalink,
            'featured_img' => $post_thumbnail,
            'post_date' => $post->post_date,
            'post_modified_gmt' => $post->post_modified_gmt,
            'post_date_gmt' => $post->post_date_gmt,
            'tags' => $tagList,
            'categories' => $catList,
        );
    }

    //-- Write to /wp-content/export.json
    $json = wp_json_encode($posts_data);
    write_to_json_file($json);

    echo "Export to JSON complete.  /wp-content/pais-feeds/export.json";
    return;
    //return $posts_data;

}


/**
 * Write the export.json file to the wp-content directory.
 * File will be created if it does not exist.
 * Content will be replaced if the file already exists.
 *
 * @param string $json The JSON formatted string to write into the file.
 *
 * @return void
 */
function write_to_json_file( $json )
{
    $myfile = fopen(WP_CONTENT_DIR . "/pais-feeds/export.json", "w+");
    fwrite($myfile, $json);
    fclose($myfile);
}
?>