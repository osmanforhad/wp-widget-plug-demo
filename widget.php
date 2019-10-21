<?php
/*
 * Plugin Name: CBX Widget
	Plugin URL:
	Description: Demo of Plugin Widget
	Version: 1.0
	Author: osman forhad
	Author URI: https://osmanforhad.net
	License: GPLv2 or later
	Text Domain:widgetdemo
	Domain Path: /languages/
 * */

class CBXwidget extends WP_Widget{
    
/**
 * constructor function
 */
public function __construct(){

    add_action('widgets_init',array($this,'registerCBXWidget'));

	/**
	 * Declare Instance for 
	 * Parent Class WP_Widget 
	 * which is extends here
	 */
	$widget_options = array( 
		'classname' => 'CBXwidget',
		'description' => 'This Widget Created By CBX Plugin',
	);
	
	parent::__construct( 
		$baseID = 'cbxwidget',//base id for widget lower case and (unique)
		 $name = 'Custom Widget CBX',//widget name (string) (optional)
		  $widget_options //Widget options (array) (Optional) 
		);//end parent data

    
}//end constructor

/**
 * callback function registerCBXWidget
 */
public function registerCBXWidget(){

	/**
	 * register widget
	 */
	register_widget( 
	$reg_widget = 	'CBXwidget'//class name (Required)
 );//end register widget 

}//end registerCBXWidget call back

/**
 * output the widget content on the front end
 */
public function widget($args, $instance){

echo $args['before_widget'];
	if ( ! empty( $instance['title'] ) ) {
		echo $args['before_title'] . apply_filters( 
			'widget_title', 
			$instance['title'] ) . $args['after_title'];
	}

	if( ! empty( $instance['selected_posts'] ) && is_array( $instance['selected_posts'] ) ){ 

		$selected_posts = get_posts( array( 'post__in' => $instance['selected_posts'] ) );
		?>
<p>
    <u>Most Favoriets Posts</u>
    <ol>
        <?php foreach ( $selected_posts as $post ) { ?>
        <li><a href="<?php echo get_permalink( $post->ID); ?>">
                <?php echo $post->post_title; ?>
            </a></li>
        <?php } ?>
    </ol>
</p>
<?php 
		
	}else{
		echo esc_html__( 'No posts selected!', 'text_domain' );	
	}

	echo $args['after_widget'];
    
}//end front end disply function

/**
 * output the option form field in admin Widgets Screen
 */
public function form($instance){

$posts = get_posts( array( 
			'posts_per_page' => 20,
			'offset' => 0
		) );
	$selected_posts = ! empty( $instance['selected_posts'] ) ? $instance['selected_posts'] : array();
	?>
<div style="max-height: 120px; overflow: auto;">
    <p>Select Favorites POSTS</p>
    <hr>
    <ul>
        <?php foreach ( $posts as $post ) { ?>

        <li><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'selected_posts' ) ); ?>[]"
                value="<?php echo $post->ID; ?>"
                <?php checked( ( in_array( $post->ID, $selected_posts ) ) ? $post->ID : '', $post->ID ); ?> />
            <?php echo get_the_title( $post->ID ); ?></li>

        <?php } ?>
    </ul>
</div>
<?php

}//end output admin screen optin

/**
 * save option
 */
public function update($new_instance, $old_instance){

    $instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
	$selected_posts = ( ! empty ( $new_instance['selected_posts'] ) ) ? (array) $new_instance['selected_posts'] : array();
	$instance['selected_posts'] = array_map( 'sanitize_text_field', $selected_posts );

	return $instance;
    
}//end save option
    
}//end class

/**
 * initiate the class
 */
new CBXwidget();