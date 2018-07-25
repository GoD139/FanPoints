<?php



/**
 *
 */
class controller extends fanPoints
{

  function __construct()
  {
    // add the save action to user's own profile editing screen update
    add_action( 'personal_options_update' , array( $this , 'usermeta_form_field_fanpoint_update' ) );

    // add the save action to user profile editing screen update
    add_action( 'edit_user_profile_update' , array( $this , 'usermeta_form_field_fanpoint_update' ) );

    if(isset($_POST['submit'])){
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
      return update_user_meta( $user_id, 'FanPoints', $_POST['fanpoints']
      );
  }




  /*
  * update FanPoint settings
  *
  */
  function options_update()
  {
    $Worth = $_POST['FP_Worth'];

    if(get_option('fanpoint_options', false)){
      add_option( 'FanPoints_Worth', $Worth );
    }else{
      update_option( 'FanPoints_Worth', $Worth );
    }

  }







}
