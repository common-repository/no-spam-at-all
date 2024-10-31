<?php

require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
class NSListAllCommentPage
{


  public function __construct()
  {

		add_action( 'wp_ajax_repostDeleteThisComment', array($this,'repostDeleteThisComment') );

		add_action( 'wp_ajax_nsaaDeleteHasLinks', array($this,'nsaaDeleteHasLinks') );

		add_action( 'wp_ajax_nsaaClearMetaAkis', array($this,'nsaaClearMetaAkis') );

		add_action( 'wp_ajax_nsaaDeleteQueque', array($this,'nsaaDeleteQueque') );

		add_action('admin_menu', array($this,'nsaa_do_add_menu_page'));

  }
  

 public function repostDeleteThisComment()
 {
 	require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
  $commentobj = new NSComment();
 	 $typeof = htmlentities($_POST['actiontype']);
 	 $comment_id = (int) $_POST['comment_id'];

 	 if($typeof =='delete'){
 	 	  $commentobj->deleteByID($comment_id);
 	 	  echo 'OK';
 	 	  exit;
 	 }

 	 if($typeof =='repost'){

 	 	$row = $commentobj->getRow($comment_id);

 	 	if(!is_object($row)){
 	 		 echo 'Could not continue, Not a valid comment';
 	 		 exit;
 	 	}

 	 	if((int) $row->post_id ==0){
 	 		  echo 'Could not continue, Not a valid comment post id, old comment before version 1.3 cannot be reposted';
 	 		 exit;
 	 	}
      
       $commentobj->wpLevelComment($row);
       echo 'OK';
       exit;
       





 	 }
       
 }





 //function added to admin-ajax.php manages links deletion
function nsaaDeleteHasLinks(){
 $commentobj = new NSComment();

if ( isset($_POST['delete_has_links']) ) {
    ob_clean();
    if($_POST['delete_has_links'] =='1') { 
  $nsaa_xed_links = $commentobj->deleteCommentsWithLinks();

if($nsaa_xed_links ===true) {   
echo "<p class ='success'>". __('Likely spam comments deleted, successfully!!!', 'no-spam-at-all')." </p>";
die();
}

else{
    echo "<p class ='error'>". __('Nothing Found, Nothing to do. Sigh!', 'no-spam-at-all')." </p>";
die();
}

}
}

}


//function added to admin-ajax.php manages meta akis
function nsaaClearMetaAkis(){
	  $commentobj = new NSComment();

if ( isset($_POST['delete_ak_meta']) ) {
    ob_clean();
    if($_POST['delete_ak_meta'] =='1') { 
  $nsaa_xed_akis =$commentobj->deleteAkisMetaDump();

if($nsaa_xed_akis ===true) {   
echo "<p class ='success'>". __('Meta values taking database space deleted. successfully!!!', 'no-spam-at-all' )." </p>";
die();
}

else{
    echo "<p class ='error'> ".__('Nothing Found, Nothing to do. Sigh!', 'no-spam-at-all')." </p>";
die();
}

}
}

}



//Function added to admin-ajax.php to delete all on queque
function nsaaDeleteQueque(){
	$commentobj = new NSComment();
    if ( isset($_POST['delete_queue']) ) {
    ob_clean();
    if($_POST['delete_queue'] =='1') { 
  $nsaa_xed_deleted = $commentobj->deleteAllOnQueue();

if($nsaa_xed_deleted ===true) {   
echo "<p class ='success'>". __( ' Comment Queque, Successfully Cleared', 'no-spam-at-all')." </p>";
die();
}

else{
    echo "<p class ='error'> ". __("Sigh! Nothing to do.", "no-spam-at-all"). "</p>";
die();
}

}
}

}



  public function nsaa_do_add_menu_page(){
	add_options_page('List of stopped spam comment ', 'Spam Comment List', 'manage_options','nsaa_spam_comment_list_page', array( $this,'spamCommentListPage' ));
}






public function spamCommentListPage(){
	require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
  $commentobj = new NSComment();
  $nsaa_xed_violaters =$commentobj->getLatestedCaught();
?>
<h1> <img src="<?php echo plugins_url();  ?>/No-spam-at-all/assets/img/delete.png"> <?php _e('Manage Spam Comments pending Moderation', 'no-spam-at-all'); ?> </h1>
<hr>

<div class="display_result_ajax_call"></div>

<div class="flex-row boxes_of_no_spam_at_all">
	
	 <div class="first_inst">
<h3> <?php _e('Clear Akismet Meta values (Recommended)', 'no-spam-at-all'); ?>  </h3>
<button name ="nsaa_clear_wp_c_meta" id="nsaa_clear_wp_c_meta" class="nsaa_clear_btn"> <?php _e(' Clear all comment meta' , 'no-spam-at-all'); ?> </button> 
<p> <?php _e( 'if you were using akismet comment plugin, click to clear, stored comment information.', 'no-spam-at-all'); ?> </p>




	 </div>
	  <div class="second_inst">
	  	<h3> <?php _e('Delete All Pending comments containing anchor tags' , 'no-spam-at-all'); ?></h3>
<button name ="nsaa_clear_wp_c_links" id="nsaa_clear_wp_c_links" class="nsaa_clear_btn"> <?php _e('Delete Has Link', 'no-spam-at-all'); ?>  </button> 
<p> <?php _e("Clear all queue comments containing  atleat one anchor tag (sign of spam). Use the bottom below to clear all pending comments, if you don't really give a damn.", 'no-spam-at-all'); ?>   </p>

	  </div>
	   <div class="third_inst">
	   	
	   	<h3><?php _e('Delete All Pending comments' , 'no-spam-at-all'); ?></h3>
<button name ="nsaa_clear_wp_comment" id="nsaa_clear_wp_comment" class="nsaa_clear_btn"> <?php _e( '  Clear all pending comment' , 'no-spam-at-all'); ?>  </button> 
<p> <?php _e( '  This step is irreversible, It will delete all comment awaiting moderation' , 'no-spam-at-all'); ?>  </p>


	   </div>

</div>
<div class="table_level_list_of_comments">
	<table id="listospamcommentaction" class="table table-stripped table-big">
		<thead>
			<tr> 
				<th>SN</th> <th>IP</th> <th>Content</th> <th>Date</th> <th>Repost</th> <th>Delete</th>
			</tr>
		</thead>

		<tbody>

<?php if(count($nsaa_xed_violaters) >0):
          foreach($nsaa_xed_violaters as $scomment) : ?>
			
			<tr>
				<td><?php echo $scomment->xed_ip_id; ?></td>
				<td><?php echo $scomment->xed_comment_ip; ?></td>
				<td><?php echo $scomment->xed_comment_post_content; ?></td>
				<td><?php echo $scomment->xed_time; ?></td>
				<td><?php if($scomment->status !='Reposted'): ?> <button data-actiontype="repost" data-current_comment_id="<?php echo $scomment->xed_ip_id; ?>" class="click_each_action_nsaa_btn btn btn-xs btn-success"> Repost</button> <?php endif; ?> </td>
				<td><button data-actiontype="delete" data-current_comment_id="<?php echo $scomment->xed_ip_id; ?>" class="click_each_action_nsaa_btn btn btn-xs btn-danger"> delete</button></td>
			</tr>

		<?php endforeach;
		   else:
		   	echo '<tr class="thereis_nothinghere_now"> <td colspan="6"> '.__('No spam comment found', 'no-spam-at-all').' </td> <tr>';
		      endif; ?>
		</tbody>

	</table>
</div>
<div class="ns_donate_box_side_2">
 Donate Via Bitcoin:
 3HPrLAAqc4fYiuKeviD61JKRGhHmpVKixB
</div>

 <?php
}
}

if(is_admin()) $nsaa_options = new NSListAllCommentPage(); ?>
