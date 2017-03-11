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
	public function findTestSetForUser($id_user)
	{
		$test_sets = $this->database->table('User_test_sets')->where('id_user',$id_user);
		$sets = array();
		foreach ($test_sets as $set) {
			$sada = $set->id_set.' - '.$set->producer.', '.$set->model.', '.$set->note;
			$sets[$set->id_set] = $sada;
		}
		return $sets;
	}


}
