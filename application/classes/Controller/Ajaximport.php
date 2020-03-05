<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajaximport extends Controller
{
    public function before() {
        parent::before();
      if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
        
    }
    public function action_cleantable()
    {
        $result = DB::query(Database::SELECT,"SELECT * FROM `tmpprice` WHERE 1")->execute();
        $kol = count($result);
        if($kol > 0)
        {
            $res = DB::query(NULL,"TRUNCATE TABLE `tmpprice`")->execute();
        }
         echo $res;
         die;
    }
    
}//class