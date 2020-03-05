<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Корзина
 */
class Controller_Index_Cart extends Controller_Index {

    public function before()
    {
        parent::before();
        // Выводим в шаблон
        $this->template->left_block = null;
        $this->template->right_block = null;
    }

    public function action_index() {
        
        $products_s = $this->session->get('products');
        $user = $this->auth->get_user();
        $units = array();
        if ($products_s != NULL)
        {
            $ids = array_keys($products_s);
            $products = ORM::factory('Product')->where('prod_id','IN',$ids)->find_all();

            foreach($products as $p)
            {
                $dtagr =  ORM::factory('Dataagromat',$p->code);
                if($dtagr->loaded()) $units[$p->prod_id] = $dtagr->unit;
            }
        }
        else
        {
            $products = NULL;
        }
        $deliverys = ORM::factory('Delivery')->find_all();
           foreach($deliverys as $d) 
           {
               $delivery[$d->id]= $d->title; /// . '(' .$d->price .' грн)';
           }
        $pays = ORM::factory('Pay')->find_all();
            foreach($pays as $p) 
            {
                $pay[$p->id]= $p->title;
            }
        
       if(isset($_POST['order'])) 
       {
            $data = Arr::extract($_POST, array('pay_id','delivery_id','name','phone','email','adress',
                'content','sum'));
            
            if($user) $data['user_id'] = $user->id;
            
            $data['date'] = date('Y-m-d H:i:s');
            $order = ORM::factory('Order');
            $order->values($data);
        
            try {
                    $order->save();
            
                    foreach($products_s as $p_id => $count)
                    {
                    $prods_ord = ORM::factory('Orderprods');
                    $prods_ord->order_id = $order->pk();    
                    $prods_ord->prod_id = $p_id;
                    $prods_ord->count = $count;
                    $prods_ord->save();
                    }
                    
                 /* Отправка писем админу и клиенту */
            $email_admin = Kohana::$config->load('config.email_admin');
            $sitename = Kohana::$config->load('config.sitename');
            $deliv = ORM::factory('Delivery',$data['delivery_id']);
			$waypay = ORM::factory('Pay',$data['pay_id']);
                $wt = $waypay->title;
                $dt = $deliv->title;
			$to = $email_admin;
      $toClient = $data['email'];
			$subject = 'Новый заказ';
			$from = $data['email'];
            $message  = View::factory('/' . $this->theme . '/index/cart/v_cart_mailadmin')
                          ->bind('name',$data['name'])
                          ->bind('phone',$data['phone'])
                          ->bind('email',$data['email'])
                          ->bind('adress',$data['adress'])
                          ->bind('pay',$wt)
                          ->bind('delivery',$dt)
                          ->bind('sum',$data['sum'])
                          ->bind('message',$data['content'])
                          ->bind('products_s',$products_s)
                          ->bind('products',$products)
                          ->bind('units',$units);
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $code = @mail($to,$subject,$message,$headers);
            $code = @mail('alexubk2@gmail.com',$subject,$message,$headers);
            $code = @mail($toClient,$subject,$message,$headers);
                
             /*************************************************/  
                if($this->session->get('products'))
                {
                    $this->session->delete('products');
                }
                
            $this->redirect('cart/success');
                }catch (ORM_Validation_Exception $e) 
                    {
                        $errors = $e->errors('validation');
                    }
           
          
         }

        $min_sum = Kohana::$config->load('config.min_order_sum');
        $content = View::factory('/' . $this->theme . '/index/cart/v_cart_index', array(
            'products' => $products,
            'products_s' => $products_s,
            'sum' => 0,
        ))
           ->bind('data' , $data)
           ->bind('units' , $units)
           ->bind('delivery', $delivery)
           ->bind('pay', $pay)
           ->bind('min_sum', $min_sum)
           ->bind('errors',$errors);

        // Выводим в шаблон
        $this->template->page_title = 'Ваша корзина товаров';
        $this->template->center_block = array($content);
        
    }



    public function xaction_XXorder()
    {
        
        $user = $this->auth->get_user();
        $products_s = $this->session->get('products');
        
        if(!$products_s) $this->redirect ('cart');
        
        $deliverys = ORM::factory('Delivery')->find_all();
           foreach($deliverys as $d) 
           {
               $delivery[$d->id]= $d->title . '(' .$d->price .' грн)';
           }
        $pays = ORM::factory('Pay')->find_all();
            foreach($pays as $p) 
            {
                $pay[$p->id]= $p->title;
            }
           
       if(isset($_POST['order'])) 
       {
            $data = Arr::extract($_POST, array('pay_id','delivery_id','name','phone','email','adress',
                'content'));
            
            
            
            if($user) $data['user_id'] = $user->id;
            
            $data['date'] = date('Y-m-d H:i:s');
            $order = ORM::factory('Order');
            $order->values($data);
            

            try {
//                if ( $data['adress'] == '' && $data['delivery_id'] != 1)
//                {    
//                    $extra_validation = Validation::factory($data)
//                        ->rule('adress', 'not_empty');
//                
//                    $order->save($extra_validation);
//                }
//                else 
//                
                $order->save();
                
                
            
                foreach($products_s as $p_id => $count)
                {
                $prods_ord = ORM::factory('Orderprods');
                $prods_ord->order_id = $order->pk();    
                $prods_ord->prod_id = $p_id;
                $prods_ord->count = $count;
                $prods_ord->save();
                }
                
                if($this->session->get('products'))
                {
                    $this->session->delete('products');
                }
                $this->redirect('cart/success');
                }
                
            
                catch (ORM_Validation_Exception $e) 
                {
                $errors = $e->errors('validation');
                }
          
         }
         
        $content = View::factory('/' . $this->theme . '/index/cart/v_cart_order')
           ->bind('data' , $data)
           ->bind('delivery', $delivery)
           ->bind('pay', $pay)
           ->bind('errors',$errors);
        // Выводим в шаблон
        $this->template->page_title = 'Оформление заказа';
        $this->template->page_caption = HTML::anchor('cart', 'Ваша корзина') . ' &rarr; ' . 'Оформление заказа';
        $this->template->center_block = array($content);
    } 

    public function action_success()
    {
        
        $content = View::factory('/' . $this->theme . '/index/cart/v_cart_success');
       
        // Выводим в шаблон
        $this->template->center_block = array($content); 
    }
    public function action_clear()
    {
        if($this->session->get('products'))
        {
            $this->session->delete('products');
        }
            $this->redirect('cart/');
        
    }
    public function action_ajaxclear()
    {
        if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
        if($this->session->get('products'))
        {
            $this->session->delete('products');
        }
       echo "ok";
        die;
        
    }
    
  
            
private function totalSumItems()
{
    $products_s = $this->session->get('products');
    
    $arr['count'] = 0;
    $arr['sum'] = 0;
    
       
        if($products_s)
        {
           $totalsum = 0; $totalelements = 0;
           foreach($products_s as $id => $c)
           {
               $product = ORM::factory('Product',$id);
               $totalsum += $product->price * $c;
               $totalelements += $c;
               $arr['count'] = $totalelements;
               $arr['sum'] = $totalsum;
           }
         return $arr;  
        }
}

private function GetTotalItems()
 {
    $products_s = $this->session->get('products');

    return count($products_s);
 }
 
 private function GetTotalSum()
 {
     
    $products_s = $this->session->get('products');
    $totalsum = 0;
    if($products_s)
    {
       
       foreach($products_s as $i => $c)
       {
           $product = ORM::factory('Product',$i);
           $totalsum += $product->price * $c;
       }
    }
    return $totalsum;
    
 }
public function action_getcartinfo()
{
    if ( ! $this->request->is_ajax())
    {
        $this->redirect(URL::base());
        die;
    }
    $data = array();
    $products_s = $this->session->get('products');
    $id = ($this->request->param('id'))?$this->request->param ('id'):null;
    $count = ($this->request->param('count'))?$this->request->param ('count'):null;

    if($id  && $products_s)
    {
        if(isset($products_s[$id]))
        {
            if($count === null){
                unset($products_s[$id]);

            }
            else {
                $products_s[$id] = $count;
            }
            if(count($products_s) == 0 )
            {
                $this->session->delete('products');
            }
            else
            {
                $this->session->set('products', $products_s);
            }
        }
        $products_s = $this->session->get('products');
    }

    $data['totalsum']=0;
    $data['totalelements']=0;
    if ($products_s != NULL)
    {
        $ids = array_keys($products_s);
        $products = ORM::factory('Product')->where('prod_id','IN',$ids)->find_all();
         foreach($products as $p)
        {
            $data['totalsum'] += $p->price * $products_s[$p->prod_id];
            $data[$p->prod_id]['code'] = $p->code;
            $data[$p->prod_id]['price'] = $p->price;
            $data[$p->prod_id]['title'] = $p->title;
            $data[$p->prod_id]['path'] = $p->path;
            $data[$p->prod_id]['count'] = $products_s[$p->prod_id];
            $data[$p->prod_id]['sum'] = round($data[$p->prod_id]['price'] * $data[$p->prod_id]['count'],2);
            $dtagr =  ORM::factory('Dataagromat',$p->code);
            if($dtagr->loaded()) $data[$p->prod_id]['units'] = $dtagr->unit;
            else $data[$p->prod_id]['units'] = 'шт';
            $data[$p->prod_id]['img'] = '/public/uploads/imgproducts_small/small_' . $p->main_image->name;
        }
        $data['totalelements'] = count($products_s);
        $data['totalsum'] = round($data['totalsum'],2);
    }
   echo json_encode($data);
    die;
}
 public function action_addElement()
 {
     if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
      
     $id = ($this->request->param ('id'))?$this->request->param ('id'):null;
     $count = ($this->request->param ('count'))?$this->request->param ('count'):null;
     if (!is_numeric($id))
        die;
     $products_s = $this->session->get('products');
     $data = array();
     $data['totalsum']=0;
     $data['totalelements']=0;
        if (isset($products_s[$id])) {
            $products_s[$id] += $count;
        }
        else {
            $products_s[$id] = $count;
        }
        if($products_s)
        {

            $ids = array_keys($products_s);
            $products = ORM::factory('Product')->where('prod_id','IN',$ids)->find_all();
           foreach($products as $p)
           {
               $data['totalsum'] += $p->price * $products_s[$p->prod_id];
               $data[$p->prod_id]['id'] = $p->prod_id;
               $data[$p->prod_id]['code'] = $p->code;
               $data[$p->prod_id]['price'] = $p->price;
               $data[$p->prod_id]['title'] = $p->title;
               $data[$p->prod_id]['path'] = $p->path;
               $data[$p->prod_id]['count'] = $products_s[$p->prod_id];
               $data[$p->prod_id]['sum'] = round($data[$p->prod_id]['price'] * $data[$p->prod_id]['count'],2);
               $dtagr =  ORM::factory('Dataagromat',$p->code);
               if($dtagr->loaded()) $data[$p->prod_id]['units'] = $dtagr->unit;
               else $data[$p->prod_id]['units'] = 'шт';
               $data[$p->prod_id]['img'] = '/public/uploads/imgproducts_small/small_' . $p->main_image->name;
           }
           
        }
     $data['totalelements'] = count($products_s);
     $data['totalsum'] = round($data['totalsum'],2);
        $this->session->set('products', $products_s);
        echo json_encode($data);
        die; 
 }
 
 public function action_ElementEdit()
 {
     if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
  $id = ($this->request->param ('id'))?$this->request->param ('id'):null;
  $count = ($this->request->param ('count'))?$this->request->param ('count'):null;
  $products_s = $this->session->get('products');

  
  if (isset($products_s[$id]))
  {
   
    $products_s[$id] = $count;
    $this->session->set('products', $products_s);
  }
  
 $data['sum'] = $this->GetTotalSum();
 $data['count'] = $this->GetTotalItems();
 
 
 echo json_encode($data);
 die;
 }
 
 public function action_ElementDelete()
 {

     if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
    $id = ($this->request->param ('id'))?$this->request->param ('id'):null;
    $products_s = $this->session->get('products');
    
    if (!is_numeric($id))
        die;
    if (isset($products_s[$id]))
    {
        unset($products_s[$id]);
    }
    if(count($products_s) == 0 )
    {
        $this->session->delete('products');
    }
    else
    {
        $this->session->set('products', $products_s);
    }
      
      $data['sum'] = $this->GetTotalSum();
      $data['count'] = $this->GetTotalItems();
        echo json_encode($data);
        die;
     
 }
  
  public function action_reload()
 {
      if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }

    $products_s = $this->session->get('products');
    $units = array();
        if ($products_s != NULL)
        {
            $ids = array_keys($products_s);
            $products = ORM::factory('Product')->where('prod_id','IN',$ids)->find_all();


            foreach($products as $p)
            {
                $dtagr =  ORM::factory('Dataagromat',$p->code);
                if($dtagr->loaded()) $units[$p->prod_id] = $dtagr->unit;
            }
        }
        else
        {
            $products = NULL;
        }
		
        $deliverys = ORM::factory('Delivery')->find_all();
           foreach($deliverys as $d) 
           {
               $delivery[$d->id]= $d->title . '(' .$d->price .' грн)';
           }
        $pays = ORM::factory('Pay')->find_all();
            foreach($pays as $p) 
            {
                $pay[$p->id]= $p->title;
            }


     $min_sum = Kohana::$config->load('config.min_order_sum');
     $content = View::factory('/' . $this->theme . '/index/cart/v_cart_index', array(
            'products' => $products,
            'products_s' => $products_s,
            'sum' => 0,
        ))
           ->bind('data' , $data)
           ->bind('units' , $units)
           ->bind('delivery', $delivery)
           ->bind('pay', $pay)
           ->bind('min_sum', $min_sum)
           ->bind('errors',$errors);
        // Выводим в шаблон
        echo $content;
        die;
 }
 
}//  class