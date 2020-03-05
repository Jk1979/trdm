<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Test extends Controller_Admin
{
  
    
    
	public function action_getfilteroptions()
	{
		
		$str = '<div><table>';
		$cat_id = $this->request->param('id');
        if(!$cat_id) die;
        $filters = ORM::factory('Filters')->where('cat_id','=',$cat_id)->find_all();
		if(count($filters)>0)
		{
			foreach($filters as $filter)
			{
				$str .= '<tr><td>' . $filter->filter_title . '</td><td></td></tr>';
				$options = $filter->options->find_all();
				
				if(count($options)>0)
				{
					foreach($options as $opn)
					{
						$opnval = ORM::factory('Froptionvalues')->where('option_id','=',$opn->option_id)->find();
						if ($opnval->option_value == 1) {
							$sel = 'checked';
						}	
						else $sel = '';
						$str .= '<tr><td><input type="checkbox" name="foptions[]" value="' . $opn->option_id . '" '. $sel .'></td><td>' . $opn->option_title . '</td></tr>';
					}
				}
			}
			$str .= '</table></div>';
		}
		else {
			$str .= '<div><p>Нет фильтров для данной категории.</p></div>';
		}
         echo $str;
		
       
	}
}	