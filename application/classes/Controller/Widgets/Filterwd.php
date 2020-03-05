<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Filterwd extends Controller_Widgets 
{
	 // Главная страница
    
    public function action_index()
    {
	
    $cat = Request::initial()->param('cat');
        //$cat = mysql_real_escape_string ($cat);
    $category = ORM::factory('Category')->where('path', '=', $cat)->find();
        
    $catids = $this->get_where_cats($cat);
        
    $maxpricedb = ORM::factory('Product')->select(array(DB::expr('MAX(price)'), 'maxprice'))
        ->where('cat_id','IN',$catids)
        ->find()->as_array();
       
    
    if($maxpricedb['maxprice']) $price_to = $maxpricedb['maxprice'];
        else $price_to = 1;
        
    $price_from = 1;
    $current_price_from = isset($_GET['price_from'])?$_GET['price_from']:1;    
    $current_price_to = isset($_GET['price_to'])?$_GET['price_to']:$price_to;   
     
        $this->template->content = View::factory('widgets/w_filterwd')
            ->set('price_from', $price_from)
            ->set('price_to', $price_to)
            ->set('current_price_from', $current_price_from)
            ->set('current_price_to', $current_price_to);
    }
        
  protected function get_where_cats($catpath)
{
    $catids = array();
    $cats_chs = array();
    $cat = ORM::factory('Category')->where('path', '=', $catpath)->find();
    if(!$cat->loaded()){
        return null;
    }
    if($cat->has_children()) 
    {
        $cats_chs = $cat->children();
    
        if(count($cats_chs))
        {    
            $catids[] = $cat->cat_id;
            foreach($cats_chs as $c)
            {
                $catids[] = $c->cat_id; 
            }
            return $catids;
        }
    }
    else{
        $catids[] = $cat->cat_id;
        return $catids;
    }
    return null;
} 
    
}//class   