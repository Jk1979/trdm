<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Базовый класс  страницы администратора
 */
class Controller_Admin extends Controller_Common {

    public $template = 'boot/admin/v_admin';
    protected $breadcrumbs = array();
    
    public function  before() {
        parent::before();
        if (!$this->auth->logged_in('admin')) {
            $this->redirect('login');
        }
        $menu_admin = Widget::load('menuadmin');

        // Вывод в шаблон
        $config = Kohana::$config->load('config');
        $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
        $this->template->scripts[] = 'public/js/magn_popup_inline.js';
        $this->template->scripts[] = 'public/js/clickhandler.js';
        $this->template->scripts[] = 'public/js/libsadmin.js';


        //$this->template->scripts[] = 'public/js/tiny_mce.js';
        $this->template->styles = $config->get('styles_admin');
        $this->template->menu_admin = $menu_admin;
        $this->template->submenu = null;
        $this->template->page_title = 'Панель администрирования';
        
    }
}