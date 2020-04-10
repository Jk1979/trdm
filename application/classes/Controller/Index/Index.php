<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Index extends Controller_Common {
 
    public $template =  'boot/index/v_main'; // базовый шаблон default/
    protected $breadcrumbs = array();
    
    public function before()
    {
        parent::before();
        $this->stats();
        
        $search = Widget::load('search');
        $config = Kohana::$config->load('config');
        $this->template->styles = $config->get('styles_index');
        if($this->theme == "default")
        {
            //$this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
           
            $this->template->scripts[] = 'public/js/libs.js';
             $this->template->scripts[] = 'public/js/pricecalc.js';
            $this->template->scripts[] = 'public/js/js.js';
            $this->template->scripts[] = 'public/js/cart_min.js';
            
            //$this->template->scripts[] = 'public/js/double-slider.js';
            //$this->template->scripts[] = 'public/js/pricecalc.js';
            //$this->template->scripts[] = 'public/js/view_images.js';
        }
        if($this->theme == "boot")
        {
              //$this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
              //$this->template->scripts[] = 'public/js/jquery.liMenuVert.js';
              $this->template->scripts[] = 'public/js/libs.js';
              $this->template->scripts[] = 'public/js/common.js';
              $this->template->scripts[] = 'public/js/cart.js';
             // $this->template->scripts[] = 'public/js/cart_unminify.js';
              $this->template->scripts[] = 'public/js/js.js';
               $this->template->scripts[] = 'public/js/pricecalc.js';
        }
        $uri = $this->request->uri();
         $this->template->thisuri = (strlen($uri)>2)?$uri:""; 
        $this->template->topline = Widget::load('topline');
        $this->template->nav = Widget::load('newnavigation');
        $this->template->cart = Widget::load('cart');
        $this->template->search = $search;
        $this->template->topmenu = Widget::load('topmenu');
        $this->template->menulogin = '';//Widget::load('menulogin');
        
        
//        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
//         $this->breadcrumbs[] = array('name' => 'Каталог', 'link' => 'catalog/');
//         $this->breadcrumbs[] = array('name' => 'Категория', 'link' => 'catalog/cat');
//         
    }
    
    public function stats() {
        
        // Получаем IP-адрес посетителя и сохраняем текущую дату
        $visitor_ip = $_SERVER['REMOTE_ADDR'];
       // $ref = $_SERVER['HTTP_REFERER'];
        $date = date("Y-m-d");
        
        //$res = mysqli_query($db, "SELECT `visit_id` FROM `visits` WHERE `date`='$date'")
        $query = DB::select('visit_id')
        ->from('visits')
        ->where('date','=',$date);
        $result = $query->as_object()->execute();
        if ($result->count() == 0)
        {
            // Очищаем таблицу ips
            //mysqli_query($db, "DELETE FROM `ips`");
            $query = DB::delete('ips');
            $result = $query->execute();

            // Заносим в базу IP-адрес текущего посетителя
            //mysqli_query($db, "INSERT INTO `ips` SET `ip_address`='$visitor_ip'");
            $insert = DB::insert('ips', array('ip_address'))->values(array($visitor_ip))->execute();
            // Заносим в базу дату посещения и устанавливаем кол-во просмотров и уник. посещений в значение 1
            //$res_count = mysqli_query($db, "INSERT INTO `visits` SET `date`='$date', `hosts`=1,`views`=1");
            //$res_count = DB::insert('visits', array('date','hosts','views'))->values(array($date,1,1))->execute();
            $query = DB::insert('visits', array('date', 'hosts','views'))->values(array($date, 1,1));
            $result = $query->execute();
        }
        
        // Если посещения сегодня уже были
        else
        {
            // Проверяем, есть ли уже в базе IP-адрес, с которого происходит обращение
            //$current_ip = mysqli_query($db, "SELECT `ip_id` FROM `ips` WHERE `ip_address`='$visitor_ip'");
            $query = DB::select('ip_id')
            ->from('ips')
            ->where('ip_address','=',$visitor_ip);
            $result = $query->as_object()->execute();
            
            // Если такой IP-адрес уже сегодня был (т.е. это не уникальный посетитель)
            if ($result->count() == 1)
            {
                // Добавляем для текущей даты +1 просмотр (хит)
                //mysqli_query($db, "UPDATE `visits` SET `views`=`views`+1 WHERE `date`='$date'");
                $rows = DB::update('visits')->set(array('views' => DB::expr('views + 1')))
                    ->where('date', '=', $date)
                    ->execute();

            }

            // Если сегодня такого IP-адреса еще не было (т.е. это уникальный посетитель)
            else
            {
                // Заносим в базу IP-адрес этого посетителя
               // mysqli_query($db, "INSERT INTO `ips` SET `ip_address`='$visitor_ip'");
                $insert = DB::insert('ips', array('ip_address'))->values(array($visitor_ip))->execute();
                // Добавляем в базу +1 уникального посетителя (хост) и +1 просмотр (хит)
              //  mysqli_query($db, "UPDATE `visits` SET `hosts`=`hosts`+1,`views`=`views`+1 WHERE `date`='$date'");
                $rows = DB::update('visits')
                    ->set(array('hosts' => DB::expr('hosts + 1'),'views' => DB::expr('views + 1')))
                    ->where('date', '=', $date)
                    ->execute();
            }
        }
        
    }
 
} // End Common