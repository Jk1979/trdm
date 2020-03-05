<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Поиск
 */
class Controller_Index_Search extends Controller_Index {

    public function before() {
        parent::before();
        
        $this->template->right_block = null;
    }

    public function action_index() {
        
        if (isset($_GET['query']))
        {
            

            $search = Arr::get($_GET, 'query');
            $search = substr($search, 0, 128);
            $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
            $good = htmlspecialchars($search);
            //$words = explode(' ', $good);
            //$words = "%" . implode('%" and title like "%', $words) . "%";
            
            $good = '%'.$good.'%';
			$brands = array();
			
			$brand = ORM::factory('Brand')
					->select('brand_id')
					->where('title', 'LIKE', $good)
					->find_all();
			if (count($brand) > 0)
			{
				foreach($brand as $a)
				{
					$brands[] = $a->brand_id;
				}			
				
			}
                       
            $searchres = ORM::factory('Product')
                    ->where('status','<>','0')
                    ->and_where('title', 'LIKE', $good)
                    ->or_where('code', 'LIKE', $good)
                    ->or_where('code', 'LIKE', $good)
                    ->or_where('meta_title', 'LIKE', $good)
                    ->or_where('meta_keywords', 'LIKE', $good)
                    ->or_where('meta_description', 'LIKE', $good);
            /*foreach ($words as $word)
            {
                $searchres->or_where('title', 'LIKE', '%' .$word . '%');
            }*/
            if(count($brands)) $searchres->or_where('brand_id', 'in', $brands);  
            $countprs = $searchres->reset(FALSE)->count_all();
            $pagination = Pagination::factory(
                array(
                    //'current_page'      => array('source' => 'route', 'key' => 'page'),
                    'total_items'=>$countprs,
                    'items_per_page'=>21,
                    'view'=> 'pagination/floating'));      
            $result =   $searchres->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all()->as_array();
			}
            $shortDesc = array();
            $dataAgr=array();
            $vowels = array("<br>","<br/>","<br />","<p>","</p>");
            if(count($result))
            foreach($result as $p)
            {
                $tempStr = str_replace($vowels, " ", $p->content);
                $shortDesc[$p->prod_id] = Text::limit_words(strip_tags($tempStr), 25);
                $dataAgrom = ORM::factory('Dataagromat')->where('code','=',$p->code)->find();
                if($dataAgrom->loaded())
                {
                    $dataAgr[$p->code]['unit'] = $dataAgrom->unit; 
                    $dataAgr[$p->code]['meters'] = $dataAgrom->meters;
                    $dataAgr[$p->code]['pieces'] = $dataAgrom->pieces;
                }
            }            
                        
                        
			//$result = array_merge ($search_in_products, $search_in_brands);
        
        $content = View::factory($this->theme . '/index/search/v_search_index')
                ->bind('query',$search)
                ->bind('result',$result)
                ->bind('dataAgrom', $dataAgr)
                ->bind('shortDesc', $shortDesc)
                ->bind('pagination',$pagination);
        
        // Выводим в шаблон
        $this->template->page_title = 'Поиск';
        $this->template->page_caption = 'Поиск';
        $this->template->center_block = array($content);
        $this->template->left_block = null;
    }


}