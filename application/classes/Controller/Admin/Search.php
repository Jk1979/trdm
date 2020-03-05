<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Поиск
 */
class Controller_Admin_Search extends Controller_Admin {

    public function before() {
        parent::before();
        
        $this->template->right_block = null;
    }

    public function action_index() {
        
        if (isset($_POST['query']))
        {
            $search = Arr::get($_POST, 'query');
            $search = substr($search, 0, 128);
            $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
            $good = htmlspecialchars($search);

           
            $good = '%'.$good.'%';
			
            $search_result = ORM::factory('Product')
                    ->where('prod_id', 'LIKE', $good)
                    ->and_where('title', 'LIKE', $good)
                    ->or_where('code', 'LIKE', $good)
                    ->find_all()->as_array();
			}
			$pagination = " ";
        
        $content = View::factory($this->theme . '/admin/products/v_products_index')
                ->bind('products',$search_result)
                ->bind('pagination',$pagination);
        
        // Выводим в шаблон
        $this->template->page_title = 'Поиск';
        $this->template->page_caption = 'Поиск';
        $this->template->center_block = array($content);
        $this->template->left_block = null;
    }


}