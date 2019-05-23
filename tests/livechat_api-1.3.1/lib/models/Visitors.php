<?php

class Visitors extends Model
{
	public function get($onlyChatting=false)
	{
		$url = $onlyChatting ? 'visitors/chatting' : 'visitors';
		return parent::get($url);
	}
}