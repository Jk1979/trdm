<?php defined('SYSPATH') or die('No direct script access.');
 
class Breadcrumb {
   
   static function generate($bred_array){
      $bred_generated = array();
      $i = 0;
      foreach($bred_array as $bred){
         $i++;
         if ( ! isset($bred['link']) || $i == count($bred_array))
            $bred_generated[] = $bred['name'];
         else
            $bred_generated[] = '<a href="'.$bred['link'].'">'.HTML::chars($bred['name']).'</a>';
      }
      $bred_generated = '<div class="breadcrumb" id="breadcrumb">'.implode('<span class="separator"> / </span>', $bred_generated).'</div>';
      return $bred_generated;
   }
   
}
