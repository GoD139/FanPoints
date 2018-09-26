<?php
   /*
   Plugin Name: Fan Points
   Plugin URI: http://fanboy.dk
   description: Fan points for members
   Version: 1.0
   Author: Benjamin Behrens
   Author URI: http://benjaminbehrens.com
   License: GPL2
   */



   


   include_once(plugin_dir_path( __DIR__ ) .'woocommerce/woocommerce.php' );

   include_once('class/display_fields.class.php');
   include_once('class/controller.class.php');
   include_once('class/recurring.class.php');
   include_once('class/purchase.class.php');
   include_once('class/shortcode.class.php');

    //wc_get_order_id_by_order_key($_GET['key'])

    //print_r(wc());

   $fanPoints = new fanPoints();
   $fp_display = new displayFields();
   $fp_controller = new controller();
   $fp_recurring = new recurring();
   $fp_purchase = new purchase();
   $fp_shortcode = new shortcodes();


   //$fp_recurring->RecurrenceChecker();

   class fanPoints
   {

     protected $Title = 'FanPoints';

     protected $GetOrdersFilter = array(
     'post_status' => 'any',
     'post_type' => 'shop_order',
     'paged' => 1,
     'meta_key' => '_order_total',
     );


     function __construct()
     {
       add_action( 'wp_enqueue_scripts', array($this , 'fanpoints_style') );
     }



     function fanpoints_style()
     {
         wp_register_style( 'fp-style', plugins_url( '/assets/css/fp-style.css', __FILE__ ), array(), '20120209', 'all' );

         // For either a plugin or a theme, you can then enqueue the style:
         wp_enqueue_style( 'fp-style' );
     }




     //fetch currently logged in users points
     protected function getCurrentUsersPoints()
     {
       return get_user_meta(get_current_user_id() , 'FanPoints')[0];
     }

     //fetch the worth of a certain amount of points
     protected function getPointsWorth($points = 1)
     {
       return $points * floatval(get_option('fanpoint_options', false)['FP_Worth']);
     }
     
     //fetch the worth of a certain amount of points
     protected function getMoneyWorth($dkk = 1)
     {
       return $dkk / floatval(get_option('fanpoint_options', false)['FP_Worth']);
     }

     protected function numberFormatter($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));

        // is this a number?
        if(!is_numeric($n)) return false;

        // now filter it;
        if($n>1000000000000) return round(($n/1000000000000),1).' trillion';
        else if($n>1000000000) return round(($n/1000000000),1).' milliarder';
        else if($n>1000000) return round(($n/1000000),1).' millioner';
        else if($n>1000) return round(($n/1000),1).' tusind';

        return number_format($n);
    }


     protected function cartPrice()
     {
       return $woocommerce->cart->cart_contents_total;
     }



   protected function get_fanpoint_value_premium_product($productprice){
       preg_match_all('!\d+!', $productprice, $productpriceOut);
       $productpriceOut = $productpriceOut[0][0];
       $amount = get_option('fanpoint_options')['FP_Receives_Premium'];

       return $amount*$productpriceOut;

   }


	   protected function get_fanpoint_value_basic_product($productprice){
		   preg_match_all('!\d+!', $productprice, $productpriceOut);
		   $productpriceOut = $productpriceOut[0][0];
		   $amount = get_option('fanpoint_options')['FP_Receives_Basic'];

		   return $amount*$productpriceOut;

	   }


	   protected function isPremiumMember(){
         if(wc_memberships_is_user_active_member(get_current_user_id(), 'premium-subscription')){
             return true;
         }
         return false;
	   }

	   protected function isBasicMember(){
		   if(wc_memberships_is_user_active_member(get_current_user_id(), 'basic-subscription')){
			   return true;
		   }
		   return false;
	   }

   }

























?>
