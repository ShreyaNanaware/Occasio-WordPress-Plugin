<?php
/**
 * Plugin Name: Occasio Events
 * Plugin URI: https://example.com/occasio-events
 * Description: A custom plugin for event management with custom post types, meta fields, and a shortcode.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPLv2 or later
 * Text Domain: occasio-events
 *
 * @package OccasioEvents
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register Event Custom Post Type
function occasio_register_event_post_type() {
    $labels = array(
        'name'               => __( 'Events', 'occasio-events' ),
        'singular_name'      => __( 'Event', 'occasio-events' ),
        'add_new'            => __( 'Add New', 'occasio-events' ),
        'add_new_item'       => __( 'Add New Event', 'occasio-events' ),
        'edit_item'          => __( 'Edit Event', 'occasio-events' ),
        'new_item'           => __( 'New Event', 'occasio-events' ),
        'all_items'          => __( 'All Events', 'occasio-events' ),
        'view_item'          => __( 'View Event', 'occasio-events' ),
        'search_items'       => __( 'Search Events', 'occasio-events' ),
        'not_found'          => __( 'No events found', 'occasio-events' ),
        'not_found_in_trash' => __( 'No events found in Trash', 'occasio-events' ),
        'menu_name'          => __( 'Events', 'occasio-events' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'events' ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'          => 'dashicons-calendar',
        'show_in_rest'       => true,
    );

    register_post_type( 'occasio_event', $args );
}
add_action( 'init', 'occasio_register_event_post_type' );

// Add custom meta box for Event Details
function occasio_event_meta_box() {
    add_meta_box(
        'occasio_event_details',
        __( 'Event Details', 'occasio-events' ),
        'occasio_event_meta_callback',
        'occasio_event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'occasio_event_meta_box' );

// Render meta box fields
function occasio_event_meta_callback( $post ) {
    // Add a nonce field for security
    wp_nonce_field( 'occasio_save_event_meta', 'occasio_event_meta_nonce' );

    $date  = get_post_meta( $post->ID, '_event_date', true );
    $time  = get_post_meta( $post->ID, '_event_time', true );
    $venue = get_post_meta( $post->ID, '_event_venue', true );
    $link  = get_post_meta( $post->ID, '_event_link', true );

    echo '<p><label for="event_date"><strong>' . esc_html__( 'Date', 'occasio-events' ) . '</strong></label><br />';
    echo '<input type="date" id="event_date" name="event_date" value="' . esc_attr( $date ) . '" style="width: 100%; max-width: 320px;" /></p>';

    echo '<p><label for="event_time"><strong>' . esc_html__( 'Time', 'occasio-events' ) . '</strong></label><br />';
    echo '<input type="time" id="event_time" name="event_time" value="' . esc_attr( $time ) . '" style="width: 100%; max-width: 320px;" /></p>';

    echo '<p><label for="event_venue"><strong>' . esc_html__( 'Venue', 'occasio-events' ) . '</strong></label><br />';
    echo '<input type="text" id="event_venue" name="event_venue" value="' . esc_attr( $venue ) . '" style="width: 100%; max-width: 520px;" placeholder="' . esc_attr__( 'e.g., Hall A, Pune', 'occasio-events' ) . '" /></p>';

    echo '<p><label for="event_link"><strong>' . esc_html__( 'External Link (optional)', 'occasio-events' ) . '</strong></label><br />';
    echo '<input type="url" id="event_link" name="event_link" value="' . esc_attr( $link ) . '" style="width: 100%; max-width: 520px;" placeholder="https://..." /></p>';
}

// Save meta fields
function occasio_save_event_meta( $post_id ) {
    // Verify nonce.
    if ( ! isset( $_POST['occasio_event_meta_nonce'] ) || ! wp_verify_nonce( $_POST['occasio_event_meta_nonce'], 'occasio_save_event_meta' ) ) {
        return;
    }

    // Check autosave.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions.
    if ( isset( $_POST['post_type'] ) && 'occasio_event' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Sanitize and save.
    $fields = array(
        '_event_date'  => isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '',
        '_event_time'  => isset( $_POST['event_time'] ) ? sanitize_text_field( wp_unslash( $_POST['event_time'] ) ) : '',
        '_event_venue' => isset( $_POST['event_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['event_venue'] ) ) : '',
        '_event_link'  => isset( $_POST['event_link'] ) ? esc_url_raw( wp_unslash( $_POST['event_link'] ) ) : '',
    );

    foreach ( $fields as $key => $value ) {
        if ( ! empty( $value ) ) {
            update_post_meta( $post_id, $key, $value );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }
}
add_action( 'save_post', 'occasio_save_event_meta' );

// Shortcode: [occasio_events posts="5" order="ASC" upcoming="1"]
function occasio_display_events_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'posts'    => 5,
            'order'    => 'ASC', // ASC for upcoming first (by date), DESC for latest added.
            'upcoming' => 1,     // 1 to show only upcoming events (date >= today), 0 for all.
        ),
        $atts,
        'occasio_events'
    );

    // Build meta query for upcoming events
    $meta_query = array();
    if ( intval( $atts['upcoming'] ) === 1 ) {
        $meta_query[] = array(
            'key'     => '_event_date',
            'value'   => date( 'Y-m-d' ),
            'compare' => '>=',
            'type'    => 'DATE',
        );
    }

    $args = array(
        'post_type'      => 'occasio_event',
        'posts_per_page' => intval( $atts['posts'] ),
        'meta_key'       => '_event_date',
        'orderby'        => 'meta_value',
        'order'          => ( strtoupper( $atts['order'] ) === 'DESC' ) ? 'DESC' : 'ASC',
        'meta_query'     => $meta_query,
    );

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p>' . esc_html__( 'No events found.', 'occasio-events' ) . '</p>';
    }

    ob_start();
    echo '<div class="occasio-events">';
    while ( $query->have_posts() ) :
        $query->the_post();
        $date  = get_post_meta( get_the_ID(), '_event_date', true );
        $time  = get_post_meta( get_the_ID(), '_event_time', true );
        $venue = get_post_meta( get_the_ID(), '_event_venue', true );
        $link  = get_post_meta( get_the_ID(), '_event_link', true );

        echo '<div class="occasio-event-item">';
            echo '<h3 class="occasio-event-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
            if ( $date ) {
                echo '<p><strong>' . esc_html__( 'Date:', 'occasio-events' ) . '</strong> ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) ) . '</p>';
            }
            if ( $time ) {
                echo '<p><strong>' . esc_html__( 'Time:', 'occasio-events' ) . '</strong> ' . esc_html( $time ) . '</p>';
            }
            if ( $venue ) {
                echo '<p><strong>' . esc_html__( 'Venue:', 'occasio-events' ) . '</strong> ' . esc_html( $venue ) . '</p>';
            }
            if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail( get_the_ID(), 'medium' );
            }
            echo '<div class="occasio-event-excerpt">' . wp_kses_post( wpautop( get_the_excerpt() ) ) . '</div>';
            if ( $link ) {
                echo '<p><a href="' . esc_url( $link ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Event Link', 'occasio-events' ) . '</a></p>';
            }
        echo '</div><hr />';
    endwhile;
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'occasio_events', 'occasio_display_events_shortcode' );

// Basic styles on front-end (optional)
function occasio_enqueue_styles() {
    wp_register_style( 'occasio-events-style', plugins_url( 'assets/occasio-events.css', __FILE__ ), array(), '1.0.0' );
    wp_enqueue_style( 'occasio-events-style' );
}
add_action( 'wp_enqueue_scripts', 'occasio_enqueue_styles' );
