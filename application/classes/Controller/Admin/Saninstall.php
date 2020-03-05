<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Saninstall extends Controller_Admin
{
	 // Редактирование страниц сайта
    public function before() {
        parent::before();

        // Вывод в шаблон
        array_unshift($this->template->styles,'bootstrap.min');
        $this->template->submenu = Widget::load('menupages');
        $this->template->page_title = 'Страницы';
        $this->template->page_caption = 'Страницы';
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Страницы', 'link' => '/admin/pages');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }
    

    public function action_index() {

        $items = ORM::factory('Saninstall')->find_all();


        $content = View::factory($this->theme . '/admin/saninstall/v_saninstall_index', array(
            'items' => $items
        ));

        // Вывод в шаблон
         $this->template->center_block = array($content);
    }

    public function action_add() {

        if (isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title', 'price', 'unit'));
            $san = ORM::factory('Saninstall');
            $san->values($data);

            try {
                $san->save();
                $this->redirect('admin/saninstall');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/saninstall/v_saninstall_add')
                ->bind('errors', $errors)
                ->bind('data', $data);
        $this->breadcrumbs[] = array('name' => 'Добавить услугу', 'link' => '/admin/saninstall/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ':: Добавить';
        $this->template->center_block = array($content);
    }

    public function action_edit() {
        $id = (int) $this->request->param('id');
        $item = ORM::factory('Saninstall', $id);
        
        if(!$item->loaded()){
            $this->redirect('admin/saninstall');
        }

        $data = $item->as_array();

        // Редактирование
        if (isset($_POST['edit'])) {
            $data = Arr::extract($_POST, array('title', 'price', 'unit'));
            $item->values($data);

            try {
                $item->save();
                $this->redirect('admin/saninstall');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/saninstall/v_saninstall_edit')
                ->bind('errors', $errors)
                ->bind('data', $data);

        $this->breadcrumbs[] = array('name' => $item->title, 'link' => '/admin/saninstall/edit/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ' :: Редактировать';
        $this->template->center_block = array($content);
    }

    public function action_delete() {

        $id = (int) $this->request->param('id');
        $item = ORM::factory('Saninstall', $id);
        
        if(!$item->loaded()){
            $this->redirect(Request::detect_uri());
        }

        $item->delete();
        $this->redirect(Request::detect_uri());
    }
    public function action_pageinfo() {

    $item = ORM::factory('Page',3);
     $data = $item->as_array();
        if (isset($_POST['save'])) {
            $data = Arr::extract($_POST, array('id','title', 'text', 'status','alias','meta_title','meta_keywords','meta_description'));
            $item->values($data);

            try {
                $item->save();
                $this->redirect('admin/saninstall/pageinfo');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }
        $content = View::factory($this->theme . '/admin/saninstall/v_saninstall_info')
            ->bind('errors', $errors)
            ->bind('data', $data);

        $this->breadcrumbs[] = array('name' => $data['title'], 'link' => '/admin/saninstall/pageinfo/');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ' Информация для страницы монтажа';
        $this->template->center_block = array($content);
    }
	

}