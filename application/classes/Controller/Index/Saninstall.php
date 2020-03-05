<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Страницы
 */
class Controller_Index_Saninstall extends Controller_Index {

    public function before() {
        parent::before();
       $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
    $this->template->right_block = null;

    }
    public function action_index() {

        $item = ORM::factory('Page',3);
        $prices = ORM::factory('Saninstall')->find_all();
        $content = View::factory($this->theme . '/index/installation/v_install_index', array(
            'data'=>$item->as_array(),
            'prices'=>$prices
        ));
        
        $this->breadcrumbs[] = array('name' => 'Установка сантехнического оборудования', 'link' => '/saninstall');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Выводим в шаблон
        $this->template->title = 'Установка сантехнического оборудования Киев';
        $this->template->page_title = 'Установка сантехнического оборудования в Киеве, цены на сантехнические работы';
        $this->template->center_block = array($content);
        $this->template->description = 'Установка (монтаж) сантеники - ванны, унитазы, душевые кабины, смесители, теплый пол';
        $this->template->keywords = 'Цены установка сантехники, монтаж сантехники, цены установка унитазов, установка душевой кабины цена';

    }
    public function action_userform() {
        array_unshift($this->template->styles,'css/bootstrap.min');

        if(isset($_POST['send'])){
            //$form = serialize($_POST);
            unset($_POST['send']);
            $fio = isset($_POST['fio'])? $_POST['fio']: 'Nonamne';
            unset($_POST['fio']);
            $services = ORM::factory('Saninstall')->where('id','IN',$_POST['id'])->find_all();
            $fp = fopen('form_'.$fio.date('dmYhi').'.csv', 'w');
            if($fio) fputcsv($fp,array($fio),';');
            $message = $fio  . '<br/>';
            foreach ($services as $s ) {
                $line=[];
                $line[] = $s->title;
                $line[] = $_POST['price_'.$s->id];
                $message .=  $s->title . ' ' . $_POST['price_'.$s->id] . '<br/>';
           fputcsv($fp,$line,';');
            }
            fclose($fp);
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $code = @mail('jk280679@gmail.com','Цены на услуги',$message,$headers);
            $this->redirect(URL::base());
        }
        $ps = isset($_GET['ps'])? $_GET['ps'] : false;
        if($ps!='a1234321' ) $this->redirect('/404');

        $prices = ORM::factory('Saninstall')->find_all();
        $content = View::factory($this->theme . '/index/installation/v_install_userform', array(

            'prices'=>$prices
        ));

        $this->breadcrumbs[] = array('name' => 'Установка сантехнического оборудования', 'link' => '/installation/userform');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Выводим в шаблон
        $this->template->title = 'Установка сантехнического оборудования цены Киев';
        $this->template->page_title = 'Установка сантехнического оборудования в Киеве, цены на сантехнические работы';
        $this->template->center_block = array($content);
        $this->template->description = 'Установка (монтаж) сантеники - ванны, унитазы, душевые кабины, смесители, теплый пол';
        $this->template->keywords = 'Цены установка сантехники, монтаж сантехники, цены установка унитазов, установка душевой кабины цена';

    }



}