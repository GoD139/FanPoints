<?php




class recurring extends fanPoints
{

  private $oneMonthFromNow;

  function __construct()
  {
    $this->oneMonthFromNow = date("Y-m-d H:i:s", strtotime("+1 month"));

    add_action('user_register', array($this, 'executeOnUserRegistration'));
    
    //check if a user should get their recurring monthly bonus
    add_action( 'init', array($this, 'RecurrenceChecker') );
  }


  function executeOnUserRegistration($user_id){
    $this->createDateToRecurre($userID);
  }



  


  public function RecurrenceChecker()
  {
    foreach(get_users() as $Users){
      //echo 'get users <br>';
      if($this->checkIfPremium($Users->ID)){
        //echo 'is premium <br>';
        //echo get_user_meta($Users->ID, 'FP_Recurring')[0];
        if($this->checkifMoreThanAMonthAgo(get_user_meta($Users->ID, 'FP_Recurring')[0])){
          //echo 'Is more than a month ago <br>';
          $this->givePoints($Users->ID, 7800);//give points
          $this->createDateToRecurre($Users->ID);//give new recurring date
        }
      }
    }
  }



  public function createDateToRecurre($userID)
  {
    update_user_meta( $userID, 'FP_Recurring', $this->oneMonthFromNow );
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
    if ($date < date("Y-m-d H:i:s"))
    {
      return true;
    }
    return false;
    // 
    // if(strtotime($date) > strtotime('+2 min')) {
    //   return true;
    // }
    // return false;
  }




  private function givePoints($userID, $points)
  {
    $PlayersCurrentPoints = 0;
    $FanPoints = get_user_meta($userID , 'FanPoints')[0];

    if (wc_memberships_is_user_active_member($userID, 'Premium Subscription')){
      $amount = $points;
      $amount += $FanPoints;
    }

    return update_user_meta( $userID, 'FanPoints', $amount);
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
