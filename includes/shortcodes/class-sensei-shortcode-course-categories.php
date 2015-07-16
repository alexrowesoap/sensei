<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 *
 * Renders the [sensei_course_categories] shortcode. Show the list or course categories as links to their archive
 * pages. The list can be change with the given parameters.
 *
 * This class is loaded int WP by the shortcode loader class.
 *
 * @class Sensei_Shortcode_Course_Categories
 * @since 1.9.0
 * @package Sensei
 * @category Shortcodes
 * @author 	WooThemes
 */
class Sensei_Shortcode_Course_Categories implements Sensei_Shortcode_Interface {

    /**
     * @var array list of taxonomy terms.
     */
    protected $sensei_course_taxonomy_terms;

    /**
     * Setup the shortcode object
     *
     * @since 1.9.0
     * @param array $attributes
     * @param string $content
     * @param string $shortcode the shortcode that was called for this instance
     */
    public function __construct( $attributes, $content, $shortcode ){

        $this->orderby = isset( $attributes['orderby'] ) ? $attributes['orderby'] : 'name';
        $this->order = isset( $attributes['order'] ) ? $attributes['order'] : 'ASC';
        $this->number = isset( $attributes['number'] ) ? $attributes['number'] : '100';
        $this->parent = isset( $attributes['parent'] ) ? $attributes['parent'] : '';
        $this->include = isset( $attributes['include'] ) ? $attributes['include'] : '';
        $this->exclude = isset( $attributes['exclude'] ) ? $attributes['exclude'] : '';

        // make sure we handle string true/false values correctly with respective defaults
        $hide_empty = isset( $attributes['hide_empty'] ) ? $attributes['hide_empty'] : 'false';
        $this->hide_empty = 'true' == $hide_empty ? true: false;

        $this->setup_course_categories();

    }

    /**
     * create the messages query .
     *
     * @return mixed
     */
    public function setup_course_categories(){

        $args = array(
            'orderby'       => $this->orderby,
            'order'         => $this->order,
            'exclude'       => $this->exclude,
            'include'       => $this->include,
            'number'        => $this->number,
            'parent'        => $this->parent,
            'hide_empty'    => $this->hide_empty,
            'fields'        => 'all',
        );

        $this->sensei_course_taxonomy_terms = get_terms('course-category', $args);

    }

    /**
     * Rendering the shortcode this class is responsible for.
     *
     * @return string $content
     */
    public function render(){

        if( empty(  $this->sensei_course_taxonomy_terms  ) ){

            return __( 'No course categories found.', 'woothemes-sensei' );

        }

        $terms_html = '';

        //set the wp_query to the current messages query
        $terms_html .= '<ul class="sensei course-categories">';
        foreach( $this->sensei_course_taxonomy_terms as $category ){

            $category_link = '<a href="'. get_term_link( $category ) . '">' . $category->name  . '</a>';
            $terms_html .=  '<li class="sensei course-category" >' . $category_link . '</li>';

        }
        $terms_html .= '<ul>';

        return $terms_html;

    }// end render

}// end class

