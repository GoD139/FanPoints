<?php 




/**
 * 
 */
class shortcodes extends fanPoints
{
  
  function __construct()
  {
    add_shortcode( 'users_fanpoints', array($this, 'fp_display_users_fanpoints') );
  }
  
  
  
  function fp_display_users_fanpoints( $atts ) {
  
      return $this->getCurrentUsersPoints();
  
  }
  
  
  
  
}
