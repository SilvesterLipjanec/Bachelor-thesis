<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

	define("ERR", -999);


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
		if(!file_exists($file))
		{
			return ERR;			
		}
		$myfile = fopen($file,'rb');
		if(!$myfile)
		{
			return ERR;
		}
		fscanf($myfile , "%f\t%f\t%f",$r,$g,$b);
		fclose($myfile);
		if(	$r > 255 || $r < 0 ||
			$g > 255 || $g < 0 ||
			$b > 255 || $b < 0 )
		{
			return ERR;
		}
		return array('r' => $r,
					 'g' => $g,
					 'b' => $b );
	}
	public function fillRecord($values,$rgb,$width)
	{

		$values['red'] = $rgb['r'];
		$values['green'] = $rgb['g'];
		$values['blue'] = $rgb['b'];
		$ref = $this->getReference($width);
		/*
		$values['dif_r'] = number_format((($values['red']*100)/($ref->red)-100),2);		
		$values['dif_g'] = number_format((($values['green']*100)/($ref->green)-100),2);
		$values['dif_b'] = number_format((($values['blue']*100)/($ref->blue)-100),2);
*/		

		$values['dif_r'] = number_format(((($ref->red*100)/255)-(($values['red']*100)/255)),2);		
		$values['dif_g'] = number_format(((($ref->green*100)/255)-(($values['green']*100)/255)),2);
		$values['dif_b'] = number_format(((($ref->blue*100)/255)-(($values['blue']*100)/255)),2);
		$values['width'] = $width;
		if($values['printer_res'] == '')
			unset($values['printer_res']);
		if($values['scanner_res'] == '')
			unset($values['scanner_res']);
		if(isset($values['test_note']) and ($values['test_note'] == ''))
			unset($values['test_note']);
		
		return $values;
	}
	public function insertResultForExistSet($values,$form)
	{
		$file = $form->getParameter('file');
		$width = $form->getParameter('width');
		$rgb = $this->readResultFromFile($file);
		if($rgb == ERR)
		{
			return ERR;
		}
		$values = $this->fillRecord($values,$rgb,$width);
		$row_test = $this->database->table('Test')->insert($values);
		exec('rm '.$file);
		return $row_test->getPrimary();
	}
	public function insertResultForNewSet($values,$form,$id_user)
	{	
		$file = $form->getParameter('file');
		$width = $form->getParameter('width');
		$values['id_user'] = 0;
		if($id_user != -99)	//je prihlaseny
		{
			$values['id_user'] = $id_user;
			$test_val['test_note'] = $values['test_note'];
			unset($values['test_note']);
		}

		$test_val['printer_res'] = $values['printer_res'];
		$test_val['scanner_res'] = $values['scanner_res'];
						
		unset($values['printer_res']);
		unset($values['scanner_res']);
		if($values['type'] == '')
			unset($values['type']);	
		if($values['set_note'] == '')
			unset($values['set_note']);

		$rgb = $this->readResultFromFile($file);
		if($rgb == ERR)
		{
			return ERR;
		}
		$row = $this->database->table('Test_set')->insert($values);
		$test_val['id_set'] = $row->getPrimary();
		
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
		return $this->database->table('Test')->where('id_test',$width)->where('width',$width)->where('test_note','REF')->fetch();
	}
	public function getTestSets($id_user)
	{
		return $this->database->table('Test_set')->where('id_user',$id_user);
	}
	public function getNumberTests($id_set)
	{
		return $this->database->table('Test')->where('id_set',$id_set)->count();
	}
	public function getNumberUserSets($id_user)	
	{
		return $this->database->table('Test_set')->where('id_user',$id_user)->count();
	}
	public function getSetDetail($id_set)
	{
		return $this->database->table('Test_set')->where('id_set',$id_set)->fetch();
	}
	public function getTestsForSet($id_set)
	{
		return $this->database->table('Test')->where('id_set',$id_set);
	}
	public function getTestsForSetCount($id_set)
	{
		return $this->database->table('Test')->where('id_set',$id_set)->count();
	}
	public function deleteTest($id_test)
	{
		$this->database->table('Test')->where('id_test',$id_test)->delete();
	}
	public function deleteSet($id_set)
	{
		$tests = $this->getTestsForSet($id_set);
		foreach ($tests as $test) {
			$this->deleteTest($test->id_test);
		}
		$this->database->table('Test_set')->where('id_set',$id_set)->delete();
	}
	public function getProducers()
	{
		$producers = $this->database->table('Test_set')->select('producer')->group('producer');
		
		$prod = array();
		foreach ($producers as $producer) {
			$prod[$producer->producer] = $producer->producer;
		}
		return $prod;
		
	}
	public function getModels($prod)
	{
		
		$models = $this->database->table('Test_set')->select('model')->where('producer',$prod)->group('model');
		$mod = array();
		foreach ($models as $model) {
			$mod[$model->model] = $model->model;
		}
		return $mod;
	}
	public function findTests($prod,$model)
	{
		return $this->database->table('test_v')->where('producer',$prod)->where('model',$model);
	}
	public function findTestsCount($prod,$model)
	{
		return $this->database->table('test_v')->where('producer',$prod)->where('model',$model)->count();
	}
	public function findBestTests()
	{
		return $this->database->table('test_v');
	}
	public function bestTestCount()
	{
		return $this->database->table('test_v')->count();
	}
}
