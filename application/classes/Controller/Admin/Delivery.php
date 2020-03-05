<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Delivery extends Controller_Admin 
{
	
    public function before() {
        parent::before();
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Доставка', 'link' => '/admin/delivery');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menusettings');
        $this->template->page_title = 'Способы доставки';
        $this->template->page_caption = 'Способы доставки';
    }
    

    public function action_index() {
        $count = ORM::factory('Delivery')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>20))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        $delivery = ORM::factory('Delivery')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();

        $content = View::factory($this->theme . '/admin/delivery/v_delivery_index', array(
            'delivery' => $delivery,
            'pagination' => $pagination,
            
        ));

        // Вывод в шаблон
         $this->template->center_block = array($content);
    }

    public function action_add() {

        if (isset($_POST['submit'])) {
            $data = Arr::extract($_POST, array('title','price','status'));
            $delivery = ORM::factory('Delivery');
            $delivery->values($data);

            try {
                $delivery->save();
                $this->redirect('admin/delivery');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/delivery/v_delivery_add')
                ->bind('errors', $errors)
                ->bind('data', $data);
        $this->breadcrumbs[] = array('name' => 'Дабавить вариант достаки', 'link' => '/admin/delivery/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ':: Добавить';
        $this->template->center_block = array($content);
    }

    

    public function action_delete() {
        $id = (int) $this->request->param('id');
        $delivery = ORM::factory('Delivery', $id);
        
        if(!$delivery->loaded()){
            $this->redirect('admin/delivery');
        }

        $delivery->delete();
        $this->redirect('admin/delivery');
    }
	

}