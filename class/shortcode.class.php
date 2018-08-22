<?php 




/**
 * 
 */
class shortcodes extends fanPoints
{
  
  function __construct()
  {
    add_shortcode( 'users_fanpoints', array($this, 'fp_display_users_fanpoints') );
    add_shortcode( 'users_fanpoints_worth', array($this, 'fp_display_users_fanpoints_worth') );
  }
  
  
  
  function fp_display_users_fanpoints( $atts ) {
      return $this->getCurrentUsersPoints();
  }
  
  function fp_display_users_fanpoints_worth( $atts ) {
      return $this->getPointsWorth($this->getCurrentUsersPoints());
  }
  
  
}
