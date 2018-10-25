<?php


/**
 *
 */
class cancel extends fanPoints {


	function __construct() {
		add_action("init",array( $this , 'init' ));
	}

function init(){
	add_action( 'woocommerce_order_status_cancelled', array( $this , 'remove_fanpoints_woocommerce_cancelled_order' ), 21, 1 );

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

	function remove_fanpoints_woocommerce_cancelled_order($order_get_id){
		$order = wc_get_order( $order_get_id );
		/*foreach ($order->get_items() as $item){
			print_r($item);
		}*/


		$orderTotal=$order->get_total();


		$userid=$order->get_user_id();
		if($this->isBasicMemberid($userid)) {
			$orderFanpointValue = $this->get_fanpoint_value_basic_product( $orderTotal );
		}

		elseif ($this->isPremiumMemberid($userid)){
			$orderFanpointValue = $this->get_fanpoint_value_premium_product( $orderTotal );

		}else{
			$orderFanpointValue=0;
		}
		if(get_user_meta($userid, 'FanPoints')[0]){
			$userFanpointAmount= get_user_meta($userid , 'FanPoints')[0];
		}
		else{
			$userFanpointAmount=0;
		}
		$_product= new WC_Product();
		foreach ($order->get_items() as $item){
			$_product=$item->get_product();
			if($_product->get_type()=="subscription"){
				if($this->isBasicMemberid($userid)) {
					$orderFanpointValue-=$this->get_fanpoint_value_basic_product(ceil($item->get_total()+$item->get_total_tax()));
				}
				elseif ($this->isPremiumMemberid($userid)){
					$orderFanpointValue -= $this->get_fanpoint_value_premium_product( ceil($item->get_total()+$item->get_total_tax()) );

				}

			}
		}

		$userFanpointAmount=$userFanpointAmount-$orderFanpointValue;

		update_user_meta( $userid, 'FanPoints', $userFanpointAmount);

	}






}