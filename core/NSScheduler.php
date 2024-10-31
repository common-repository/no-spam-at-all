<?php


class NSScheduler
 {


        public function __construct()
        {
        	  add_action('nsaa_tokens_generator', array($this, 'tokenGenerator'));
        }


        public function tokenGenerator()
        {

        	 update_option('nsaa_com_token', $this->genToken() );

        }


		     
        public function clearSchedule()
        {
            wp_clear_scheduled_hook( 'nsaa_tokens_generator' );

        }

        public function genToken( $length = 64 )
           {
                   $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $count = mb_strlen($chars);

                    for ($i = 0, $token = ''; $i < $length; $i++) {
                        $index = rand(0, $count - 1);
                        $token .= mb_substr($chars, $index, 1);
                    }

                    return $token;
        }


}

new NSScheduler();