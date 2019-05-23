<?php

class Status extends Model
{
	public function get($skill=0)
	{
		$skill = (int)$skill;

		$url = 'status';
		if ($skill !== 0)
		{
			$url .= '/'.$skill;
		}

		$status = parent::get($url);
		return $status->status;
	}
}