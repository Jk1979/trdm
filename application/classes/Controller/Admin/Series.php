<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Series extends Controller_Admin
{
   public function before() {
         parent::before();
         $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';  
         $this->template->scripts[] = 'public/js/genpath.js';
         $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
         $this->breadcrumbs[] = array('name' => 'Производители', 'link' => '/admin/brands');
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menuproducts');
    }
    

    public function action_index()
    {
        $id = $this->request->param('id');
        
        if($id)
            $count = ORM::factory('Serie')->where('brand_id','=',$id)->count_all();
        else
            $this->redirect('admin/brands');
            
        
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>50))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                    'id' => $id,
                                      ));
        $brand = ORM::factory('Brand',$id);
        $series = ORM::factory('Serie')
            ->where('brand_id','=',$id)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->order_by('title','ASC')
            ->find_all();
        $content = View::factory($this->theme . '/admin/series/v_series_index')
                ->set('series', $series)
                ->set('pagination', $pagination)
                ->set('brand_id', $id);
        
         $this->breadcrumbs[] = array('name' => $brand->title . ' : серии', 'link' => '/admin/series/index/' . $brand->brand_id);
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title = $brand->title . '- серии';
        $this->template->page_caption = $brand->title . ' - серии';
        $this->template->center_block = array($content);
    }  
    
    public function action_add() {
      
        $id = (int) $this->request->param('id');
        $brand = ORM::factory('Brand',$id);
        if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title','path','description'));
            $data['brand_id'] = $id;
            $serie =  ORM::factory('Serie');
            $serie->values($data);

            try {
                $serie->save();
                $this->redirect('admin/series/' . $id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }
       
        
           
       $content = View::factory($this->theme . '/admin/series/v_series_add')
            ->bind('errors',$errors)
            ->bind('data',$data);
       $this->breadcrumbs[] = array('name' => $brand->title . ' : серии', 'link' => '/admin/series/index/' . $brand->brand_id);
       $this->breadcrumbs[] = array('name' => $brand->title . ' : добавить серию', 'link' => '/admin/series/add/' . $brand->brand_id);
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = $brand->title . '::добаивть серию';
       $this->template->page_caption = $brand->title . '::добавить серию';
       $this->template->center_block = array($content);
        
    }
    
public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->request->redirect('admin/brands');
        }


        $serie = ORM::factory('Serie', $id);
        $brand_id = $serie->brand_id;
        $data = $serie->as_array();
        

        // Редактирование
        if (isset($_POST['save'])) {
            $data = Arr::extract($_POST, array('title','path','description'));
            $serie->values($data);

            try {
                $serie->save();

                $this->redirect('admin/series/' . $brand_id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/series/v_series_edit')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        $brand = ORM::factory('Brand', $brand_id);
       $this->breadcrumbs[] = array('name' => $brand->title . ' : серии', 'link' => '/admin/series/index/' . $brand->brand_id);
       $this->breadcrumbs[] = array('name' => $serie->title, 'link' => '/admin/series/edit/' . $serie->serie_id);
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title = $serie->title . ':: Редактировать';
        $this->template->center_block = array($content);
    }
    
    
    public function action_delete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/brands');
            
        }
        
        $serie = ORM::factory('Serie', $id);
        $brand_id = $serie->brand_id;
        $serie->delete();
        $this->redirect('admin/series/' . $brand_id);
    }	

}