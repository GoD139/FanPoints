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


