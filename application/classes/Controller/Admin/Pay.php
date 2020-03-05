<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pay extends Controller_Admin 
{
	 // Редактирование страниц сайта
    public function before() {
        parent::before();

        // Вывод в шаблон
        $this->template->submenu = Widget::load('menusettings');
        $this->template->page_title = 'Способы оплаты';
        $this->template->page_caption = 'Способы оплаты';
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Способы оплаты', 'link' => '/admin/pay');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }
    

    public function action_index() {
        $count = ORM::factory('Pay')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>20))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        $pay = ORM::factory('Pay')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();

        $content = View::factory($this->theme . '/admin/pay/v_pay_index', array(
            'pay' => $pay,
            'pagination' => $pagination,
            
        ));

        // Вывод в шаблон
         $this->template->center_block = array($content);
    }

    public function action_add() {

        if (isset($_POST['submit'])) {
            $data = Arr::extract($_POST, array('title','status'));
            $pay = ORM::factory('Pay');
            $pay->values($data);

            try {
                $pay->save();
                $this->redirect('admin/pay');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/pay/v_pay_add')
                ->bind('errors', $errors)
                ->bind('data', $data);
        
        $this->breadcrumbs[] = array('name' => 'Добавить способ оплаты', 'link' => '/admin/pay/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title .= ':: Добавить';
        $this->template->center_block = array($content);
    }

   
    public function action_delete() {
        $id = (int) $this->request->param('id');
        $pay = ORM::factory('Pay', $id);
        
        if(!$pay->loaded()){
            $this->redirect('admin/pay');
        }

        $pay->delete();
        $this->redirect('admin/pay');
    }
	

}