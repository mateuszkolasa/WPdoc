<?php
/**
 * Created by PhpStorm.
 * User: mkolasa
 * Date: 21.07.16
 * Time: 14:06
 */

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
        'supports' => array('title', 'revisions', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
        'hierarchical' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-portfolio'
    ]);
    
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
            'WPdoc Projects', // Name
            array('description' => __( 'Display list of WPdoc projects'))
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
        global $post;

        // We wrap it in unordered list
        echo '<ul>';
        $taxonomies = get_categories(array('taxonomy' => 'wpdoc_project'), 'objects');
        if($taxonomies) {
            foreach($taxonomies as $taxonomy) {
                echo '<li><a href="/projects/' . $taxonomy->slug . '">' . $taxonomy->name . '</a>';
                
                /* Pages */
                $listings = new WP_Query();
                $listings->query('post_type=wpdoc_page&post_parent=0&wpdoc_project=' . $taxonomy->slug);
                if($listings->found_posts > 0) {
                    echo '<ul class="realty_widget">';
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'realty_widget_size') : '<div class="noThumb"></div>';
                        $listItem = '<li>';
                        $listItem .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                
                        $args = array(
                            'numberposts' => 1,
                            'post_parent' => $post->ID,
                            'post_status' => null,
                            'post_type' => 'wpdoc_page',
                        );
                
                        $children = get_children($args);
                
                        if($children) {
                            $listItem .= '<ul>';
                            foreach($children as $child) {
                                $listItem .= '<li><a href="' . get_permalink($child) . '" title="' . $child->post_title . '">' . $child->post_title . '</a></li>';
                            }
                            $listItem .= '</ul>';
                        }
                
                        $listItem .= '</li>';
                        echo $listItem;
                    }
                    echo '</ul>';
                    wp_reset_postdata();
                }
                /* --- Pages */
                
                echo '</li>';
            }
        }
        echo '</ul>';
    }


}

add_action( 'init', 'wpdoc_register_post_types' );
add_action( 'widgets_init', function(){
    register_widget( 'wpdoc_widget' );
});