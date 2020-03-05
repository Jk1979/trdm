<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index_Articles extends Controller_Index 
{
	 // Главная страница
    public function before()
    {
        parent::before();
//        $category = Widget::load('category');
//        $this->template->left_block = array($category);

        $this->template->scripts[] = 'public/js/comment-reply.js';
        $this->template->scripts[] = 'public/js/comments.js';
      $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
      $this->breadcrumbs[] = array('name' => 'Статьи', 'link' => '/articles');
      $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
      $this->template->right_block = null;
      $this->template->description = 'В нашем интернет магазине плитки в Киеве, вы найдете большой ассортимент по лучшей цене.';
      $this->template->keywords = 'Продажа сантехники и плитки в Киеве. Интернет магазин сантехники и плитки';

    }    
	
    public function action_index()
    {
	$this->template->page_title = 'Статьи';	
        $this->template->page_caption = 'Статьи';	
        $articles = array();
        
        $count = ORM::factory('Article')->where('status','=',1)->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>10));
        
        $articles = ORM::factory('Article')
                ->where('status','=',1)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
            ->order_by('date','DESC')
                ->find_all();
        $content = View::factory($this->theme . '/index/articles/v_articles')
        ->bind('articles', $articles)
        ->bind('pagination', $pagination);
		$this->template->page_title = "Статьи о плитке и сантехнике. Интернет магазин плитки и сантехники Киев";		
        $this->template->center_block[] = $content;
    }  

    public function action_article()
    {
       // $id = (int) $this->request->param('id');
        $path =  $this->request->param('path');
        Cache::instance()->delete('comments'."_structure");
        $article = ORM::factory('Article')->where('path','=',$path)->find();

        $comments = $article->comments->order_by('parent_id','ASC')->find_all()->as_array();
        $countcomments = count($comments);
        $coms = array();
        foreach ($comments as $c) {
            if($c->parent_id == 0){ $coms[0][] = $c->as_array();}
            else $coms[$c->parent_id][] = $c->as_array();
        }
        $article = $article->as_array();
        $this->breadcrumbs[] = array('name' => $article['title'],
            'link' => '/articles/' . $article['id'] .'-'. $article['path']);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);

        if($article['meta_title'])
            $this->template->page_title = $article['meta_title'];
        else $this->template->page_title = $article['title'] . " Интернет магазин плитки и сантехники Киев";
        if($article['meta_description'])
            $this->template->description = $article['meta_description'];
        if($article['meta_keywords'])
            $this->template->keywords = $article['meta_keywords'];

        $content = View::factory($this->theme . '/index/articles/v_article')
                        ->bind('article', $article)
                        ->bind('countcomments', $countcomments)
                        ->bind('comments', $coms);

        $this->template->center_block[] = $content;
       /* $comments_url = 'comments/' . $article['id'];
        $comments = Request::factory($comments_url)->execute();

        switch($comments->status()) 
        {
          case 302: $this->redirect(Request::detect_uri()); break;
          case 200:  $this->template->center_block[] = $content; break;
        }*/
    }
   
}//class    

