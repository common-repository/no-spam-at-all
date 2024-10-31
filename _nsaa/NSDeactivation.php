<?php 


class NSDeactivation
{
     

     public static function doDeactivation()
     {
        wp_clear_scheduled_hook( 'nsaa_tokens_generator' );

     }



}