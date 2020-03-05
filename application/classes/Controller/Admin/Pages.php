<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin 
{
	 // Редактирование страниц сайта
    public function before() {
        parent::before();

        // Вывод в шаблон
        $this->template->submenu = Widget::load('menupages');
        $this->template->page_title = 'Страницы';
        $this->template->page_caption = 'Страницы';
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Страницы', 'link' => '/admin/pages');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }
    

    public function action_index() {
         $count = ORM::factory('Page')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>20))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        $pages = ORM::factory('Page')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();

        $content = View::factory($this->theme . '/admin/pages/v_pages_index', array(
            'pages' => $pages,
            'pagination' => $pagination,
            
        ));

        // Вывод в шаблон
         $this->template->center_block = array($content);
    }

    public function action_add() {

        if (isset($_POST['submit'])) {
            $data = Arr::extract($_POST, array('title', 'alias', 'text', 'status'));
            $pages = ORM::factory('Page');
            $pages->values($data);

            try {
                $pages->save();
                $this->redirect('admin/pages');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/pages/v_pages_add')
                ->bind('errors', $errors)
                ->bind('data', $data);
        $this->breadcrumbs[] = array('name' => 'Новая страница', 'link' => '/admin/pages/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ':: Добавить';
        $this->template->center_block = array($content);
    }

    public function action_edit() {
        $page_id = (int) $this->request->param('id');
        $pages = ORM::factory('Page', $page_id);
        
        if(!$pages->loaded()){
            $this->redirect('admin/pages');
        }

        $data = $pages->as_array();

        // Редактирование
        if (isset($_POST['submit'])) {
            $data = Arr::extract($_POST, array('title', 'alias', 'text', 'status'));
            $pages->values($data);

            try {
                $pages->save();
                $this->redirect('admin/pages');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/pages/v_pages_edit')
                ->bind('page_id', $page_id)
                ->bind('errors', $errors)
                ->bind('data', $data);

        $this->breadcrumbs[] = array('name' => $pages->title, 'link' => '/admin/pages/edit/' . $page_id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ' :: Редактировать';
        $this->template->center_block = array($content);
    }

    public function action_delete() {
        $id = (int) $this->request->param('id');
        $pages = ORM::factory('Page', $id);
        
        if(!$pages->loaded()){
            $this->redirect('admin/pages');
        }

        $pages->delete();
        $this->redirect('admin/pages');
    }
	

}