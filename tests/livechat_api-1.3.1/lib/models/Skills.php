<?php

class Skills extends Model
{
	public function get($skill=0)
	{
		$skill = (int)$skill;

		$url = 'skills';
		if ($skill !== 0)
		{
			$url .= '/'.$skill;
		}

		return parent::get($url);
		
	}
	
	public function update($id, $vars)
	{
		$id = (int)$id;
		$url = 'skills/'.$id;
		
		return parent::put($url, $vars);
	}
	
	public function add($vars)
	{
		$url = 'skills';

		return parent::post($url, $vars);
	}
	
	public function delete($id)
	{
		$id = (int)$id;
		$url = 'skills/'.$id;

		return parent::delete($url);
	}
}