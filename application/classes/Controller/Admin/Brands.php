<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Brands extends Controller_Admin
{
   public function before() {
         parent::before();
         
         $this->template->scripts[] = 'public/js/genpath.js';
         $this->template->scripts[] = 'public/js/uploadFromPage.js';
         $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
         $this->breadcrumbs[] = array('name' => 'Производители', 'link' => '/admin/brands');
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menuproducts');
    }
    

    public function action_index()
    {
        $count = ORM::factory('Brand')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>30))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        $brands = ORM::factory('Brand')
		
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
				->order_by('title','ASC')
                ->find_all();
        $content = View::factory($this->theme . '/admin/brands/v_brands_index')
                ->set('brands', $brands)
                ->set('pagination', $pagination);

        // Вывод в шаблон
        $this->template->page_title = 'Производители';
        $this->template->page_caption = 'Производители';
        $this->template->center_block = array($content);
    }  
    
    public function action_add() {
      
           
       if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title','path','status','description','meta_title','meta_description','meta_keywords'));
            $image = explode('/',Arr::get($_POST, 'selectImage'));
            $data['image'] = $image[count($image)-1];
            unset ($data['selectImage']);
            $brands = ORM::factory('Brand');
            
            $brands->values($data);
            
            

            try {
                $brands->save();
                $this->redirect('admin/brands');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
       
        
           
       $content = View::factory($this->theme . '/admin/brands/v_brands_add')
            ->bind('errors',$errors)
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Добавить производителя', 'link' => '/admin/brands/add');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Добаивть производителя';
       $this->template->page_caption = 'Добавить производителя';
       $this->template->center_block = array($content);
        
    }
    
public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/brands');
        }


        $brand = ORM::factory('Brand', $id);
        $data = $brand->as_array();
        

        // Редактирование
        if (isset($_POST['save'])) {
            $data = Arr::extract($_POST,  array('title','path','status','description','meta_title','meta_description','meta_keywords'));
            $image = explode('/',Arr::get($_POST, 'selectImage'));
            $data['image'] = $image[count($image)-1];
            unset ($data['selectImage']);
            $brand->values($data);

            try {
                $brand->save();

                $this->redirect('admin/brands');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/brands/v_brands_edit')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        
        
         $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/admin/brands/edit/' . $brand->brand_id);
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
	$this->template->page_title = 'Производители : Редактировать';
        $this->template->page_caption = 'Производители : Редактировать';
        $this->template->center_block = array($content);
    }
    
    
    public function action_delete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/brands');
            
        }
        $series = ORM::factory('Serie')
            ->where('brand_id','=',$id)
            ->find_all();
        foreach($series as $s)
        {
            $s->delete();
        }
        $brand = ORM::factory('Brand', $id);
        $brand->delete();
        $this->redirect('admin/brands');
    }	
public function action_articles()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        $this->redirect('admin/brands');
    }
        $brand = ORM::factory('Brand', $id);
        $cats = array();
        $articles = ORM::factory('Descbrandcat')->where('brand_id','=',$id);
        $count = $articles->reset(FALSE)->count_all();
        if($count) {
                $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>30))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        
        $articles = $articles->limit($pagination->items_per_page)
                    ->offset($pagination->offset)
                    ->find_all();
        foreach($articles as $art)
        {
           $cat = ORM::factory('Category',$art->cat_id);
           $cats[$art->id] = $cat->title;
        }
      }
      else $articles = null;
        $content = View::factory($this->theme . '/admin/brands/v_brands_articles')
                ->bind('brand', $brand)
                ->bind('articles', $articles)
                ->bind('cats', $cats)
                ->bind('pagination', $pagination);
         $this->breadcrumbs[] = array('name' => $brand->title . ' статьи', 'link' => '/admin/brands/articles/' . $brand->brand_id);
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title = 'Статьи по ' . $brand->title;
        $this->template->page_caption = 'Статьи по ' . $brand->title;
        $this->template->center_block = array($content);
}
public function action_addarticle()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        $this->redirect('admin/brands');
    }
    $brand = ORM::factory('Brand', $id);
    $categories = ORM::factory('Category')->find_all();
    
     if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('cat_id','description'));
            $data['brand_id']=$id;
            $newarticle = ORM::factory('Descbrandcat');
            $newarticle->values($data);
           try {
                $newarticle->save();
                $this->redirect('admin/brands/articles/'.$id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
       
        
           
       $content = View::factory($this->theme . '/admin/brands/v_brands_addarticle')
            ->bind('categories',$categories)
            ->bind('errors',$errors)
            ->bind('brand_id',$id)
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Добавить статью производителя '. $brand->title, 'link' => '');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Добаивть статью производителя ' . $brand->title;
       $this->template->page_caption = 'Добавить статью производителя '. $brand->title;
       $this->template->center_block = array($content);
    
}
public function action_editarticle()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        $this->request->redirect('admin/brands');
    }
    $categories = ORM::factory('Category')->find_all();
    $article = ORM::factory('Descbrandcat', $id);
    $data = $article->as_array();
    $brand = ORM::factory('Brand', $data['brand_id']);
     if (isset($_POST['save'])) {
            $data = Arr::extract($_POST,  array('cat_id','description'));
            $data['brand_id'] = $brand->brand_id;
            $article->values($data);

            try {
                $article->save();

                $this->redirect('admin/brands/articles/'. $brand->brand_id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }
        $content = View::factory($this->theme . '/admin/brands/v_brands_editarticle')
            ->bind('categories',$categories)
            ->bind('errors',$errors)
            ->bind('id',$id)
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Редактировать статью производителя '. $brand->title, 'link' => '');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Редактировать статью производителя ' . $brand->title;
       $this->template->page_caption = 'Редактировать статью производителя '. $brand->title;
       $this->template->center_block = array($content);
}
 public function action_deletearticle() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/brands');
            
        }
        $article = ORM::factory('Descbrandcat', $id);
        $brand_id = $article->brand_id;
        $article->delete();
        $this->redirect('admin/brands/articles/'.$brand_id);
    } 

}// class