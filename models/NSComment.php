<?php

class NSComment 
{

public function saveComment($commentdata){
global $wpdb;

$table_name =$wpdb->prefix."nsaa_xed_comments";
$xed_comment_ip = $_SERVER['REMOTE_ADDR'];
if($xed_comment_ip =='::1'){
    $xed_comment_ip = '127.0.0.1';
}
$xed_time =current_time('mysql');
$commentdetails = array('xed_comment_ip'=>$xed_comment_ip, 'xed_comment_post_content'=>htmlspecialchars($commentdata['comment_content']), 'xed_time'=> $xed_time);
$commentdetails['author'] = $commentdata['comment_author'];
$commentdetails['email'] = $commentdata['comment_author_email'];
$commentdetails['post_id'] = $commentdata['comment_post_ID'];
$commentdetails['comment_parent'] = $commentdata['comment_parent'];

$wpdb->insert($table_name, $commentdetails);
    
}



public function userByEmail($email)
{
     return get_user_by( 'email', $email );
}
public function wpLevelComment($comment)
{
    $time = current_time('mysql');
$data = array(
    'comment_post_ID' => $comment->post_id,
    'comment_author' => $comment->author,
    'comment_author_email' => $comment->email,
    'comment_author_url' => '',
    'comment_content' => $comment->xed_comment_post_content,
    'comment_type' => '',
    'comment_parent' => $comment->comment_parent ,
    'comment_author_IP' => $comment->xed_comment_ip,
    'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
    'comment_date' => $time,
    'comment_approved' => 1,
);

$authordata = $this->userByEmail($comment->email);
if(is_object($authordata)){
    $data['user_id'] = $authordata->id;
}

wp_insert_comment($data);
 $this->updateStatus($comment->xed_ip_id);
 return true;
}
public function getLatestedCaught(){
global $wpdb;
$table= $wpdb->prefix.'nsaa_xed_comments';
$query= "SELECT * FROM ".$table." ORDER BY xed_ip_id DESC LIMIT 5";
$result_set = $wpdb->get_results( $query, OBJECT );

return $result_set;
}


public function checkTableExists(){
    global $wpdb;
    $table= $wpdb->prefix.'nsaa_xed_comments';
    $query= 'SELECT SHOW TABLES LIKE '.$table;
    $result_set = $wpdb->get_results( $query, OBJECT );
    if(count($result_set) >0){
        return true;
    }
    return false;
}




public function hasThisFields(){
global $wpdb;
$table= $wpdb->prefix.'nsaa_xed_comments';
$query= "SHOW COLUMNS FROM ".$table." LIKE status";
$result_set = $wpdb->get_row( $query, OBJECT );
 if($result_set){
     if($result_set->status){
        return true;
     }
 }
 return false;
}


public function getRow($xed_ip_id){
global $wpdb;
$table= $wpdb->prefix.'nsaa_xed_comments';
$query= "SELECT * FROM ".$table." WHERE xed_ip_id =".(int) $xed_ip_id;
return  $wpdb->get_row( $query, OBJECT );
 
}


public function updateStatus($xed_ip_id, $status ='Reposted')
{
     global $wpdb;
     $table= $wpdb->prefix.'nsaa_xed_comments';
     return  $wpdb->update($table, ['status'=>$status], ['xed_ip_id'=>$xed_ip_id]);
}


public function totalCaught(){

global $wpdb;
$table= $wpdb->prefix.'nsaa_xed_comments';
$query= "SELECT count(xed_ip_id) as xed_no FROM ".$table;
$nsaa_counts = $wpdb->get_results( $query,OBJECT);

return $nsaa_counts;
}

public function deleteAllStoredSpam(){

global $wpdb;
$table= $wpdb->prefix.'nsaa_xed_comments';
$query= "TRUNCATE TABLE ".$table;
$nsaa_x= $wpdb->query( $query );
if($nsaa_x):
return true;
else:
return false;
endif;
}


public function deleteByID($xed_ip_id){
    global $wpdb;
$nsaatable= $wpdb->prefix .'nsaa_xed_comments';
$query ="DELETE FROM ". $nsaatable. " WHERE xed_ip_id=".(int) $xed_ip_id;
if($nsaa_xed_queue =$wpdb->query( $query )):
    return true;
else:

return false;
endif;

}


public function deleteAllOnQueue(){
    global $wpdb;
$nsaatable= $wpdb->prefix .'comments';
$query ="DELETE FROM ". $nsaatable. " WHERE comment_approved='0'";
if($nsaa_xed_queue =$wpdb->query( $query )):
    return true;
else:

return false;
endif;

}


public function deleteAkisMetaDump(){
    global $wpdb;
    $akis_met = "%akismet\\_%";
$table= $wpdb->prefix.'commentmeta';
$query= $wpdb->prepare( 'DELETE FROM '.$table .' WHERE meta_key LIKE  %s', $akis_met) ;
 if( $nsaa_xed_meta =$wpdb->query ( $query ) ):

    return true;
    else:
return false;
endif;
}

public function deleteCommentsWithLinksOld(){
    global $wpdb;
$table= $wpdb->prefix.'comments';
$query='DELETE FROM '.$table.' WHERE comment_approved ="0" AND comment_content REGEXP "[[.less-than-sign.]]a[[.space.]]href.*[[.>.]].*[[.<.]]/a[[.>.]]"';
 if( $nsaa_delete_linked =$wpdb->query ( $query ) ):

    return true;
    else:
return false;
endif;
}

public function deleteCommentsWithLinks(){
    global $wpdb;
$table= $wpdb->prefix.'comments';
$query='DELETE FROM '.$table.' WHERE comment_approved ="0" AND (comment_content LIKE "%<a href=%>%" OR comment_content LIKE "%&lt;a href=")';
 if( $nsaa_delete_linked =$wpdb->query ( $query ) ):

    return true;
    else:
return false;
endif;
}

}