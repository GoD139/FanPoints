<?php


/**
 * Class for displaying fields
 */
class displayFields extends fanPoints
{



  function __construct()
  {

    // add the field to user's own profile editing screen
    add_action( 'edit_user_profile', array($this, 'fanpoints_usermeta_form_field_admin') );

    // add the field to user profile editing screen
    add_action( 'show_user_profile', array($this, 'fanpoints_usermeta_form_field_admin') );


    add_filter( 'manage_users_columns', array($this, 'fanpoints_user_table') );// add the custom userlist column
    add_filter( 'manage_users_custom_column', array($this, 'fanpoints_user_table_row'), 10, 3  ); // add the custom userlist column data


    // add_filter( 'manage_users_custom_column', array($this, 'fanpoints_to_dkk_user_table_row'), 11, 4  );// add the custom userlist column data

    // add custom FanPoint menu
    add_action( 'admin_menu', array( $this ,'settings_menu' ) );
  }



  /**
   * The field on the editing screens.
   *
   * @param $user WP_User user object
   */
  public function fanpoints_usermeta_form_field_admin($user)
  {
      echo '
      <h3>FanPoints</h3>
      <table class="form-table">
          <tr>
              <th>
                  <label for="fanpoints">FanPoints</label>
              </th>
              <td>
                  <input type="number"
                         class="regular-text ltr"
                         id="fanpoints"
                         name="fanpoints"
                         value="'. esc_attr(get_user_meta($user->ID, 'FanPoints', true)) .'"
                         title="Users FanPoints"
                         pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                         required>
                  <p class="description">
                      Users FanPoints
                  </p>
              </td>
          </tr>
      </table>';
  }



  /**
   * Add user list column
   *
   * @param $column userlist var
   */
  function fanpoints_user_table( $column ) {
      $column['FanPoints'] = 'FanPoints';
      return $column;
  }



  /**
   * Add data to user list column
   */
  function fanpoints_user_table_row( $val, $column_name, $user_id ) {


    $UsersFanPoints = get_the_author_meta( 'FanPoints', $user_id );

    if($column_name == 'FanPoints'){
      if($UsersFanPoints){
        return $UsersFanPoints .' Points <br>'. $UsersFanPoints * floatval(get_option('fanpoint_options', false)['FP_Worth']) . '/dkk';
      }
      return $UsersFanPoints;
    }
    return $val;
  }


  private function FP_Worth()
  {
    return (float)$this->Settings['FP_Worth'];
  }



  function fanpoint_page_admin()
  {
    echo '
    <h1>'. $this->Title .'</h1>
    <hr>

    <form method="post" action="'. admin_url( 'users.php?page=fanpoints' ) .'">

      <div class="form-group">
        <label>1 point is worth</label><br>
        <input type="number"
               class="regular-text ltr"
               id="fanpoints"
               name="FP_Worth"
               value="'. esc_attr(get_option('fanpoint_options')['FP_Worth']) .'"
               title="FanPoints worth"
               pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
               step="0.0001"
               required>
         <label>DKK.</label>
       </div>
       <h2>Subscription Settings</h2>
       <div class="form-group">
         <label>Basic Subscription gets </label><br>
         <input type="number"
                class="regular-text ltr"
                id="fanpoints"
                name="FP_Receives_Basic"
                value="'. esc_attr(get_option('fanpoint_options')['FP_Receives_Basic']) .'"
                title="FanPoints worth"
                pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                step="0.0001"
                required>
          <label>points per 1 DKK spent</label>
        </div>

        <div class="form-group">
          <label>Premium Subscription gets </label><br>
          <input type="number"
                 class="regular-text ltr"
                 id="fanpoints"
                 name="FP_Receives_Premium"
                 value="'. esc_attr(get_option('fanpoint_options')['FP_Receives_Premium']) .'"
                 title="FanPoints worth"
                 pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                 step="0.0001"
                 required>
           <label>points per 1 DKK spent</label>
         </div>



      <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Update"><span class="acf-spinner"></span>
      </p>
    </form>

    ';
  }






  function settings_menu(){

    $parent_slug = 'users.php';
    $page_title = 'FanPoints';
    $menu_title = 'FanPoints';
    $capability = 'manage_options';
    $menu_slug  = 'fanpoints';
    $function   = array($this,'fanpoint_page_admin');
    //$icon_url   = 'dashicons-awards';
    //$position   = 56;


    //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function  );

  }









}
