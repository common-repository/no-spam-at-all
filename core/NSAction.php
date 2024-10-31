<?php


class NSAction
{
	 public function __construct()
	 {

        add_filter('preprocess_comment', array($this, 'commentPreProcess') );
        add_action('wp_head', array($this, 'tokenPaste'));
		
	 }




	 public function commentPreProcess($commentdata)
	 {
        ob_flush();
$nsaa_token = get_option('nsaa_com_token');
require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
  $commentobj = new NSComment();

if( !isset( $_POST['i_token'] ) AND !is_admin() ) {
 $commentobj->saveComment($commentdata);
    die( __('Wait for the page to load before posting comments! Manners ', 'no-spam-at-all') );
}

if(isset($_POST['i_token'])){
if( ($_POST['i_token'] == $nsaa_token) ){ 
    //lets check the content for anchor and script tags
    $pattern =(get_option('nsaa_allow_links') =='Yes')? ("#(?:<script.+>.+</script>|<script>.+</script>)#i"): ("#(?:<script.+>.+</script>|<script>.+</script>|<a href.+>.+</a>)#i");
    if( preg_match($pattern, $commentdata['comment_content'] )  ) {
        $commentobj->saveComment($commentdata);
        echo "tags";
        exit;
    }
    else{
        return $commentdata;
        echo'success';
        exit;
    }

}

else { 

$commentobj->saveComment($commentdata);
die('Loaded');
}
//die();
}
elseif(is_admin() )
{
        return $commentdata;
        echo 'success';
        exit;


}

	 }
    
     public function tokenPaste()
     { 
       $nsaa_bad_comment = (get_option("nsaa_bad_comment"))? get_option("nsaa_bad_comment"):'Love your ruins';
       $nsaa_success_comment = (get_option("nsaa_success_comment"))? get_option("nsaa_success_comment"):'Love your comment';
      ?>
       <script type="text/javascript">
       let TYD344KDK487JDJQJS7JDH23UDJKKDH43UDJJJHFJSH = "<?php echo get_option("nsaa_com_token");?>";
       let imageSpinnerEle ='<img id="pend-load" class="image_loader_pre_content" src="<?php echo plugins_url(); ?>/no-spam-at-all/assets/img/loading.gif"/>';
       let goodmart_image = '<span> <img src="<?php echo plugins_url(); ?>/no-spam-at-all/assets/img/good.png"> </span>';

       let nsaa_bad_comment ="<?php echo $nsaa_bad_comment; ?>";
       let nsaa_success_comment ="<?php echo  $nsaa_success_comment; ?>";
       let isloggednsaa_comment ="<?php if(is_user_logged_in()): echo 'Yes'; else: echo 'No'; endif; ?>"

       let author_field_required ="<?php _e('Author name is required', 'no-spam-at-all') ?>"
       let email_field_required ="<?php _e('Valid email is required', 'no-spam-at-all') ?>"
       let comment_field_required ="<?php _e('Comment is required', 'no-spam-at-all')  ?>"
       let valid_email_field_required ="<?php _e('Valid Email is required', 'no-spam-at-all')  ?>"

       </script>
     <?php 
     }
}

new NSAction();