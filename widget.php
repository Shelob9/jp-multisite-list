<?php
/**
 *
 * @package jp-multisite-links
 * @since 0.2
 * @author Josh Pollock http://JoshPress.net
 */


/**
 * Adds jpt_Widget widget.
 */
class jpt_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'jp_widget', // Base ID
            __('Multisite Post List', 'jp-msl'), // Name
            array( 'description' => __( 'Show all posts from all blogs in your multisite network.', 'jp-msl' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        //echo __( 'Hello, World!', 'jp-msl' );
        //echo $args['after_widget'];
        jp_msl_posts();
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( ' ', 'jp-msl' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class jpt_Widget
// register jpt_Widget widget
function register_jpt_widget() {
    register_widget( 'jpt_Widget' );
}
add_action( 'widgets_init', 'register_jpt_widget' );