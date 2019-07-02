<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Form_validation extends CI_Form_validation
{
  function __construct()
  {
    parent::__construct();
  }

  public function date($str){
    if (!DateTime::createFromFormat('d-m-Y', $str)) //yes it's YYYY-MM-DD
    {
        return FALSE;
    }
    else
    {
        return TRUE;
    }
  }
/*

  public function datetime($str)
  {
      $date_time = explode(' ',$str);
      if(sizeof($date_time)==2)
      {
          $date = $date_time[0];
          if(!DateTime::createFromFormat('d-m-Y', $date_values))
          {
              return FALSE;
          }
          $time = $date_time[1];
          if(!DateTime::createFromFormat('H\h i\m s\s', $time))
          {
              return FALSE;
          }
          return TRUE;
      }
      return FALSE;
  }*/
}