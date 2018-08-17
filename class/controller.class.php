<?php



/**
 *
 */
class controller extends fanPoints
{



  function __construct()
  {

    /**
  	 * Returns the product discounted price for a member user.
  	 *
  	 * @since 1.3.0
  	 *
  	 * @param float $base_price original price
  	 * @param int|\WC_Product $product product ID or product object.
  	 * @param int|null $member_id optional, defaults to current user ID
  	 * @return float|null the discounted price or null if no discount applies
  	 */
    //get_discounted_price( $base_price, $product, $member_id = null )

    // echo '<pre>';
    // print_r($this->Woocommerce);
    // echo '</pre>';

    // add the save action to user's own profile editing screen update
    add_action( 'personal_options_update' , array( $this , 'usermeta_form_field_fanpoint_update' ) );

    // add the save action to user profile editing screen update
    add_action( 'edit_user_profile_update' , array( $this , 'usermeta_form_field_fanpoint_update' ) );

    add_action( 'woocommerce_thankyou', array( $this, 'add_points_after_purchase'));

    if(isset($_POST['fp_settings_submit'])){
      $this->options_update();
    }
  }


  /**
   * The save action.
   *
   * @param $user_id int the ID of the current user.
   *
   * @return bool Meta ID if the key didnt exist, true on successful update, false on failure.
   */
  function usermeta_form_field_fanpoint_update($user_id)
  {
      // check that the current user have the capability to edit the $user_id
      if (!current_user_can('edit_user', $user_id)) {
          return false;
      }

      // create/update user meta for the $user_id
      return update_user_meta( $user_id, 'FanPoints', $_POST['fanpoints']);
  }




  /*
  * update FanPoint settings
  *
  */
  function options_update()
  {

    $Settings = array(
      'FP_Worth' => $_POST['FP_Worth'],
      'FP_Receives_Basic' => $_POST['FP_Receives_Basic'],
      'FP_Receives_Premium' => $_POST['FP_Receives_Premium']);

    if(!get_option('fanpoint_options', false)){
      add_option( 'fanpoint_options', $Settings );
    }else{
      update_option( 'fanpoint_options', $Settings );
    }

  }







 function add_points_after_purchase()
 {
    if($this->is_user_logged_in()){


      // echo 'is logged in';
      //
      //
      // echo $this->get_order_price();

      // echo $this->check_if_order_have_given_points($_GET['key']);
      //
      // echo $this->get_order_price() .'<br>';
      //
      // echo wc_get_order_id_by_order_key($_GET['key']);

      //echo get_user_meta(get_current_user_id() , 'FanPoints')[0];

      //echo get_the_author_meta( 'FanPoints', get_current_user_id() );

      if(!$this->check_if_order_have_given_points($_GET['key']))
      {
        $this->give_points($this->get_order_price());
        $this->give_order_points($_GET['key']);
      }






    }
 }


 //check if user have already received points for their order
 //so they dont get more points if they reload their thank-you page
 function check_if_order_have_given_points($postKey)
 {
   $orderId = wc_get_order_id_by_order_key($postKey);
   if(get_post_meta( $orderId, 'fb_point_given')){
       return true;
    }
    return false;
 }


 //Save the order that have given points, so we can check if order should give points
 function give_order_points($postKey)
 {
    $orderId = wc_get_order_id_by_order_key($postKey);
    add_post_meta( $orderId, 'fb_point_given', 1, true );
 }


  private function give_points($purchaseCost)
  {
    $PlayersCurrentPoints = 0;
    if(get_user_meta(get_current_user_id() , 'FanPoints')){
      $PlayersCurrentPoints = get_user_meta(get_current_user_id() , 'FanPoints')[0];
    }

    if (wc_memberships_is_user_active_member($user_id, 'Premium Subscription')){
      $amount = get_option('fanpoint_options')['FP_Receives_Premium'] * $purchaseCost;
      $amount += $PlayersCurrentPoints;
    }else if (wc_memberships_is_user_active_member($user_id, 'Basic Subscription')){
      $amount = get_option('fanpoint_options')['FP_Receives_Basic'] * $purchaseCost;
      $amount += $PlayersCurrentPoints;
    }
    return update_user_meta( get_current_user_id(), 'FanPoints', $amount);
  }


 //MISC

 private function is_user_logged_in()
 {
   $user = wp_get_current_user();
   return $user->exists();
 }


 private function get_order_price()
 {
   $orders = new WP_Query($this->GetOrdersFilter);

   foreach($orders->posts as $order){
     if($orders->post->ID == $order->ID){
       $orderPrice = get_post_meta($order->ID, '_order_total')[0];
     }
   }
   return $orderPrice;
 }



}
