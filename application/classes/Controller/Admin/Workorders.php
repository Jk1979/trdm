<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Workorders extends Controller_Admin
{
    
    public function before() {
        parent::before();
        $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
        //$this->template->scripts[] = 'public/js/orders-submit.js';
        $submenu = Widget::load('menuorders');
        $this->template->page_title = 'Заказы';
        $this->template->page_caption = 'Заказы';
        $this->template->submenu = $submenu;
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Заказы в работе', 'link' => '/admin/workorders');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }

    // Вывод всех заказов
    public function action_index() {
      

        // Вывод в шаблон
        
        $orders = ORM::factory('Order')
                ->where('status','=',1)
                ->or_where('status','=',2)
                ->find_all()->as_array();
        $units = array();
        foreach ($orders as $order) 
        {
            $products = $order->products->find_all()->as_array();
            $sum_all = 0;
            foreach ($products as $pr)
            {
                $prod = ORM::factory('Product',$pr->prod_id);
                $dataagr = ORM::factory('Dataagromat')->where('code','=',$prod->code)->find();
                if($dataagr->loaded()) $units[$prod->prod_id] = $dataagr->unit;
                $prods[$order->order_id][] = $prod;
                $sum[$prod->prod_id]['sum'] = $pr->count*$prod->price; 
                $sum[$prod->prod_id]['count'] = $pr->count; 
                $sum_all += $sum[$prod->prod_id]['sum'];
                $sum_ord[$order->order_id] = $sum_all;
            }
        }
        $content = View::factory($this->theme . '/admin/orders/v_workorders_index')
                ->bind('orders',$orders)
                ->bind('prods',$prods)
                ->bind('sum',$sum)
                ->bind('units',$units)
                ->bind('sum_all',$sum_ord);
               
        $this->template->center_block = array($content);
    }
    
    
     public function action_orderdone() 
    {
         $order_id = (int) $this->request->param('id');
         if($order_id)
         {
             $order = ORM::factory('Order',$order_id);
             $order->status = 2;
             $order->save();
             $this->redirect('admin/workorders');
         }
    }
     public function action_delete() 
    {
         $order_id = (int) $this->request->param('id');
         if($order_id)
         {
             
             $order_items = ORM::factory('Orderprods')
                     ->where('order_id','=',$order_id)
                     ->find_all();
             
             foreach($order_items as $item)
            {
                $item->delete();
            }

             $order = ORM::factory('Order',$order_id);
             $order->delete();
             $this->redirect('admin/workorders');
         }
    }
	
    public function action_ordermade() {
      

        // Вывод в шаблон
        
        $orders = ORM::factory('Order')->where('status','=',2)->find_all()->as_array();
        $units = array();
        foreach ($orders as $order) 
        {
            $products = $order->products->find_all()->as_array();
            $sum_all = 0;
            foreach ($products as $pr)
            {
                $prod = ORM::factory('Product',$pr->prod_id);
                $dataagr = ORM::factory('Dataagromat')->where('code','=',$prod->code)->find();
                if($dataagr->loaded()) $units[$prod->prod_id] = $dataagr->unit;
                $prods[$order->order_id][] = $prod;
                $sum[$prod->prod_id]['sum'] = $pr->count*$prod->price; 
                $sum[$prod->prod_id]['count'] = $pr->count; 
                $sum_all += $sum[$prod->prod_id]['sum'];
                $sum_ord[$order->order_id] = $sum_all;
            }
        }
        $content = View::factory($this->theme . '/admin/orders/v_workorders_made')
                ->bind('orders',$orders)
                ->bind('prods',$prods)
                ->bind('sum',$sum)
                ->bind('units',$units)
                ->bind('sum_all',$sum_ord);
               
        $this->template->center_block = array($content);
    }

}