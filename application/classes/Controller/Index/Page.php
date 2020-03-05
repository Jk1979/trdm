<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Страницы
 */
class Controller_Index_Page extends Controller_Index {

    public function before() {
        parent::before();
       $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
    $this->template->right_block = null;

    }
    public function action_index() {
        $page_alias = $this->request->param('page_alias');
        //$page_alias = mysql_real_escape_string ($page_alias);
        $page = ORM::factory('Page')->where('alias', '=', $page_alias)->find();

        if(!$page->loaded() || $page->status == 0) {
            $this->redirect(URL::base());
        }
        
        $news = Widget::load('news');
        $content = View::factory($this->theme . '/index/page/v_page_index', array(
            'page' => $page, 'news' => $news
        ));
        
        $this->breadcrumbs[] = array('name' => $page->title, 'link' => '/' . $page_alias);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Выводим в шаблон
        $this->template->title = $page->title;
        $this->template->page_title = $page->title;
        $this->template->center_block = array($content);
        $this->template->description = 'В магазине плитки в Киеве Трейдмаг, вы найдете большой ассортимент по лучшей цене.';
        $this->template->keywords = 'Купить плитку и сантехнику в интернет магазине Трейдмаг Киев. Душевые кабины, унитазы, ванны';

    }

    // Статические страницы
    public function action_about() {
		$page = ORM::factory('Page')->where('alias', '=', 'about')->find();

        if(!$page->loaded() || $page->status == 0) {
            $text = '';
        }
		$text = $page->text;
        $news = Widget::load('news');
        
        $this->template->page_title = 'Об интернет магазине';
        $this->template->page_caption = 'О нас';
        $content = View::factory($this->theme . '/index/page/v_page_about',array('about'=>$text,'news'=>$news));
        $this->breadcrumbs[] = array('name' => 'Про нас', 'link' => '/about.html');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->center_block=array($content);
       $this->template->description = 'Трейдмаг лучший выбор плитки в Киеве по лучшей цене.';
        $this->template->keywords = 'Биде, раковины, унитазы, ванны';

    }

    // Контакты
    public function action_delivery() {
        $page = ORM::factory('Page')->where('alias', '=', 'delivery')->find();

        if(!$page->loaded() || $page->status == 0) {
            $text = '';
        }
        $text = $page->text;

        $this->template->page_title = 'Доставка - интернет-магазин Трейдмаг';
        $this->template->page_caption = 'Доставка и оплата';
        $content = View::factory($this->theme . '/index/page/v_page_delivery',array('content'=>$text));
        $this->breadcrumbs[] = array('name' => 'Доставка и оплата', 'link' => '/delivery');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->center_block=array($content);
        $this->template->description = 'В магазине плитки в Киеве Трейдмаг, вы найдете большой ассортимент по лучшей цене.';
        $this->template->keywords = 'Доставка плитки и сантехники, интернет магазин Трейдмаг, доставка Киев,доставка Украина';
    }
    public function action_contacts() {

        $contacts = ORM::factory('Contacts')->find();
        $captcha_image = Captcha::instance()->render();
        $post = $this->request->post();

        if(isset($post['send']))
        {
            $valid = Validation::factory($post);
            $valid->rules(
                'captcha', array(
                    array('not_empty'),
                    array('Captcha::valid')
                )
            );
            $valid->rule('name', 'not_empty');

            if ($valid->check()) {


                $data = Arr::extract($_POST, array('name', 'email', 'text'));
                $data['name'] = strip_tags($data['name']);
                $data['name'] = htmlspecialchars($data['name']);
                //$data['name'] = mysql_real_escape_string($data['name']);
                $data['email'] = strip_tags($data['email']);
                $data['email'] = htmlspecialchars($data['email']);
                //$data['email'] = mysql_real_escape_string($data['email']);
                $cPattern = '(?:(?:(?:http[s]?):\/\/)|(?:www.))(?:[-_0-9a-z]+.)+[-_0-9a-z]{2,4}[:0-9]*[\/]*'; //шаблон регулярного выражения
              mb_regex_encoding('UTF-8');             //кодировка строки
              $vRegs = array();                       //массив с подстроками
              mb_eregi($cPattern,  $data['text'], $vRegs);   //поиск подстрок в строке pValue
              if (count($vRegs)>0 || (stripos($data['text'], 'html')!== false))
              {
                    $this->redirect(URL::base());
              }
                //$data['text'] = mysql_real_escape_string($data['text']);
                $data['text'] = preg_replace("/href|url|http|www|\.ru|\.com|\.net|\.info|\.org|\.ua/i", "", $data['text']);
                $data['text'] = strip_tags($data['text']);

                if (!$data['name'] || !$data['email']) $this->redirect();
                $email_admin = Kohana::$config->load('config.email_admin');
                $sitename = Kohana::$config->load('config.sitename');
                $message = View::factory('/' . $this->theme . '/index/page/v_mail')
                    ->bind('name', $data['name'])
                    ->bind('email', $data['email'])
                    ->bind('message', $data['text']);
                $message = str_replace('\n', "\n", $message);
                $message = nl2br($message);
                $to = $email_admin;
                $subject = 'Сообщение с сайта !!!';
                $from = $data['email'];
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $code = @mail($to, $subject, $message, $headers);
            }
            else
            {
                $errors = $valid->errors('validation');
            }
        }
        $this->template->description = 'Самый большой ассортимент плитки в Киеве по лучшей цене.';
        $this->template->keywords = 'Купить плитку и сантехнику в интернет магазине Трейдмаг Киев. ';
        //$content = View::factory($this->theme . '/index/page/v_page_about');
        $this->breadcrumbs[] = array('name' => 'Контакты', 'link' => '/contacts.html');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->page_title = $contacts->title;
        //$this->template->page_caption = $contacts->title;
        $content = View::factory($this->theme . '/index/page/v_page_contacts', array('contacts'=>$contacts))
            ->bind('captcha_image', $captcha_image)
            ->bind('errors', $errors);
        $this->template->center_block = array($content);
    }


}