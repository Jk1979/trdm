<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Ajax controller
 */

class Controller_Index_Ajax extends Controller_Index {

    /**
    * @var  boolean  View auto render. Not used in AJAX
    */
   protected $auto_render = FALSE;

   /**
    * @var  mixed  Response data type(text, html, json, xml). If empty not convert data
    */
   protected $data_type = 'json';
   
   
      public function before()
    {

// Check request type
      if ( ! $this->request->is_ajax())
      {
         throw HTTP_Exception::factory(501, 'AJAX request not detected');
         // throw new Request_Exception('AJAX request not detected', 501);
      }
      
      // Set response data type
      $this->data_type = $this->param('data_type', $this->data_type);
      if ( ! empty($this->data_type) AND ! in_array($this->data_type, array('text', 'html', 'json', 'xml')))
      {
         throw HTTP_Exception::factory(500, 'Wrong response data type');
         // throw new Request_Exception('Wrong response data type', 500);
      }
      
       parent::before();
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
    $totalitems = 0;
    $products_s = $this->session->get('products');
    if($products_s)
    {
        foreach ($products_s as $i => $c)
        $totalitems += $c;
    }
    return $totalitems;
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
 
 public function action_addElement()
 {
     
     $id = ($this->request->param ('id'))?$this->request->param ('id'):null;
     $count = ($this->request->param ('count'))?$this->request->param ('count'):null;
     if (!is_numeric($id))
        die;
     $products_s = $this->session->get('products');

        if (isset($products_s[$id])) {
            $products_s[$id] += $count;
        }
        else {
            $products_s[$id] = $count;
        }
        if($products_s)
        {
           $totalsum = 0; $totalelements = 0;
           foreach($products_s as $id => $c)
           {
               $product = ORM::factory('Product',$id);
               $totalsum += $product->price * $c;
               $totalelements += $c;
           }
           
        }

        $this->session->set('products', $products_s);
        $content = View::factory('index/cart/v_element_add')
                ->bind('totalsum',$totalsum)
                ->bind('totalitems',$totalelements);
        echo $content;
        die; 
 }
 
 public function action_ElementEdit()
 {
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
    $products_s = $this->session->get('products');

        if ($products_s != NULL)
        {
            $products = ORM::factory('Product');

            foreach($products_s as $id => $count)
            {
                $products->or_where('prod_id', '=', $id);
            }

            $products = $products->find_all();
        }
        else
        {
            $products = NULL;
        }

        $content = View::factory('index/cart/v_cart_index', array(
            'products' => $products,
            'products_s' => $products_s,
            'sum' => 0,
        ));

        // Выводим в шаблон
        echo $content;
        die;
 }
    
} // class