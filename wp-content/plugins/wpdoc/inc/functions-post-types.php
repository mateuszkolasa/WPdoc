<?php
/**
 * Created by PhpStorm.
 * User: mkolasa
 * Date: 21.07.16
 * Time: 14:06
 */

add_action( 'init', 'wpdoc_register_post_types' );

function wpdoc_register_post_types() {
    register_post_type('wpdoc_page', [
        'labels' => array(
            'name' => __( 'WPdoc' ),
            'singular_name' => __( 'wpdoc' ),
            'new_item_name' => __( 'Add New Page' )
        ),
        'taxonomies' => array('wpdoc_project'),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'wpdoc'),
        'show_ui' => true,
        'supports' => array('title', 'revisions', 'editor', 'thumbnail'),
        'hierarchical' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-portfolio'
    ]);

    //register_taxonomy_for_object_type( 'wpdoc_project', 'wpdoc_page' );



    $labels = array(
        'name' => _x( 'Project', 'taxonomy general name' ),
        'singular_name' => _x( 'Project', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Projects' ),
        'all_items' => __( 'All Projects' ),
        'edit_item' => __( 'Edit Project' ),
        'update_item' => __( 'Update Project' ),
        'add_new_item' => __( 'Add New Project' ),
        'new_item_name' => __( 'New Project Name' ),
        'menu_name' => __( 'Projects' ),
    );

    register_taxonomy('wpdoc_project', 'wpdoc_page', array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'projects' ),
    ));
}


class WPdoc_widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'wpdoc_widget', // Base ID
            'Projects', // Name
            array('description' => __( 'Opis'))
        );
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
        return $instance;
    }

    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters('widget_title', 'Projects');
        $numberOfListings = 5;
        echo $before_widget;
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        $this->getRealtyListings($numberOfListings);
        echo $after_widget;
    }

    function getRealtyListings($numberOfListings) { //html
        /*global $post;

        $listings = new WP_Query();
        $listings->query('post_type=wpdoc_page&posts_per_page=' . $numberOfListings );
        if($listings->found_posts > 0) {
            echo '<ul class="realty_widget">';
            while ($listings->have_posts()) {
                $listings->the_post();
                $image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'realty_widget_size') : '<div class="noThumb"></div>';
                $listItem = '<li>';
                $listItem .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                $listItem .= '</li>';
                echo $listItem;
            }
            echo '</ul>';
            wp_reset_postdata();
        }else{
            echo '<p style="padding:25px;">No listing found</p>';
        }*/

        // We wrap it in unordered list
        echo '<ul>';
        echo wp_list_categories(array(
            taxonomy => 'wpdoc_project',
            title_li => ''
        ));
        echo '</ul>';
    }


} //end class Realty_Widget

add_action( 'widgets_init', function(){
    register_widget( 'wpdoc_widget' );
});