<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Новости
 */
class Controller_Index_News extends Controller_Index {
    
    public function before() {
        parent::before();

        // Выводим в шаблон
         $this->template->right_block = null;
         $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
        $this->breadcrumbs[] = array('name' => 'Новости', 'link' => '/news');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->description = 'В нашем интернет магазине плитки в Киеве - Трейдмаг, вы найдете большой ассортимент по лучшей цене.';
        $this->template->keywords = 'Купить плитку и сантехнику в интернет магазине Трейдмаг Киев. Раковины, ванны, смесители';

    }
    public function action_index() {
        
        $count = ORM::factory('News')->where('status','=',1)->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>10));
        
        $news = ORM::factory('News')
                ->where('status','=',1)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        $content = View::factory($this->theme . '/index/news/v_news_index', array('news'=>$news,'pagination'=>$pagination));
        
        // Выводим в шаблон
        $this->template->page_title = 'Новости интернет магазина';
        $this->template->page_caption = 'Новости';
        $this->template->center_block = array($content);
       
    }
    
     public function action_get() {
        
        $id =  (string) $this->request->param('id');
		$id =mysql_escape_string($id);
        $news = ORM::factory('News')->where('path','=',$id)->find();

        $content = View::factory($this->theme . '/index/news/v_news_one', array(
                'news' => $news,
            ));

        $this->breadcrumbs[] = array('name' => $news->title,
            'link' => '/news/' . $news->id .'-'. $news->path);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Выводим в шаблон
        $this->template->page_title = 'Новости интернет магазина в Киеве ';
        $this->template->page_caption = HTML::anchor('news', 'Новости') . " &rarr; ".  $news->title;
        $this->template->center_block = array($content);
    }


}