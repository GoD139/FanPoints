<?php


/**
 *
 */
class cancel extends fanPoints {


	function __construct() {
		add_action("init",array( $this , 'init' ));
		//$this->remove_fanpoints_woocommerce_cancelled_order("4567");
	}

function init(){
	add_action( 'woocommerce_order_status_cancelled', 'remove_fanpoints_woocommerce_cancelled_order', 10, 1 );
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
		//echo "hello gets run";
		//echo did_action('init');
		$order = wc_get_order( $order_get_id );
		$orderTotal=$order->get_total();
		$orderFanpointValue=$this->get_fanpoint_value_basic_product($orderTotal);
		$userid=$order->get_user_id();
		if(get_user_meta($userid, 'FanPoints')[0]){
			$userFanpointAmount= get_user_meta($userid , 'FanPoints')[0];
		}
		else{
			$userFanpointAmount=0;
		}

		$userFanpointAmount=$userFanpointAmount-$orderFanpointValue;

		update_user_meta( $userid, 'FanPoints', $userFanpointAmount);

	}


}