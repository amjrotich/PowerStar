<?php
// functions.php

// Include necessary files
require_once 'model_listing_functions.php';

// Register custom post type for models
add_action('init', 'register_model_post_type');

function register_model_post_type() {
    $labels = array(
        'name' => 'Models',
        'singular_name' => 'Model',
        'menu_name' => 'Models',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Model',
        'edit_item' => 'Edit Model',
        'new_item' => 'New Model',
        'view_item' => 'View Model',
        'search_items' => 'Search Models',
        'not_found' => 'No models found',
        'not_found_in_trash' => 'No models found in Trash',
        'parent_item_colon' => '',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'model'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );

    register_post_type('model', $args);
}

// Add custom fields for fashion model details
add_action('add_meta_boxes', 'add_model_details_meta_box');

function add_model_details_meta_box() {
    add_meta_box(
        'model_details_meta_box',
        'Fashion Model Details',
        'display_model_details_meta_box',
        'model',
        'normal',
        'high'
    );
}

function display_model_details_meta_box($post) {
    // Retrieve model details meta data
    $age = get_post_meta($post->ID, 'model_age', true);
    $height = get_post_meta($post->ID, 'model_height', true);
    $hair_color = get_post_meta($post->ID, 'model_hair_color', true);
    $eye_color = get_post_meta($post->ID, 'model_eye_color', true);
    ?>

    <label for="model_age">Age:</label>
    <input type="text" id="model_age" name="model_age" value="<?php echo esc_attr($age); ?>"><br>

    <label for="model_height">Height:</label>
    <input type="text" id="model_height" name="model_height" value="<?php echo esc_attr($height); ?>"><br>

    <label for="model_hair_color">Hair Color:</label>
    <input type="text" id="model_hair_color" name="model_hair_color" value="<?php echo esc_attr($hair_color); ?>"><br>

    <label for="model_eye_color">Eye Color:</label>
    <input type="text" id="model_eye_color" name="model_eye_color" value="<?php echo esc_attr($eye_color); ?>"><br>

    <?php
}

// Save model details meta data
add_action('save_post', 'save_model_details_meta_data');

function save_model_details_meta_data($post_id) {
    if (isset($_POST['model_age'])) {
        update_post_meta($post_id, 'model_age', sanitize_text_field($_POST['model_age']));
    }
    if (isset($_POST['model_height'])) {
        update_post_meta($post_id, 'model_height', sanitize_text_field($_POST['model_height']));
    }
    if (isset($_POST['model_hair_color'])) {
        update_post_meta($post_id, 'model_hair_color', sanitize_text_field($_POST['model_hair_color']));
    }
    if (isset($_POST['model_eye_color'])) {
        update_post_meta($post_id, 'model_eye_color', sanitize_text_field($_POST['model_eye_color']));
    }
}

// Enable JSON REST API with all fields for custom post type "model"
add_action('rest_api_init', 'register_model_rest_fields');

function register_model_rest_fields() {
    // Register fields for the "model" post type
    register_rest_field('model', 'model_fields', array(
        'get_callback' => 'get_model_fields',
        'schema' => null,
    ));
}

// Define callback function to retrieve all fields for the "model" post type
function get_model_fields($object) {
    $post_id = $object['id'];
    $post_meta = get_post_meta($post_id);

    // Get all meta data fields for the model post
    $model_fields = array();
    foreach ($post_meta as $key => $value) {
        $model_fields[$key] = $value[0];
    }

    return $model_fields;
}
