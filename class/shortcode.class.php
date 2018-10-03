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
    add_shortcode( 'users_fanpoints_gets_premium', array($this, 'fp_display_user_get_if_premium') );
    add_shortcode( 'users_fanpoints_gets_basic', array($this, 'fp_display_user_get_if_basic') );
  }
  
  
  
  function fp_display_users_fanpoints( $atts ) {
      return $this->getCurrentUsersPoints();
  }
  
  function fp_display_users_fanpoints_worth( $atts ) {
      return $this->getPointsWorth($this->getCurrentUsersPoints());
  }

/*
 *
 * @param Product price
 * @return Fanpoints user earns from purchasing product
 */
  function fp_display_user_get_if_premium( $atts , $content, $tag)
  {
	  if($this->isPremiumMember())
  	return "<span class='fp_get_if_premium'> Optjen ". $this->get_fanpoint_value_premium_product($atts['price'])." Fanpoints </span>";
  }

	/*
   *
   * @param Product price
   * @return Fanpoints user earns from purchasing product
   */
	function fp_display_user_get_if_basic( $atts )
	{
		if($this->isBasicMember())
		return "<span class='fp_get_if_basic'> Optjen ".$this->get_fanpoint_value_basic_product($atts['price'])." Fanpoints </span>";
	}



  
  
}
