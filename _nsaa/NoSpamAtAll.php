<?php

require_once dirname(__FILE__).'/WPBaseField.php';
class NoSpamAtAll extends WPBaseField
{


   public function __construct()
   {
   	   $this->name = 'no-spam-at-all';
   	   $this->version = '1.3';
   	   $this->acro = 'NS';
   	   parent::__construct();
   }


    public function widget()
    {

    }

    public function shortcode()
    {
    	
    }


}


