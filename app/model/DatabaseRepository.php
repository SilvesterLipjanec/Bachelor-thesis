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
			$sada = $set->id_set.' - '.$set->producer.', '.$set->model.', '.$set->set_note;
			$sets[$set->id_set] = $sada;
		}
		return $sets;
	}
	public function readResultFromFile($file)
	{
		$myfile = fopen($file,'rb');
		if(!$myfile)
		{
			$this->flashMessage('Vyskytla sa chyba pri otváraní súboru!');
			exit(1);
		}
		fscanf($myfile , "%f\t%f\t%f",$r,$g,$b);
		fclose($myfile);
		return array('r' => $r,
					 'g' => $g,
					 'b' => $b );
	}
	public function fillRecord($values,$rgb,$width)
	{
		$values['red'] = $rgb['r'];
		$values['green'] = $rgb['g'];
		$values['blue'] = $b['b'];
		$values['width'] = $width;
		return $values;
	}
	public function insertResultForExistSet($values,$file,$width)
	{
		$rgb = readResultFromFile($file);
		$values = fillRecord($values,$rgb,$width);
		$this->database->table('Test')->insert($values);
		exec('rm '.$file);
	}
	public function insertResultForNewSet($values,$file,$width)
	{
		
		$val['set_note'] = $values['set_note'];
		$val['test_note'] = $values['test_note'];
		unset($values['set_note']);
		unset($values['test_note']);
		//var_dump($values);
		$this->database->table('Printers')->insert($values);

		//$rgb = readResultFromFile($file);
		//$values = fillRecord($values,$rgb,$width);


	}

}
