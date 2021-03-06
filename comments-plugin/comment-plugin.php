<?php
/**
 * Plugin Name: Comments plugin
 * Description: This plugin is used to get comments from post on to other pages
 * Version: 1.0
 * Author: Madeleine Hallqvist
 * 
 */

class comment_widget extends WP_Widget{
    //Widgets settings
    function __construct(){
        $widget_options = array(
            'classname' =>'comment_widget',
            'description' => 'Get posts comments from a category.'
        );
        parent::__construct('comment_widget','Comments widget', $widget_options);
        }

    //function to output the widget form in customize, and widget site
    function form( $instance ) {
        $headline = ! empty( $instance['headline'] ) ? $instance['headline'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : 'Your category here';
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'headline'); ?>">Headline of the widget:</label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'headline' ); ?>" name="<?php echo $this->get_field_name( 'headline' ); ?>" value="<?php echo esc_attr( $headline ); ?>" /></p>

    <label for="<?php echo $this->get_field_id( 'text'); ?>">Category you want to get the latest post comments from:</label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo esc_attr( $text ); ?>" /></p>

    <?php }

    //Function to update the instance
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['headline'] = strip_tags( $new_instance['headline'] );
        $instance['text'] = strip_tags( $new_instance['text'] );
        return $instance;          
    }

 //function to display the widget in the site
 function widget( $args, $instance ) {
        //define variables
        $text = $instance['text'];
        $headline = $instance['headline'];
      
        $argse = array(
            'category_name' => $text
        );
        //Gets post id in the choosen category
        $post = get_posts($argse);
        foreach($post as $post) :
            $idOfLatestPost = $post->ID ;
          //  $date = get_the_date( 'd m Y', $post->ID );   //uncomment if you want to only display the widget when there is a post from todays date
        break;
        endforeach;	
       // $todaysDate = date('d m Y');   //uncomment if you want to only display the widget when there is a post from todays date
       // if($date === $todaysDate){     //uncomment if you want to only display the widget when there is a post from todays date
       //arguments for the comments
        $args = array(
                'number'=> 5,  //gets the five last comments
                'post_id' => $idOfLatestPost, //the id of the post to get the comments from
        );
    
        //Widget out put
        echo $args['before_widget'];
        ?>
        <div class="commentsWidget" id="commentsWidgets">
            <h2><?php echo $headline; ?></h2>
            <?php 
            $comments = get_comments($args);
            foreach($comments as $comments) :
                    ?>
                    <div class="commentsWidgetWrap" id="commentsWidgetsWrap"> 
                        <?php echo get_avatar($comments,64); ?>
                        <div class="widgetText">
                            <p class="commentsWidgetAuthor"><span><?php echo  $comments->comment_author, '</span> skriver: '?></p>
                            <p class ="commentsWidgetDate"><?php echo $comments->comment_date;?></p>
                            <p class="commentsWidgetText"><?php  echo $comments->comment_content; ?></p>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    
        <?php
        echo $args['after_widget'];
     
       wp_reset_query();
         //   }   //uncomment if you want to only display the widget when there is a post from todays date
    }
}
    //function to register the widget
    function register_comments_widget() {
        register_widget( 'comment_widget' );
    }
    add_action( 'widgets_init', 'register_comments_widget' );

    //function to register css and Js files. 
    function wpse_load_plugin_css() {
        $plugin_url = plugin_dir_url( __FILE__ );
    
        wp_enqueue_style( 'commentplugin', $plugin_url . 'css/commentplugin.css' );
        wp_register_script( 'sd_my_cool_script', plugins_url( 'js/commentpluginjs.js', __FILE__ ), array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'sd_my_cool_script' );        
    }
    add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );

