<?php


class NSConfiguration
{


  public function __construct()
  {


		add_action( 'wp_ajax_nsaa_delete_queque', array($this,'nsaa_delete_queque') );

		add_action( 'wp_ajax_nsaa_clear_meta_akis',  array($this, 'nsaa_clear_meta_akis'));
		add_action( 'wp_ajax_nsaa_delete_has_links', array($this, 'nsaa_delete_has_links'));

		add_action('wp_ajax_nsaa_clear_all_logged_xed', array($this,'nsaa_clear_all_logged_xed'));
		add_action('admin_footer', array($this, 'nsaa_xed_for_view_delete_js'));
		add_action('admin_init', array($this, 'nsaa_do_register_settings'));
        add_action('admin_menu', array($this,'nsaa_do_add_menu_page'));

		add_action( 'admin_footer', array($this, 'nsaa_clear_wp_c_meta_js' ));
		add_action( 'admin_footer', array($this, 'nsaa_delete_wp_c_linked' ));

  }
  




  public function nsaa_do_add_menu_page(){
	add_options_page('Settings for No Spam At All ', 'No Spam Settings', 'manage_options','nsaa_display_settings_page', array( $this,'nsaa_main_settings_page' ));
}

public function nsaa_do_register_settings(){
	register_setting( 'nsaa_display_settings_page','nsaa_schedule_token', array( $this, 'nsaa_schedule_token_callback' ));
	register_setting( 'nsaa_display_settings_page','nsaa_comment_author_url', array( $this, 'nsaa_comment_author_url_callback' ));
	register_setting( 'nsaa_display_settings_page','nsaa_success_comment', array( $this, 'nsaa_success_comment_cb' ));
	register_setting( 'nsaa_display_settings_page','nsaa_bad_comment', array( $this, 'nsaa_bad_comment_cb' ));
	register_setting( 'nsaa_display_settings_page','nsaa_allow_links', array( $this, 'nsaa_allow_linkx_cb' ));

} 


public function nsaa_xed_for_view_delete_js()
{

}

public function nsaa_clear_wp_c_meta_js()
{
	
}

public function nsaa_delete_wp_c_linked()
{
	
}


public function nsaa_main_settings_page(){
$nsaa_schedule_token = get_option('nsaa_schedule_token');
$nsaa_comment_author_url =get_option('nsaa_comment_author_url');
$nsaa_success_comment = get_option('nsaa_success_comment');
$nsaa_bad_comment = get_option('nsaa_bad_comment');
$nsaa_allow_links = get_option('nsaa_allow_links');
?>
<div class="wrap nsaa_white">
<h1><img src="<?php echo plugins_url();  ?>/no-spam-at-all/assets/img/settings.png"> <?php _e('"No Spam At All" Settings', 'no-spam-at-all'); ?>  </h1>
<hr>
<form action="options.php" method="POST">
<?php settings_fields( 'nsaa_display_settings_page' );?>
<p>
<label for ="nsaa_schedule_token"> <?php _e( 'How often should the comment token change' , 'no-spam-at-all'); ?> </label>

<select name="nsaa_schedule_token" class="widefat txt-fields" id="nsaa_schedule_token">
<option value ="hourly" <?php if($nsaa_schedule_token == "hourly") echo " selected";  ?>> <?php _e('   Every Hour', 'no-spam-at-all'); ?> </option>
<option value ="twicedaily"  <?php if($nsaa_schedule_token == "twicedaily") echo " selected";  ?>>  <?php _e('Twice Daily', 'no-spam-at-all'); ?> </option>
<option value ="daily" <?php if($nsaa_schedule_token == "daily")  echo " selected"; ?>> <?php _e('Once 
a day', 'no-spam-at-all'); ?> </option>


</select>
</p>
<p>
<label for ="nsaa_comment_author_url"><?php _e(' Include OR Exclude comment author URL', 'no-spam-at-all' ); ?>  </label>

<select name="nsaa_comment_author_url" id="nsaa_comment_author_url" class="txt-fields">
<option value ='included' <?php if(isset( $nsaa_comment_author_url )){ if( $nsaa_comment_author_url =='included') echo 'selected';}     ?>> Include Comment Author URL </option>
<option value='excluded' <?php if(isset( $nsaa_comment_author_url )){ if( $nsaa_comment_author_url =='excluded' ) echo 'selected';}     ?>> Exclude Comment Author URL </option>
</select>
<span class ="inote"> <?php _e( ' Removing Comment Author URL, according to research will reduce number of spam comments by 40%; ', 'no-spam-at-all'); ?></span>
</p>


<p>
<label for ="nsaa_allow_links"><?php _e(' Allow Links in the comment', 'no-spam-at-all' ); ?>  </label>

<select name="nsaa_allow_links" id="nsaa_allow_links" class="txt-fields">
<option value ='Yes' <?php if(isset( $nsaa_allow_links )){ if( $nsaa_allow_links =='Yes') echo 'selected';}     ?>>Yes</option>
<option value='No' <?php if(isset( $nsaa_allow_links )){ if( $nsaa_allow_links =='No' ) echo 'selected';}     ?>> No </option>
</select>
<span class ="inote"> <?php _e( ' Allow blog/site commenters to use anchor tag "< a >"  in their comments ', 'no-spam-at-all'); ?></span>
</p>




<p>
<label for ="nsaa_success_comment"> <?php _e( 'Successfull comment message to real commenters', 'no-spam-at-all' ); ?> </label>
<input type ="text" name="nsaa_success_comment" placeholder ="Love your comment! Comment Again"
class="widefat txt-fields" id="nsaa_success_comment" value="
<?php echo $nsaa_success_comment ?>">
</p>

<p>
<label for ="nsaa_bad_comment"> <?php _e( 'Message to intending spammers' , 'no-spam-at-all'); ?> </label>
<input type ="text" name="nsaa_bad_comment" placeholder ="Chei! Evil Genuis Again"
class="widefat txt-fields" id="nsaa_bad_comment" value="
<?php echo $nsaa_bad_comment ?>">
</p>
<input type ="submit" value="Save Changes" class="nsaa_clear_btn" name="submit" id="submit">
<?php //submit_button(); ?>
</form>
</div>

<div class="ns_donate_box_side">
 Donate Via Bitcoin:
 3HPrLAAqc4fYiuKeviD61JKRGhHmpVKixB
</div>

 <?php
}
public function nsaa_comment_author_url_callback($input){
	return $input;
}

public function nsaa_schedule_token_callback($input){
 return $input;
}

public function nsaa_success_comment_cb($input){
 return $input;
}

public function nsaa_bad_comment_cb($input){
	return $input;

}


public function nsaa_allow_linkx_cb($input){
	return $input;
}

}
if(is_admin()) $nsaa_options = new NSConfiguration(); ?>
