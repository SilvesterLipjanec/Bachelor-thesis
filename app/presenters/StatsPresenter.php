<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
//use Nette\Forms\Controls\Button;

class StatsPresenter extends BasePresenter
{
	private $database;

	
	public function __construct(Model\DatabaseRepository $database)
	{
		$this->database = $database;
	}
	
	public function renderMytests($page = 1, $items = 0)
	{
		$id_user = $this->getUser()->getIdentity()->id_user;
		
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage(15); // the number of records on page
		$paginator->setPage($page);

		$sets = $this->database->getTestSets($id_user)->limit($paginator->getLength(), $paginator->getOffset());
		if( $items == 0 )
		{
			$totalPosts = $this->database->getNumberUserSets($id_user);
		} 
		else
		{
			$totalPosts = $items;
		}

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		

		$this->template->sets = $sets;		
		$this->template->cnt = array();
		foreach ($sets as $set) {
			$id_set = $set->id_set;
			$this->template->cnt[$id_set] = $this->database->getNumberTests($id_set);
		
		}
				
	}
	public function createComponentNextForm()
	{

		$form = new Form;
		$form->addSubmit('next',"Otestovať tlačiareň")
			->setAttribute('class',"w3-button w3-black w3-padding-large");;
		$form->onSuccess[] = array($this, 'goNext');
	  	return $form;
	}
	
	public function goNext()
	{
		
		$this->redirect('Homepage:download');
	
	}
	public function createComponentBackForm()
	{

		$form = new Form;
		$form->addSubmit('back',"Späť")
			->setAttribute('class','w3-button w3-grey');
		$form->onSuccess[] = array($this, 'goBack');
	  	return $form;
	}
	
	public function goBack()
	{
		switch ($this->getView()) {
			case 'setdetail':
				$this->redirect('Stats:mytests');
				break;
			
			default:
				# code...
				break;
		}
		
	
	}
	public function createComponentSetDetailForm()
	{ 
		$form = new Form;
		$form->addText('producer','Výrobca tlačiarne:')
			->setDisabled(TRUE)
			->setAttribute('class','w3-input w3-border w3-white');
		$form->addText('model','Model tlačiarne:')
			->setAttribute('class','w3-input w3-border w3-white');
		$form->addText('type','Typ tlačiarne:')
			->setAttribute('class','w3-input w3-border w3-white');
		$form->addTextArea('set_note','Poznámka:')		
			->setAttribute('class','w3-input w3-border w3-white');
		return $form;
	}
	public function renderSetDetail($id_set = 0,$page = 1, $items = 0)
	{
		$form = $this['setDetailForm'];
		if(!$form->isSubmitted()){
			$set = $this->database->getSetDetail($id_set);
			if(!isset($set)){
				$this->error('Záznam nebol nájdený');
			}
		
			
			$form->setDefaults(array(
			   	'producer' => $set->producer,
			   	'model' => $set->model,
			   	'type' => $set->type,
			   	'set_note' => $set->set_note,
			   	
			));
		}

		

		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage(5); // the number of records on page
		$paginator->setPage($page);
		$tests = $this->database->getTestsForSet($id_set)->limit($paginator->getLength(), $paginator->getOffset());		

		if( $items == 0 )
		{
			$totalPosts = $this->database->getTestsForSetCount($id_set);
		} 
		else
		{
			$totalPosts = $items;
		}

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		$this->template->id_set = $id_set;

		$this->template->tests = $tests;		
		$this->template->cnt = array();

	}
	public function renderDeleteTest($id_test)
	{
		$this->template->id_test = $id_test;
	}
	public function createComponentDeleteTestForm()
	{
		$form = new Form;
		$form->addSubmit('delete', 'Vymazať')
			->setAttribute('class', 'w3-button w3-grey')
			->setAttribute('style','width:200px')
			->onClick[] = array($this, 'deleteTest');

		$form->addSubmit('cancel', 'Zrušiť')
			->setAttribute('style','width:200px')
			->setAttribute('class', 'w3-button w3-black')
			->onClick[] = array($this, 'formCancelled');
		return $form;
	}
	public function formCancelled()
	{
		$this->redirect('Stats:setdetail');
	}
	public function deleteTest()
	{
		$id_test = $this->getParameter('id_test');
		$this->database->deleteTest($id_test);
		$this->redirect('Stats:mytests');
	}

	public function renderDeleteSet($id_set)
	{
		$this->template->id_set = $id_set;
	}
	public function createComponentDeleteSetForm()
	{
		$form = new Form;
		$form->addSubmit('delete', 'Vymazať')
			->setAttribute('class', 'w3-button w3-grey')
			->setAttribute('style','width:200px')
			->onClick[] = array($this, 'deleteSet');

		$form->addSubmit('cancel', 'Zrušiť')
			->setAttribute('style','width:200px')

			->setAttribute('class', 'w3-button w3-black')
			->onClick[] = array($this, 'formDelSetCancelled');
		return $form;
	}
	public function formDelSetCancelled()
	{
		$this->redirect('Stats:mytests');
	}

	public function deleteSet()
	{
		$id_set = $this->getParameter('id_set');
		$this->database->deleteSet($id_set);
		$this->redirect('Stats:mytests');
	}	
	public function createComponentSearchPrinterTestsForm()
	{
		$form = new Form;
		$producer = $this->database->getProducers();
		//var_dump($producer);
		$form->addSelect('printer','Značka tlačiarne:',$producer)
			->setAttribute('class','w3-select w3-white w3-border');
		$form->addSubmit('send','Pokračuj')
			->setAttribute('class','w3-button w3-grey')		
			 ->onClick[] = array($this, 'selectModel');

		return $form;
	}
	public function selectModel($button)
	{
		$values = $button->getForm()->getValues();
		$this->redirect('Stats:search',$values['printer']);

	}
	
	public function createComponentSearchModelForm()
	{
		$form = new Form;
		$prod = $this->getParameter('printer');
		$producer = $this->database->getProducers();
		$models = $this->database->getModels($prod);
		$form->addSelect('printer','Značka tlačiarne:',$producer)
			->setAttribute('class','w3-select w3-white w3-border');
		$form->addSelect('model','Model tlačiarne:',$models)
			->setAttribute('class','w3-select w3-white w3-border');
		$form->addSubmit('search','Vyhľadať')
			->setAttribute('class','w3-button w3-grey');
		$form->setAction($this->link('search'));
		$form->setMethod('GET');

		return $form;
	}
	
	public function actionSearch($printer, $model,$page = 1, $items = 0)
	{
		
		$this->template->printer = $printer;
		$this->template->model = $model;
		$prod = $this->getParameter('printer');
				
		$this['searchModelForm']->setDefaults(array('printer' => $printer,
													'model' => $model));
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage(5); // the number of records on page
		$paginator->setPage($page);

		$tests = $this->database->findTests($prod,$model)->limit($paginator->getLength(), $paginator->getOffset());
		

		if( $items == 0 )
		{
			$totalPosts = $this->database->findTestsCount($prod,$model);
		} 
		else
		{
			$totalPosts = $items;
		}

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		

		$this->template->tests = $tests;		
		$this->template->cnt = array();
	}
	
	public function renderStats($page = 1, $items = 0)
	{
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage(15); // the number of records on page
		$paginator->setPage($page);
		$this->template->itemsPP = $paginator->getItemsPerPage();
		$tests = $this->database->findBestTests()->limit($paginator->getLength(), $paginator->getOffset());
		

		if( $items == 0 )
		{
			$totalPosts = $this->database->bestTestCount();
		} 
		else
		{
			$totalPosts = $items;
		}

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		

		$this->template->tests = $tests;		
		$this->template->cnt = array();
						
	}

	
}
