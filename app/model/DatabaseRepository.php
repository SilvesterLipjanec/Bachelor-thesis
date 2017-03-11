<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;



class DatabaseRepository
{
	//use Nette\SmartObject;

	/** @var Nette\Database\Context */
	private $database;
	

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}
	public function insert


}
