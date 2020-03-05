<?php defined('SYSPATH') OR die('No direct script access.');
 
class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {
     
    public function get_response()
    {
        // Lets log the Exception, Just in case it's important!
        Kohana_Exception::log($this);

        if (Kohana::$environment >= Kohana::DEVELOPMENT) {
            // Show the normal Kohana error page.
            return parent::get_response();
        } else {
        // HTTP/404.php - это файл шаблона, можно указать другой файл
        $view = View::factory('HTTP404');
        $view->message = $this->getMessage();
        $response = Response::factory()
            ->status(404)
            ->body($view->render());
        return $response;
        }
    }
}
