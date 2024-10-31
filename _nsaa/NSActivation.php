<?php


class NSActivation 
{

	public static function doActivation()
	{
           self::createTable();
           self::createSchedule();
	}


public  static function createSchedule() {
    if(get_option('nsaa_com_token') ==false ){  
    add_option( 'nsaa_com_token', 'we4r5fre3');
    add_option('nsaa_schedule_token','hourly');
}


$nsaa_check_sch = wp_next_scheduled('nsaa_tokens_generator');

if($nsaa_check_sch ==false){

wp_schedule_event( time(), get_option('nsaa_schedule_token'), 'nsaa_tokens_generator' );

}

}


	public static function firstTable(){
			global $wpdb;
			global $nsaa_xed_ips_db_version;
			$table_name = $wpdb->prefix .'nsaa_xed_comments';

			$charset_collate = $wpdb->get_charset_collate();

			$sql =  "CREATE TABLE $table_name (
			    xed_ip_id bigint(20) NOT NULL AUTO_INCREMENT,
			    xed_comment_ip varchar(16) DEFAULT '' NOT NULL,
			    xed_comment_post_content text NOT NULL,
			    xed_time datetime DEFAULT '0000-00-00:00:00',
			    UNIQUE KEY xed_ip_id (xed_ip_id)
			) $charset_collate";
			require_once(ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($sql);
}


	public static function secondTable(){
			global $wpdb;
			global $nsaa_xed_ips_db_version;
			$table_name = $wpdb->prefix .'nsaa_xed_comments';

			$charset_collate = $wpdb->get_charset_collate();

			$sql =  "CREATE TABLE $table_name (
			    xed_ip_id bigint(20) NOT NULL AUTO_INCREMENT,
			    xed_comment_ip varchar(16) DEFAULT '' NOT NULL,
			    xed_comment_post_content text NOT NULL,
			    status varchar(32)  NULL,
			    author varchar(255) NULL,
			    email varchar(128)  NULL,
			    post_id int(11)  NULL,
			    comment_parent int(11)  NULL,
			    xed_time datetime DEFAULT '0000-00-00:00:00',
			    UNIQUE KEY xed_ip_id (xed_ip_id)
			) $charset_collate";
			require_once(ABSPATH.'wp-admin/includes/upgrade.php');

			dbDelta($sql);
      }



public static function updateTable(){
			global $wpdb;
			global $nsaa_xed_ips_db_version;
			$table_name = $wpdb->prefix .'nsaa_xed_comments';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = 'ALTER TABLE '.$table_name.' ADD status varchar(32) NULL AFTER xed_comment_post_content';
			$sql .= 'ALTER TABLE '.$table_name.' ADD author varchar(32) NULL AFTER status';
			$sql .= 'ALTER TABLE '.$table_name.' ADD email varchar(32) NULL AFTER author';
			$sql .= 'ALTER TABLE '.$table_name.' ADD post_id int(11) NULL AFTER email';
			$sql .= 'ALTER TABLE '.$table_name.' ADD comment_parent int(11) NULL AFTER post_id';

			require_once(ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($sql);
 }


 public static function createTable()
 {
 	 
 	  $table_version = get_option( 'nsaa_xed_ips_db_version');
     if($table_version && $table_version =='1.0'){
     	  self::updateTable();
     	   update_option('nsaa_xed_ips_db_version', '2.0');

     }
     elseif($table_version ==false){
     	  self::secondTable();
     	  update_option('nsaa_xed_ips_db_version', '2.0');

     }
     elseif($table_version =='2.0'){
     	    // all good
     }



 }






}