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

   if(!is_plugin_active( 'woocommerce/woocommerce.php' )) {
   	exit();
   }

   include_once(plugin_dir_path( __DIR__ ) .'woocommerce/woocommerce.php' );

   include_once('class/display_fields.class.php');
   include_once('class/controller.class.php');

   // include_once(plugin_dir_path( __DIR__ ) .'woocommerce/includes/abstracts/abstract-wc-data.php' );
   // include_once(plugin_dir_path( __DIR__ ) .'woocommerce/includes/legacy/abstract-wc-legacy-order.php' );
   // include_once(plugin_dir_path( __DIR__ ).'woocommerce/includes/abstracts/abstract-wc-order.php');
   // include_once(plugin_dir_path( __DIR__ ).'woocommerce/includes/class-wc-order.php');

   // echo wc_get_order_id_by_order_key($_GET['key']);
   //
   // $wc_order = new WC_Order(wc_get_order_id_by_order_key($_GET['key']));

   // Get an instance of the WC_Order object (same as before)
    // $order = wc_get_order( wc_get_order_id_by_order_key($_GET['key']) );
    //
    // print_r($order);
    //
    // // Get the order ID
    // $order_id = $order->get_id();
    //
    // // Get the custumer ID
    // $order_id = $order->get_user_id();

    // $query = new WC_Order_Query( array(
    //   'order_key' => 'wc_order_5b67f2a8f0695',
    // 	'limit' => -1,
    // 	'type' => 'shop_order',
    // 	'return' => 'ids',
    // ) );
    //
    //
    // $processing_orders = $query->get_orders();
    //
    // print_r($processing_orders);
    //
    //
    // foreach($processing_orders as $order_id){
  	// 	$order = wc_get_order( intval($order_id) );
  	// 	var_dump( $order ); //is returning bool(false)
  	// }









    //wc_get_order_id_by_order_key($_GET['key'])

   $fanPoints = new fanPoints();
   $display = new displayFields();
   $controller = new controller();

   class fanPoints
   {

     protected $Title = 'FanPoints';

     protected $Settings;

     protected $GetOrdersFilter = array(
     'post_status' => 'any',
     'post_type' => 'shop_order',
     'paged' => 1,
     'meta_key' => '_order_total',
     );


     function __construct()
     {

       $this->Settings = get_option('fanpoint_options', false)['FP_Worth'];

     }
   }























?>
