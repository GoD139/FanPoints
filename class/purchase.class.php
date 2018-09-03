<?php





class purchase extends fanPoints
{

  private $apply_btn_title = 'Brug Rabat';
  private $remove_btn_title = 'Fjern Rabat';

  function __construct()
  {
    session_start();
    
    //print_r($_SESSION);
    //show message to add or remove rewards
    add_action( 'woocommerce_before_cart', array($this, 'phoen_rewpts_action_woocommerce_before_cal_table'), 10, 0);

    

    if($_SESSION['fp_action'] == 'apply'){
      add_action( 'woocommerce_cart_calculate_fees', array( $this , 'fp_woo_add_cart_fee' ), 10, 1);
    }
    
    add_action('woocommerce_thankyou', array($this, 'remove_points'), 10, 1);

    $this->fp_add_fee_from_cart();
    $this->fp_remove_fee_from_cart();
  }


  function phoen_rewpts_action_woocommerce_before_cal_table() {

    global $woocommerce;

    //$curr=get_woocommerce_currency_symbol();

    //$this->getCurrentUsersPoints();


    $bill_price=$woocommerce->cart->cart_contents_total;

    $taxPrice = preg_replace('/[^0-9]/', '', $woocommerce->cart->tax_total);

    //$used_reward_amount = $woocommerce->cart->fee_total;

    //echo $this->getCurrentUsersPoints();
    if(round($this->getCurrentUsersPoints()) != 0)
    {
      ?>
      <div class="col-md-12 clearfix" style="background:#fff; padding:3px 2px; margin:50px 10px;">
        <div class="fanpoint_apply_points_text col-md-9 float-left">

      <?php

      echo '<img src="'. esc_url( plugins_url( 'assets/img/coins.svg', __DIR__ ) ) .'" class="float-left" height="50px" style="margin-right:10px;">';

      if($bill_price >= $this->getPointsWorth($this->getCurrentUsersPoints()))
      {
        if(!isset($_SESSION['fp_action']) || $_SESSION['fp_action'] == "remove")
        {
          echo '
          <p class="fp_discount_text fp_not_active">
          Du kan bruge dine <span class="fp_point_color" style="color:#50ad90;">'. $this->getCurrentUsersPoints() . '</span> FanPoints og få '. round($this->getPointsWorth($this->getCurrentUsersPoints()), 1)  .' DKK rabat!</p>';
        }else if($_SESSION['fp_action'] == "apply"){
          echo '<p class="fp_discount_text fp_active">Rabat tilføjet!</p>';
        }

      }else{
        if(!isset($_SESSION['fp_action']) || $_SESSION['fp_action'] == "remove")
        {
          echo '
          <p class="fp_discount_text fp_not_active">
          Du kan bruge dine <span class="fp_point_color" style="color:#50ad90;">'. $this->getCurrentUsersPoints() . ' <small>('. round($this->getPointsWorth($this->getCurrentUsersPoints()), 1)  .' DKK)</small></span> FanPoints og få det gratis!</p>';
        }else if($_SESSION['fp_action'] == "apply"){
          echo '<p class="fp_discount_text fp_active">Rabat tilføjet!</p>';
        }
      }

      ?>

      </div>

      <div class="fanpoint_apply_points_form col-md-3 float-right">

        <form method="post" action="">

          <?php

          if(!isset($_SESSION['fp_action']) || $_SESSION['fp_action'] == "remove")
          {
            echo '<input type="submit" class="btn btn-apply-points col-md-12"  value="'. $this->apply_btn_title .'" name="fp_apply_points">';
          }else if($_SESSION['fp_action'] == "apply"){
            echo '<input type="submit" class="btn btn-remove-points col-md-12"  value="'. $this->remove_btn_title .' " name="fp_remove_points">';
          }

          ?>




        </form>

      </div>
     </div>
      <?php
    }
  }









  //remove reward points from total if click on rmove points
  function fp_remove_fee_from_cart()
  {
    if(isset($_POST['fp_remove_points'])) {
      remove_action( 'woocommerce_cart_calculate_fees', array( $this , 'fp_woo_add_cart_fee' ),10,1);
      $_SESSION['fp_action']="remove";
    }
  }

  //add reward points to total if click on rmove points
  function fp_add_fee_from_cart()
  {
    if(isset($_POST['fp_apply_points'])) 	{
      add_action( 'woocommerce_cart_calculate_fees', array( $this , 'fp_woo_add_cart_fee' ), 10, 1);
      $_SESSION['fp_action']="apply";
    }
  }





  function fp_woo_add_cart_fee() {

    global $woocommerce;

    $amt = round($this->getPointsWorth($this->getCurrentUsersPoints()), 1);
    //$bill_price = $woocommerce->cart->cart_contents_total;
    $bill_price = $woocommerce->cart->cart_contents_total + $woocommerce->cart->get_totals()['cart_contents_tax'];

    $u_price=0;

    if($amt >= $bill_price) {
      $u_price = $bill_price;
    } else if($amt<$bill_price) {
      $u_price = $amt;
    }

    $_SESSION['fp_amount'] = $u_price;
    $u_price = $u_price * .8;
    
    $woocommerce->cart->add_fee( __('FanPoint Rabat', 'woocommerce'), '-'.$u_price, false);
  }



  

function remove_points() { 

  if(isset($_SESSION['fp_amount']))
  {
    $FanPoints = get_user_meta(get_current_user_id() , 'FanPoints');
  
    $amount = $this->getCurrentUsersPoints() - $this->getMoneyWorth($_SESSION['fp_amount']);
    
    update_user_meta( get_current_user_id(), 'FanPoints', $amount);
    unset($_SESSION['fp_amount']);
  }
}



  // add_action( 'woocommerce_cart_calculate_fees','wc_add_surcharge' ); 
  //   function wc_add_surcharge() { 
  //   global $woocommerce; 
  // 
  //   if ( is_admin() && ! defined( 'DOING_AJAX' ) ) 
  //   return;
  // 
  //   $county = array('US');
  //   // change the $fee to set the surcharge to a value to suit
  //   $fee = 1.00;
  // 
  //   if ( in_array( WC()->customer->get_shipping_country(), $county ) ) : 
  //       $woocommerce->cart->add_fee( 'Surcharge', $fee, true, 'standard' );  
  //   endif;
  //   }



}
