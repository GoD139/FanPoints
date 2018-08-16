<?php




class recurring extends fanPoints
{

  private $oneMonthFromNow;

  function __construct()
  {
    $this->oneMonthFromNow = date("Y-m-d H:i:s", strtotime("+1 month"));

    add_action('user_register','executeOnUserRegistration');

  }


  function executeOnUserRegistration($user_id){
    $this->createDateToRecurre($userID);
  }



  public function createDateToRecurre($userID)
  {
    update_user_meta( $userID, 'FP_Recurring', $this->oneMonthFromNow );
  }


  public function RecurrenceChecker()
  {
    foreach(get_users() as $Users){
      if($this->checkIfPremium($Users->ID)){
        if($this->checkifMoreThanAMonthAgo(get_user_meta($Users->ID, 'FP_Recurring')[0])){
          $this->givePoints($userID, 3000);
          $this->createDateToRecurre($userID);
        }
      }
    }
  }




  private function checkIfPremium($userID)
  {
    if (wc_memberships_is_user_active_member($userID, 'Premium Subscription')){
      return true;
    }
    return false;
  }



  private function checkifMoreThanAMonthAgo($date)
  {
    if(strtotime($date) < strtotime('-2 min')) {
      return true;
    }
    return false;
  }




  private function givePoints($userID, $points)
  {
    $PlayersCurrentPoints = 0;
    if(get_user_meta($userID() , 'FanPoints')){
      $PlayersCurrentPoints = get_user_meta($userID() , 'FanPoints')[0];
    }

    if (wc_memberships_is_user_active_member($userID, 'Premium Subscription')){
      $amount = $points;
      $amount += $PlayersCurrentPoints;
    }

    return update_user_meta( $userID(), 'FanPoints', $amount);
  }






  /////////////// testing ////////////////

  public function giveEveryoneRecurringDate()
  {
    foreach(get_users() as $Users){

        $this->createDateToRecurre($Users->ID);
        //if(get_user_meta($Users->ID, 'FP_Recurring'))
        // echo get_user_meta($Users->ID, 'FP_Recurring');
        // echo $Users->ID;

    }

  }



}
