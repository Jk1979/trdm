<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Contacts extends Controller_Admin
{
	 // Настройки сайта
    public function before()
    {
        parent::before();
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Контакты', 'link' => '/admin/contacts');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->submenu = Widget::load('menusettings');
    }
    public function action_index()
    {
        
       $contacts = ORM::factory('Contacts')->find();
       if(isset($_POST['save'])) 
       {
        $data = Arr::extract($_POST, array('title','content','map', 'meta_title',
                                         'meta_keywords', 'meta_description'));
        
        $contacts->values($data);
        
            try {
                $contacts->save();
                 $this->redirect('admin/contacts');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
        $content = View::factory($this->theme . '/admin/settings/v_contacts')
                ->bind('contacts', $contacts)
                ->bind('errors', $errors)
                ->bind('data', $data);

        // Вывод в шаблон
        $this->template->page_title = 'Контакты';
        $this->template->page_caption = 'Контакты';
        $this->template->center_block = array($content);
        
    }  
	

}