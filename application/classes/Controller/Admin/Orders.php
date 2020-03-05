<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Orders extends Controller_Admin
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
        $this->breadcrumbs[] = array('name' => 'Заказы', 'link' => '/admin/orders');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }

    // Вывод всех заказов
    public function action_index() {
      

        // Вывод в шаблон
        $orders = ORM::factory('Order')->where('status','=',0);
        $count = $orders->reset(FALSE)->count_all();
        if($count) {
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>12,'view'=> 'pagination/floating'))
            ->route_params(array(
                      'controller' => Request::current()->controller(),
                       'action' => Request::current()->action(),
                                          ));
        $orders = $orders->limit($pagination->items_per_page)
                            ->offset($pagination->offset)
                            ->order_by('date','DESC')
                            ->find_all();
        } 
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
                $sum[$prod->prod_id]['sum'] = round($pr->count*$prod->price,2);
                $sum[$prod->prod_id]['count'] = $pr->count; 
                $sum_all += $sum[$prod->prod_id]['sum'];
                $sum_ord[$order->order_id] = $sum_all;
                
            }
        }
        
       
        $content = View::factory($this->theme . '/admin/orders/v_orders_index')
                ->bind('orders',$orders)
                ->bind('prods',$prods)
                ->bind('units',$units)
                ->bind('sum',$sum)
                ->bind('sum_all',$sum_ord)
                ->bind('pagination',$pagination);
               
        $this->template->center_block = array($content);
    }
    
    
     public function action_towork() 
    {
         //$order_id = Arr::get($_POST, 'order_id');
         $order_id = (int) $this->request->param('id');
         if($order_id)
         {
             $order = ORM::factory('Order',$order_id);
             $order->status = 1;
             $order->save();
             $this->redirect('admin/orders');
         }
    }
     public function action_delete() 
    {
        $order_id = (int) $this->request->param('id');
        
         if($order_id)
         {
             //$order = ORM::factory('Order',$order_id);
             
             $order_items = ORM::factory('Orderprods')
                     ->where('order_id','=',$order_id)
                     ->find_all();
             
             foreach($order_items as $item)
            {
                $item->delete();
            }

             $order = ORM::factory('Order',$order_id);
             $order->delete();
             $this->redirect('admin/orders');
         }
    }
	
	 public function action_sendno() 
    {
    	$order_id = (int) $this->request->param('id');
         if(!$order_id) $this->redirect('admin');
        
            $order = ORM::factory('Order',$order_id);
            $data = $order->as_array();
            $order_items = ORM::factory('Orderprods')
                     ->where('order_id','=',$order_id)
                     ->find_all();
            $products = array();
            foreach($order_items as $item)
            {
				$prod = ORM::factory('Product',$item->prod_id)->as_array();
				$prod['count'] = $item->count;
				$products[] = $prod;
			}
            foreach($products as $p)
            {
                $dtagr =  ORM::factory('Dataagromat',$p['code']);
                if($dtagr->loaded()) $units[$p['prod_id']] = $dtagr->unit;
            }
    		$email_admin = Kohana::$config->load('config.email_admin');
            $sitename = Kohana::$config->load('config.sitename');
            $deliv = ORM::factory('Delivery',$data['delivery_id']);
			$waypay = ORM::factory('Pay',$data['pay_id']);
                $wt = $waypay->title;
                $dt = $deliv->title;
			$to = $data['email'];
			$subject = 'Ответ по заказу на сайте trademag.com.ua';
			$from = $email_admin;
			$count = count($products);
            $message  = View::factory('/' . $this->theme . '/admin/orders/v_mailsendno')
                          ->bind('name',$data['name'])
                          ->bind('phone',$data['phone'])
                          ->bind('email',$data['email'])
                          ->bind('adress',$data['adress'])
                          ->bind('pay',$wt)
                          ->bind('delivery',$dt)
                          ->bind('sum',$data['sum'])
                          ->bind('message',$data['content'])
                          ->bind('products',$products)
                          ->bind('count',$count)
                          ->bind('units',$units);
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $code = @mail($to,$subject,$message,$headers);
//            $code = @mail($email_admin,$subject,'Отправлен ответ клиенту:<br/>'.$message,$headers);
            $order->message = 'Отправлен ответ нет в наличии';
            $order->save();
            $this->redirect('admin/orders');
	}

}