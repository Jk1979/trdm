<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_News extends Controller_Admin {

    public function before()
    {
        parent::before();
	$this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
	$this->template->scripts[] = 'public/js/jquery-u.custom.min.js';
        $this->template->scripts[] = 'public/js/ui_datepicker.js';
	$this->template->scripts[] = 'public/js/genpath.js';
        
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Новости', 'link' => '/admin/news');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menupages');
        $this->template->page_title = 'Новости';
        $this->template->page_caption = 'Новости';
    }

    public function action_index()
    {
        $count = ORM::factory('News')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>30))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        
        $all_news = ORM::factory('News')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        
        $content = View::factory($this->theme . '/admin/news/v_news_index', array(
            'all_news' => $all_news,'all_news_count' => $count,
            'pagination' => $pagination,
        ));


        // Вывод в шаблон
        $this->template->center_block = array($content);
    }


    public function action_edit()
    {
        if(isset($_POST['update'])) 
            {
                $data = Arr::extract($_POST, array('id','title','path','content','date','status'));
                $news = ORM::factory('News',$data['id']);
                    if($news->loaded())
                    {
                        $data['intro'] = Text::limit_words($data['content'], 20);
                        //$data['date'] = date("d.m.Y");
                        $news->values($data);
                        try 
                        {
                            $news->save();
                            $this->redirect('/admin/news');
                        } 
                        catch  (ORM_Validation_Exception $e) 
                        {
                            $errors = $e->errors('validation');
                        }
                    }
             }
        $id = (int) $this->request->param('id');
        
        $news = ORM::factory('News',$id)->as_array();
        $content = View::factory($this->theme . '/admin/news/v_news_edit', array(
            'news' => $news,))
                ->bind('errors', $errors);
        
        $this->breadcrumbs[] = array('name' => $news['title'], 'link' => '/admin/news/edit/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->center_block = array($content);
    }

    public function action_add() 
    {
        if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title','path','content','date'));
            $news = ORM::factory('News');
            $data['intro'] = Text::limit_words($data['content'], 20);
            $news->values($data);
             try 
                        {
                           $news->save();
                            $this->redirect('/admin/news');
                        } 
                        catch  (ORM_Validation_Exception $e) 
                        {
                            $errors = $e->errors('validation');
                        }
            
        }    
        $content = View::factory($this->theme . '/admin/news/v_news_add')
                 ->bind('errors', $errors)
                 ->bind('data',$data);
        
        $this->breadcrumbs[] = array('name' => 'Добавить новость', 'link' => '/admin/news/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->center_block = array($content);
    }
    
    public function action_delete() 
    {
        $id = $this->request->param('id');
//        if(isset($_POST['delete'])) {
            $news = ORM::factory('News')->delete_news($id);
            $this->redirect('/admin/news');
//        }    

    }
}