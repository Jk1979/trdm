<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Articles extends Controller_Admin {

    public function before()
    {
        parent::before();
        $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
		$this->template->scripts[] = 'public/js/genpath.js';
        $this->template->scripts[] = 'public/js/uploadFromPage.js';
        $this->template->scripts[] = 'public/js/jquery-u.custom.min.js';
        $this->template->scripts[] = 'public/js/ui_datepicker.js';
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Статьи', 'link' => '/admin/articles');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menupages');
        $this->template->page_title = 'Статьи';
        $this->template->page_caption = 'Статьи';
    }

    public function action_index()
    {
        $count = ORM::factory('Article')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>10))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        
        $articles = ORM::factory('Article')
            ->select(array('categories.title','cat_title'))
            ->join('categories','LEFT')
            ->on('article.cat_id','=','categories.cat_id');
//        $cache_key = $articles->table_name()."_structure";
//        Cache::instance()->delete($cache_key);
        $articles = $articles->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->order_by('date')
                ->find_all();
        
        $content = View::factory($this->theme . '/admin/articles/v_articles_index', array(
            'articles' => $articles,'art_count' => $count,
            'pagination' => $pagination,
        ));


        // Вывод в шаблон
        $this->template->center_block = array($content);
    }


    public function action_edit()
    {
        $categories = ORM::factory('Category');
        $cats = array();
        $categories = $categories->fulltree()->as_array();
        foreach($categories as $cat) {
            $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
        }
        $id = (int) $this->request->param('id');

        $article = ORM::factory('Article',$id);
        $data = $article->as_array();

        if(isset($_POST['update']))
            {
                $data = Arr::extract($_POST, array('id','title','path','image','content_full','date','status',
                    'cat_id','meta_title','meta_keywords','meta_description','mainpage'));
                if(Arr::get($_POST, 'selectImage')) {
                $image = explode('/',Arr::get($_POST, 'selectImage'));
                echo $data['image'].'<br>'; $data['image'] = $image[count($image)-1];
                unset ($data['selectImage']);
                }

                $article = ORM::factory('Article',$data['id']);
                    if($article->loaded())
                    {
                        $data['content_short'] = Text::limit_words($data['content_full'], 100);
                        try
                        {
                            $article->values($data)->update();
                            $this->redirect('/admin/articles');
                        } 
                        catch  (ORM_Validation_Exception $e) 
                        {
                            $errors = $e->errors('validation');
                        }
                    }
             }

        $content = View::factory($this->theme . '/admin/articles/v_articles_edit')
                ->bind('cats', $cats)
                ->bind('data', $data)
                ->bind('errors', $errors);

        $this->breadcrumbs[] = array('name' => $article->title, 'link' => '/admin/articles/edit/' . $article->id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->center_block = array($content); 
    }

    public function action_add() 
    {
        $categories = ORM::factory('Category');
        $cats = array();
        $categories = $categories->fulltree()->as_array();
        foreach($categories as $cat) {
            $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
        }
     if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title','path','content_full','date','status',
                'cat_id','meta_title','meta_keywords','meta_description','mainpage'));
             $image = explode('/',Arr::get($_POST, 'selectImage'));
             $data['image'] = $image[count($image)-1];
             unset ($data['selectImage']);
            $article = ORM::factory('Article');

            $data['content_short'] = Text::limit_words($data['content_full'], 20);
             try 
                        {
                            $article->values($data)->create();
                            $this->redirect('/admin/articles');
                        } 
                        catch  (ORM_Validation_Exception $e) 
                        {
                            $errors = $e->errors('validation');
                        }
            
        }    
        $content = View::factory($this->theme . '/admin/articles/v_articles_add')
                 ->bind('errors', $errors)
                 ->bind('cats', $cats)
                 ->bind('data', $data);
        $this->breadcrumbs[] = array('name' => 'Добавить статью', 'link' => '/admin/articles/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->center_block = array($content);  
    }
    
    public function action_delete() 
    {
            $id = $this->request->param('id');
            $articles = ORM::factory('Article',$id)->delete();
            $this->redirect('/admin/articles'); 

    }
}