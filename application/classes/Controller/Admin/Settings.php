<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Settings extends Controller_Admin
{
	 // Настройки сайта
    public function before()
    {
        parent::before();
        
        $this->template->submenu = Widget::load('menusettings');
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Настройки', 'link' => '/admin/settings');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }
    public function action_index()
    {
       $settings = ORM::factory('Settings')->find();
       if(isset($_POST['save'])) 
       {
        $data = Arr::extract($_POST, array('email','phone_up','phone_down','mode',
            'copyright','slider_main','slader_inside','title_one','block_one',
            'title_two','block_two','meta_title','meta_keywords', 'meta_description'));
        
        $settings->values($data);
        
            try {
                $settings->save();
                 $this->redirect('admin/settings');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
        $content = View::factory($this->theme . '/admin/settings/v_settings_index')
                 ->bind('settings', $settings)
                ->bind('errors', $errors)
                ->bind('data', $data);

        // Вывод в шаблон
        $this->template->page_title = 'Настройки';
        $this->template->page_caption = 'Настройки';
        $this->template->center_block = array($content);
        
    }  
	

}