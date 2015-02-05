<?php

class WPAnyFormWidget extends WP_Widget {
  function WPAnyFormWidget() {
      parent::__construct(
        'WPAnyFormWidget', // Base ID
        _x("WP Any Form Widget", "Form widget name", "wp-any-form"), // Name
        array('description' => _x("Widget to contain form.", "Form widget description", "wp-any-form"), ) // Args
      );
  }

  function widget( $args, $instance ) {
    extract( $args );
    $the_widget_heading = $instance['the_widget_heading'];
    $the_widget_heading_tag = $instance['the_widget_heading_tag'];
    $the_form_post_id = $instance['the_form_post_id'];
    echo $before_widget;
    $html_str = "
  <div class='div-wp-any-form-widget-container' >";
    if($the_widget_heading != "") {
        $html_str .= "<" . $the_widget_heading_tag . " class='widget-title' >" . $the_widget_heading . "</" . $the_widget_heading_tag . " >";
    }
    $html_str .= "
    " . do_shortcode('[wp_any_form display="form" pid="' . $the_form_post_id . '" ]') . "  
  </div>" . biz_logic_wp_custom_get_div_clear_html(false);
    echo $html_str;
    echo $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

  function form( $instance ) {
    $the_widget_heading = esc_attr( $instance['the_widget_heading'] );
    $the_widget_heading_tag = esc_attr( $instance['the_widget_heading_tag'] );
    $the_form_post_id = esc_attr( $instance['the_form_post_id'] );
    $ddl_the_widget_heading_tag_options_arr = array(
      "h1" => "h1",
      "h2" => "h2",
      "h3" => "h3",
      "h4" => "h4",
      "h5" => "h5"
    );
    ?>
    <br />
    <table class='tbl-admin-options' >
      <tr><td>  
      <label for="<?php echo $this->get_field_id( 'the_widget_heading' ); ?>"><?php _e( 'Title:' ); ?>
      </td></tr>
      <tr><td>  
      <input class="widefat" id="<?php echo $this->get_field_id( 'the_widget_heading' ); ?>" name="<?php echo $this->get_field_name( 'the_widget_heading' ); ?>" type="text" value="<?php echo $the_widget_heading; ?>" />
      </label>
      </td></tr>
      <tr><td>  
      <label for="<?php echo $this->get_field_id( 'the_widget_heading_tag' ); ?>"><?php _e( 'Title tag:' ); ?>
      </td></tr>
      <tr><td>  
      <?php echo biz_logic_wp_custom_get_ddl_html($this->get_field_name( 'the_widget_heading_tag' ), "ddl_the_widget_heading_tag", "", "", $ddl_the_widget_heading_tag_options_arr, $the_widget_heading_tag); ?>  
      </label>
      </td></tr>
      <tr><td>  
      <label for="<?php echo $this->get_field_id( 'the_form_post_id' ); ?>"><?php _ex("Form", "Form widget select form text", 'wp-any-form'); ?>:
      </td></tr>
      <tr><td>  
      <?php
      $the_form_posts_drop_down_html_str = biz_logic_wp_custom_get_form_posts_drop_down_html(false, $this->get_field_name('the_form_post_id'), "", "", false, $the_form_post_id);
      echo $the_form_posts_drop_down_html_str;
      ?>
      </td></tr>
    </table>
    <br />
    <?php
  }
}

add_action( 'widgets_init', 'WPAnyFormWidget' );
function WPAnyFormWidget() {
  register_widget( 'WPAnyFormWidget' );
}

?>