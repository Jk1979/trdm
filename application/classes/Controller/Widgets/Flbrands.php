<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Flbrands extends Controller_Widgets 
{
	 // Главная страница
   
public function action_index()
{

    $cache = Cache::instance();

    $filtersGet =  Request::initial()->param('filters');
    $filtersGet = strip_tags($filtersGet);

    $serie_id= 0;
    if(!$filtersGet) $filtersGet = "";
   
    $category = Controller_Index_Catalog::$category;
    $serie_id = Controller_Index_Catalog::$serie_id;
    $serie_path = Controller_Index_Catalog::$serie_path;
    $cat = $category->path;
    $catids = Controller_Index_Catalog::$cat_ids;
    $uri= preg_replace("/(\\/)(page(\d+))/",'', Request::initial()->uri());
    $uriar = explode('/',$uri);
$brands = array();
$brands_arr = array();
$prods=array();
$prodattrs=array();

$brand_path = Request::initial()->param('brand');

if(!$brand_path) $brand_path = null;
elseif(strpos($brand_path,'+') !== FALSE )  $brands_arr = explode("+",$brand_path);
elseif(strpos($brand_path,'+') === FALSE ) $brands_arr[] = $brand_path;


$filters = array(); // массив с фильтрами по опциям
$filtersId = array(); // id значения опций
$options_path = ""; // get строка опций
$attributes_path = ""; // get строка атрибутов
$attrs_arr= array();
$filtersGet= ltrim($filtersGet,'&');
$newFilterAr = explode("&",$filtersGet);

    if($filtersGet && count($newFilterAr))
    {   
        foreach ($newFilterAr as $k => $part)
        {
            if($part)
            {    
                if(strpos($part,'options:') !== FALSE )
                {
                    $tmpar = explode(':',$part);
                    $options_path = $tmpar[1];
                    if(strpos($options_path,'+') !== FALSE )
                        $filtersId = explode("+",$options_path);
                    else $filtersId[] = $options_path;
                }
                
                else {
                    
                    $attributes_path.= $part . "&"; 
                    $tmpar = explode(':',$part);
                    $attrs_arr[$tmpar[0]][]= $tmpar[1];
                }
            } /// if part
        }
      
        /****************
         * SELECT distinct t1.prod_id FROM `jk_froptionvalues` as t1  
         * join `jk_froptionvalues` as t2 on t1.prod_id = t2.prod_id 
         * join `jk_froptionvalues` as t3 on t1.prod_id = t3.prod_id  
         * WHERE t2.option_id in (76,77) and t3.option_id in (10)
         */
        $prod_opt_ids = null;
        if($options_path && count($filtersId))
        {
            $optionsar = array();
            
            $prod_opt_ids = ORM::factory('Filteroptions')
             ->where('option_id','IN',$filtersId)
             ->find_all();
            if($prod_opt_ids)
                    {
                        foreach ($prod_opt_ids as $p)
                        {
                            $optionsar[$p->filter_id][] = $p->option_id;
                           
                        }
                    } 
            if(count($optionsar))
            {
                $k=2; $qwhere =" WHERE";
                $query = 'SELECT distinct t1.prod_id FROM `jk_froptionvalues` as t1 ';
                foreach ($optionsar as $f => $op)
                { 
                    $opstr = implode(',', $op);
                    $query .= 'join `jk_froptionvalues` as t'.$k.' on t1.prod_id = t'.$k .'.prod_id ';
                    if($k>2) $qwhere .= ' AND';
                    $qwhere .= ' t'.$k.'.option_id in ('.$opstr.')';
                    $k++;
                }
            }
            $query .= $qwhere;
            $qresult=DB::query(Database::SELECT,$query)->execute()->as_array();

            foreach ($qresult as $r)
            {
              $prods[] = $r['prod_id'];  
            }   
           
        }
       
        if($attributes_path)
        {  
            if(count($attrs_arr))
            {

            $prod_attrids = ORM::factory('Prodattribut');
            $cols_attr = array_keys($prod_attrids->table_columns());
            $qwhere = " WHERE"; $and = false;

            //$q = "SELECT `jk_prodattribut`.`prod_id` AS `prod_id`, `jk_prodattribut`.`length` AS `length`, `jk_prodattribut`.`width` AS `width`, `jk_prodattribut`.`thick` AS `thick`, `jk_prodattribut`.`color` AS `color`, `jk_prodattribut`.`imitation` AS `imitation`, `jk_prodattribut`.`material` AS `material`, `jk_prodattribut`.`form` AS `form`, `jk_prodattribut`.`surface` AS `surface`, `jk_prodattribut`.`destination` AS `destination`, `jk_prodattribut`.`weight` AS `weight`, `jk_prodattribut`.`country` AS `country` FROM `jk_prodattributes` AS `jk_prodattribut` ";
            $q = "SELECT `jk_prodattribut`.`prod_id` AS `prod_id`, `jk_prodattribut`.`length` AS `length`, `jk_prodattribut`.`width` AS `width`, `jk_prodattribut`.`thick` AS `thick` FROM `jk_prodattributes` AS `jk_prodattribut` ";
              foreach ($attrs_arr as $k =>$v)
                {     $qwar = array();
                      if(!in_array($k, $cols_attr))  continue;
                       if($and) $qwhere .= " AND ("; else { $qwhere .= " ("; $and = true; }
                      foreach ($v as $val)
                      {
                        
                        if(strpos($val,'-') !== FALSE )
                        {
                            $diapazon = explode('-', $val);
                            $qwar[]= "(`".$k."` >= '" . $diapazon[0] . "' AND `".$k."` <= '" . $diapazon[1] . "')"; 
                            
                        } 
                        else {
                            $qwar[]= "`".$k ."` = '" . $val . "'";
                        }
                      } 
                      
                     $qwhere .= implode(" OR ", $qwar); 
                     $qwhere .= ")"; 
                }
                if(count($prods)) $qwhere .= " AND `prod_id` IN (" . implode(',',$prods) . ")";
                $q .= $qwhere; 

            $qresult=DB::query(Database::SELECT,$q)->execute()->as_array();
            foreach ($qresult as $r)
            {
              $prodattrs[] = $r['prod_id']; 
            }
            }
             $attributes_path = substr($attributes_path, 0, -1);
        }
        $prods = array_diff($prods,$prodattrs) + $prodattrs;  
    }
    
    if(count($prods)) Controller_Index_Catalog::$prodsFilter = $prods;
    
        $prodbrands_db = null;
        $prodbrands = array();
               
        if(count($brands_arr)) $prodbrands_db=ORM::factory('Product')
                ->join('brands')
                ->on('brands.brand_id', '=', 'product.brand_id')
                ->where('brands.path','IN',$brands_arr)
                ->find_all();
        if($prodbrands_db)
        {
            foreach ($prodbrands_db as $p)
            {
                $prodbrands[] = $p->prod_id;
            }
        }


        $brands = ORM::factory('Brand')
            ->join('products')
            ->on('brand.brand_id', '=', 'products.brand_id')
            ->select('products.prod_id')
            ->where('products.cat_id', 'IN', $catids)
            ->and_where('brand.status', '=', 1);

        if (count($prods)) $brands->and_where('products.prod_id', 'in', $prods);
        $brands = $brands->group_by('brand.title')
            ->order_by('brand.title', 'ASC')
            ->find_all();


    $brs=array();
    $i = 0;

    foreach ($brands as $b)
        {
            $brs[$i]["title"] = $b->title;
            if(!$brand_path)
            {
                $brs[$i]["path"] = "cat/" . $cat . "-" . $b->path;
                if($filtersGet) $brs[$i]["path"] .= "/filter=" . $filtersGet;
                $brs[$i]["check"] = false;
            }
            elseif($b->path === $brand_path)
            {
                $brs[$i]["path"] = "cat/" . $cat;
                if($filtersGet) $brs[$i]["path"] .= "/filter=" . $filtersGet;
                $brs[$i]["check"] = true;
            } 
            else 
            {
                if(in_array($b->path, $brands_arr))
                {

                    $newArr = $brands_arr;
                    if(($key = array_search($b->path,$newArr)) !== FALSE){
                         unset($newArr[$key]);
                    }
                    $newPath = implode("+", $newArr);
                    $brs[$i]["path"] = "cat/" . $cat . "-" . $newPath;
                    if($filtersGet) $brs[$i]["path"] .= "/filter=" . $filtersGet;
                    $brs[$i]["check"] = true;
                }
                else 
                {
                    $brs[$i]["path"] = "cat/" . $cat .  "-" . $brand_path . "+" . $b->path;
                    if($filtersGet) $brs[$i]["path"] .= "/filter=" . $filtersGet;
                    $brs[$i]["check"] = false;
                }  
            }
            $i++;

        }


    $cacheKey = 'filters.' . str_replace(array('+','?','&'),array('_','_','_'),$uriar[count($uriar)-1]);
    if(!$filtersGet && !$serie_path) {
        $filters = unserialize($cache->get($cacheKey));
    }
    
    if(empty($filters)) {
    $filtersDb = ORM::factory('Filters');
    if($category->parent_id == 53 && $category->cat_id != 83)   $filtersDb->where('cat_id','=',$category->parent_id);
    else $filtersDb->where('cat_id','=',$category->cat_id);
    $filtersDb = $filtersDb->find_all();       
    $i=0;
    if(count($filtersDb)>0)
    {
    foreach($filtersDb as $filter)
    {
       
      $query = "SELECT * FROM `jk_filteroptions` as fop join `jk_froptionvalues` as fr on fop.option_id = fr.option_id join `jk_products` as prods on fr.prod_id = prods.prod_id";
      $query .=" WHERE fop.filter_id = ".$filter->filter_id. " AND prods.cat_id IN (".implode(',',$catids).")";
      if($serie_id) $query .=" AND prods.series_id = ".$serie_id;
      if(count($prodbrands)) $query .=" AND fr.prod_id IN (".implode(',',$prodbrands).")";
      //if(count($prods))    $query .=" AND fr.prod_id IN (".implode(',',$prods).")";
      $query .=" GROUP BY fop.option_title ORDER BY fop.option_orderid ASC";
      $filterOptions = DB::query(DATABASE::SELECT, $query)->execute()->as_array();
        if(count($filterOptions)>0)
        {    
            $filters[$i]['title'] = $filter->filter_title;
            $options = array();
            $k=0;
            foreach($filterOptions as $op)
            {
                $options[$k]["title"] = $op['option_title'];
                $options[$k]["path"] = "cat/" . $cat;
                $options[$k]["opid"] = $op['option_id'];
                if($brand_path) $options[$k]["path"] .= "-" . $brand_path;
                if($serie_path) $options[$k]["path"] .= "/series-" . $serie_path;
                if(!$options_path)
                {
                     $options[$k]["path"] .= "/filter=options:" . $op['option_id'];
                     $options[$k]["check"] = false;
                     if($attributes_path) $options[$k]["path"] .= "&" . $attributes_path;
                     
                }
                elseif($op['option_id'] === $options_path)
                {
                    $options[$k]["check"] = true;
                    if($attributes_path) $options[$k]["path"] .= "/filter=" . $attributes_path;
                    
                } 
                else
                {
                    if(in_array($op['option_id'],$filtersId))
                    {    
                        $newArr = $filtersId;
                            if(($key = array_search($op['option_id'],$newArr)) !== FALSE){
                                 unset($newArr[$key]);
                            }
                            $newPath = implode("+", $newArr);
                            $options[$k]["path"] .= "/filter=options:" . $newPath;
                            $options[$k]["check"] = true;
                            if($attributes_path) $options[$k]["path"] .= "&" . $attributes_path;
                            
                    }
                    else 
                    {
                        $options[$k]["path"] .= "/filter=options:" . $options_path . "+" . $op['option_id'];
                        $options[$k]["check"] = false;
                        if($attributes_path) $options[$k]["path"] .= "&" . $attributes_path;
                        
                    }  
                } //else
                $k++;
                } //foreach filteroptions
            $filters[$i]['options'] = $options;
            $i++;
            }

        } //foreach $filtersDb
        } // if filtersDb
        if(!empty($filters)){
            foreach ($filters as $k=>$v)
            {
                usort($v['options'], array($this, 'sortByTitle'));
                $filters[$k]['options'] = $v['options'];
            }
        }

       if(!$filtersGet && !$serie_path) { $cache->set($cacheKey, serialize($filters), Date::MINUTE * 10000);}
    }
// Атрибуты

    $path_one = "cat/" . $cat;
    if($brand_path) $path_one .= "-" . $brand_path;
    if($serie_path) $path_one .= "/series-" . $serie_path;
    $attrbase = ORM::factory('Attributes')->find_all();
       
    $attrs = array();
    $attributes=array();
    foreach ($attrbase as $at)
    {
        $cats = explode(',',$at->catids);
        if(array_intersect($cats, $catids)) $attrs[]=$at;     
    }
    if(!empty($attrs)) {
        foreach ($attrs as $at) {
            $q = "SELECT COUNT(a.`prod_id`)  as c FROM `jk_prodattributes` as a JOIN `jk_products` as p on a.prod_id = p.prod_id WHERE a." . $at->path . "<>'' AND p.cat_id IN (" . implode(',', $catids) . ")";
            $qresult = DB::query(Database::SELECT, $q)->execute()->as_array();
            $kol = $qresult[0]['c'];
            if (!$kol) continue;

            $values = $at->attributesvalues->find_all();
            $k = 0;

            foreach ($values as $v) {

                $attributes[$at->name][$k]['title'] = $v->title;
                $attributes[$at->name][$k]['id'] = $v->attrval_id;
                if ($v->path) {
                    $tmp = '&' . $v->path;
                    $new_path = "";
                    if ($attributes_path) {

                        $newArr = $newFilterAr;
                        if (in_array($v->path, $newArr)) {
                            //$newArr = $params;
                            if (($key = array_search($v->path, $newArr)) !== FALSE) {
                                unset($newArr[$key]);
                            }
                            $new_path = implode("&", $newArr);
                            $attributes[$at->name][$k]['check'] = true;
                            if ($new_path) $new_path = "/filter=" . $new_path;
                            $attributes[$at->name][$k]['path'] = $path_one . $new_path;
                        } else {
                            $attributes[$at->name][$k]['check'] = false;
                            if ($filtersGet) $new_path = "/filter=" . $filtersGet . '&' . $v->path;
                            $attributes[$at->name][$k]['path'] = $path_one . $new_path;
                        }
                    } else {

                        $attributes[$at->name][$k]['check'] = false;
                        if ($filtersGet) $new_path = "/filter=" . $filtersGet . $tmp;
                        else $new_path = "/filter=" . $tmp;
                        $attributes[$at->name][$k]['path'] = $path_one . $new_path;
                    }


                }

                $k++;
            }

        }
    }

    /*echo View::factory('profiler/stats'); die;*/
   $clearpath = 'cat/' . $cat;
   $catlist = null;
   
   if($category->cat_id == 63 && !empty($brand_path)) {
    $q = "select c.* from `jk_categories` as c JOIN `jk_products` as p on c.cat_id = p.cat_id WHERE c.parent_id = " . $category->cat_id . " GROUP BY c.title";
    $catlist = DB::query(Database::SELECT, $q)->execute()->as_array();
    
    }
    
  $this->template->content = View::factory('widgets/w_flbrands')
            ->set('flbrands', $brs)
            ->bind('attributes', $attributes)
            ->bind('filter_path', $filtersGet)
            ->bind('clearpath', $clearpath)
            ->bind('cat', $cat)
            ->bind('catlist', $catlist)
            ->bind('brand_path', $brand_path)
            ->bind('filters', $filters);
  
 
}


function sortByTitle($a, $b){
        return ($a['title'] < $b['title']) ? -1 : 1;
    }
 
                        
    
}//class   