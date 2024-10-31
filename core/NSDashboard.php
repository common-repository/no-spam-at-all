<?php

class NSDashboard

{

	public function __construct()
	{
		add_action('wp_dashboard_setup', array($this, 'showCaseDashboard'));
		add_action('wp_ajax_callDeleteAllSpam', array($this,'callDeleteAllSpam'));

	}

public function callDeleteAllSpam()
{
  require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
  $commentobj = new NSComment();

 if($_POST['delete_xed_log']==1){  
 $xed_gone =$commentobj->deleteAllStoredSpam(); 
 ob_clean();
if($xed_gone ===true){

echo "<br><span class ='adminsuccess'>".__( ' Cleared all the log!!! Refresh Page! ', 'no-spam-at-all') ."</span>";
die();
   } 
   else{
 echo "<br><span class ='error'>".__(' Nothing to do! Sighs', 'no-spam-at-all')  ."</span>";
die();
   }
}


}

public function showCaseDashboard()
{
    wp_add_dashboard_widget('display_killed_widget','Displays spam comments stopped',array($this, 'dashboardList'));
}





public function dashboardList()
{
  require_once plugin_dir_path(dirname(__FILE__)).'models/NSComment.php';
  $commentobj = new NSComment();
?>
<div>    
<h2> <?php _e('  "No Spam At All" Said No', 'no-spam-at-all' ); ?></h2>
<hr>
<div class="n_xed_all_logs">  </div>
<?php 
$nsaa_total_xed =$commentobj->totalCaught(); ?>
<p> Total number stopped: <?php echo $nsaa_total_xed[0]->xed_no; ?>  

<?php if($nsaa_total_xed >0):?>
<button name ="delete_all_logged_xed" id ="delete_all_logged_xed" class="btn-log"> Delete All</button>
 <?php endif; ?>
</p>

<div class="cls"> </div>
<hr>
<div class="display_result_ajax_call"></div>
<?php $nsaa_xed_violaters =$commentobj->getLatestedCaught(); ?>
<table class="xed">
<thead>
<tr><th> SN</th>	<th>IP</th> <th><?php _e('Content', 'no-spam-at-all'); ?></th> <th> <?php _e('Time', 'no-spam-at-all'); ?></th> </tr>
</thead>

<tbody>

<?php 
//print_r($nsaa_xed_violaters);
$i =1;
if(!empty( $nsaa_xed_violaters )):
foreach ($nsaa_xed_violaters as $xed_violaters){  

	echo "<tr><td>". $i ."</td><td>".$xed_violaters->xed_comment_ip."</td><td>".$xed_violaters->xed_comment_post_content."</td><td>".$xed_violaters->xed_time."</td></tr>";
	$i++;
}
else:
echo '<tr> <td colspan="4"> '.__('All clean, spam comment box is empty', 'no-spam-at-all').' </td> </tr>';
endif;
?>

</tbody>
</table>
<br>
<?php if($nsaa_total_xed >0):?>
<a href="<?php echo admin_url(); ?>/options-general.php?page=nsaa_spam_comment_list_page" class="btn btn-md btn-more-list goto_more_list"> <?php _e('view and manage all suspicious comments', 'no-spam-at-all') ?> </a>
 <?php endif; ?>
</div> 
<?php
}

}

new NSDashboard();