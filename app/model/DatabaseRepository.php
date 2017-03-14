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
		$test_sets = $this->database->table('Test_set')->where('id_user',$id_user);
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
		$values['blue'] = $rgb['b'];
		$values['width'] = $width;
		if($values['printer_res'] == '')
			unset($values['printer_res']);
		if($values['scanner_res'] == '')
			unset($values['scanner_res']);
		if(isset($values['test_note']) and ($values['test_note'] == ''))
			unset($values['test_note']);	
		return $values;
	}
	public function insertResultForExistSet($values,$file,$width)
	{
		$rgb = $this->readResultFromFile($file);
		$values = $this->fillRecord($values,$rgb,$width);
		$this->database->table('Test')->insert($values);
		exec('rm '.$file);
	}
	public function insertResultForNewSet($values,$file,$width,$id_user)
	{	
		if($id_user != -99)	
		{
			$values['id_user'] = $id_user;
			$test_val['test_note'] = $values['test_note'];
			unset($values['test_note']);
		}

		$test_val['printer_res'] = $values['printer_res'];
		$test_val['scanner_res'] = $values['scanner_res'];
						
		unset($values['printer_res']);
		unset($values['scanner_res']);
		
		if($values['set_note'] == '')
			unset($values['set_note']);

		$row = $this->database->table('Test_set')->insert($values);
		$test_val['id_set'] = $row->getPrimary();

		$rgb = $this->readResultFromFile($file);
		$test_val = $this->fillRecord($test_val,$rgb,$width);
		
		$row_test = $this->database->table('Test')->insert($test_val);
		unset($test_val);
		unset($values);
		exec('rm '.$file);
		return $row_test->getPrimary();
	}

	public function getTestResult($id)
	{
		return $this->database->table('Test')->where('id_test',$id)->fetch();
	}
	public function getReference($width)
	{
		return $this->database->table('Test')->where('id_test',$width)->where('width',$width);
	}
	
}
