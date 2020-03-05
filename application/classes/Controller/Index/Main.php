<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Index_Main extends Controller_Index {
    
     public function before()
    {
        parent::before();
        $this->template->scripts[] = 'public/js/libsmain.js'; 
        $this->template->scripts[] = 'public/js/mainpage.js'; 
       // $this->template->scripts[] = 'public/libs/nivoslider/jquery.nivo.slider.pack.js'; 
       // $this->template->scripts[] = 'public/libs/owl-carousel/owl.carousel.min2.js';
       // $this->template->scripts[] = 'public/libs/jquery-mousewheel/jquery.mousewheel.min.js';
    }
    // Главная страница
    public function action_index()
    {
       
        //$this->template->page_caption='О магазине';
        $data = ORM::factory('Settings',1);
        $mainnews = Widget::loadcache('mainnews');
        $topproducts = '';//Widget::loadcache('topproducts');
        $slider = Widget::loadcache('nivocarousel');
        $articles = Widget::loadcache('articlesmain');
        $reasons = Widget::load('reasons');
        $content = View::factory($this->theme . '/index/main/v_main_index')
            ->set('data',$data)
            ->bind('articles',$articles)
            ->bind('mainnews',$mainnews);
        $this->template->center_block = array($reasons,$topproducts, $content);
        //$this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->slider = $slider;
        $this->template->left_block = null;
        $this->template->right_block = null;
        if($data->meta_title) 
            $this->template->page_title = $data->meta_title;
        else $this->template->page_title = 'Интернет магазин керамической плитки и сантехники';
        if($data->meta_description) 
             $this->template->description = $data->meta_description;
        else $this->template->description = 'Если вы хотите купить плитку (Киев), то тогда Вам к нам. Большой ассортимент керамической плитки, сантехники,  керамогранита, паркетная доска, мозаика с доставкой по Киеву и Украине по лучшей цене';
        if($data->meta_keywords)
            $this->template->keywords = $data->meta_keywords;
        else $this->template->keywords = 'Купить плитку и сантехнику Киев. Интернет магазин сантехники и плитки';
        
    }  
 
} // End Common