<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Main extends Controller_Admin 
{
	 // Главная страница
    public function action_index()
    {
        $adminstat = Widget::load('adminstat');
        $stat = $this->get_stats();
        $content = View::factory($this->theme . '/admin/main/v_main_index',array('adminstat' => $adminstat,'stat'=>$stat));
        $this->template->page_caption = 'Это админка';
		$this->template->center_block = array($content);
       // $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
       // $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
                
    }  
	
	public function get_stats(){
        $interval = 1;
        if (isset($_GET['interval']))
        $interval = $_GET['interval'];
            // Если в качестве параметра передано не число
            if (!is_numeric ($interval))
            {
                $interval = 1;
            }
       
        // Получаем из базы данные, отсортировав их по дате в обратном порядке в количестве interval штук
	//$res = mysqli_query($db, "SELECT * FROM `visits` ORDER BY `date` DESC LIMIT $interval");
        $res = DB::select('*')->from('visits')->order_by('date','DESC')->limit($interval)->execute();
        $content = View::factory($this->theme . '/admin/main/v_main_stats',array('result' => $res));
        return $content;
    }

}