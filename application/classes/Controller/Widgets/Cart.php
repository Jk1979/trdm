<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Корзина"
 */
class Controller_Widgets_Cart extends Controller_Widgets {
    

    public function action_index()
    {
        $session = Session::instance();
        $products = $session->get('products');
        $sum = 0;
        $count = 0;
        if($products!=null) 
        {
            $prods = ORM::factory('Product');
            foreach ($products as $id => $c)
            {
                $prods->or_where('prod_id','=',$id);
                //$count += $c;
                $count++;
            }
            $prods  = $prods->find_all()->as_array();
          
            if(count($prods)>0)
            { 
                foreach ($prods as $prod)
                {
                    $sum += $prod->price * $products[$prod->prod_id];
                    
                }
            }
        }
        $sum = round($sum,2);
        
        $this->template->content = View::factory('widgets/w_cart')
                ->set('count',$count)
                ->set('sum',$sum);
    }

}