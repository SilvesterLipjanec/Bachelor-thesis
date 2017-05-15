<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
//use Nette\Forms\Controls\Button;

class StatsPresenter extends BasePresenter
{	
	 /** @var Nette\Http\Session */
	private $session;
	private $database;
	/** @persistent */
	public $items = 20;

	/** @persistent */
	public $page = 1;

	/** @persistent */
	public $filterStatsEnabled = false;
	/** @persistent */
	public $filterSearchEnabled = false;
	/** @persistent */
	public $filterSetEnabled = false;
	

	
	public function __construct(Model\DatabaseRepository $database,Nette\Http\Session $session)
	{
		$this->database = $database;
		$this->session = $session;
	}
	
	public function renderMysets($page = 1,$order= 'producer' ,$lstOrd = 'x',$desc = 0)
	{
		//
		$this->session->getSection('set_filter')->tests = [];
		$this->filterSetEnabled = false;
		//
		$id_user = $this->getUser()->getIdentity()->id_user;		
		$paginator = new Nette\Utils\Paginator;
		$this->page = $page;
		$this->template->order = $order;		
		if($order == $lstOrd)
		{
			if($desc)
				$desc = 0;
			else
				$desc = 1;
		}
		else if($lstOrd != 'x')
			$desc = 0;

		$this->template->desc = $desc;
		$order = $desc == 1 ?   $order.' DESC' : $order;
		$paginator->setItemsPerPage($this->items); // the number of records on page
		$paginator->setPage($page);
		if($this->getUser()->getIdentity()->role == 'admin')
		{
			$sets = $this->database->getAllSets();
		}
		else
		{
			$sets = $this->database->getTestSets($id_user);			
		}
		$sets->order($order)->limit($paginator->getLength(), $paginator->getOffset());

		if($this->getUser()->getIdentity()->role == 'admin')
			$totalPosts = $this->database->getNumberSets();
		else
			$totalPosts = $this->database->getNumberUserSets($id_user);

		$paginator->setItemCount( $totalPosts );
		
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		

		$this->template->sets = $sets;		
		$this->template->cnt = array();
		$this->template->userlogin = array();
		foreach ($sets as $set) {
			$id_set = $set->id_set;
			$this->template->cnt[$id_set] = $this->database->getNumberTests($id_set);
			$row = $this->database->getUserById($set->id_user);
			$this->template->userlogin[$id_set] = $row['login'];		
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
				$this->redirect('Stats:mysets');
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
			->setDisabled(TRUE)
			->setAttribute('class','w3-input w3-border w3-white');
		$form->addText('type','Typ tlačiarne:')
			->setDisabled(TRUE)
			->setAttribute('class','w3-input w3-border w3-white');
		$form->addTextArea('set_note','Poznámka:')
	 		->setDisabled(TRUE)		
			->setAttribute('class','w3-input w3-border w3-white');
		return $form;
	}
	public function renderSetDetail($id_set,$page = 1,$order = 'sum' ,$lstOrd = 'x',$desc = 0)
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
		$this->template->order = $order;	
		if($order == $lstOrd)
		{
			if($desc)
				$desc = 0;
			else
				$desc = 1;
		}
		else if($lstOrd != 'x')
			$desc = 0;

		$this->template->desc = $desc;
		$order = $desc == 1 ? $order.' DESC' : $order;

		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage($this->items); // the number of records on page
		$paginator->setPage($page);
		$filter = $this->template->filter = $this->session->getSection('set_filter')->tests;
		$tests = $this->database->getTestsForSet($id_set);		
		
		if ($this->filterSetEnabled) {
			$tests->where('test', $filter);
		}
		$tests = $tests->order($order)->limit($paginator->getLength(), $paginator->getOffset());
		
		$totalPosts = $this->database->getTestsForSetCount($id_set,$this->filterSetEnabled ? $filter : null);	

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;
		
		$this->template->id_set = $this->getParameter('id_set');
		$this->template->tests = $tests;		
		$this->template->cnt = array();

	}
	public function renderDeleteTest($id_test,$id_set)
	{
		$this->template->id_test = $id_test;
		$this->template->id_set = $id_set;
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
		$id_set = $this->getParameter('id_set');
		$this->redirect('Stats:setdetail',$id_set);
	}
	public function deleteTest()
	{
		$id_test = $this->getParameter('id_test');
		$id_set = $this->getParameter('id_set');
		$this->database->deleteTest($id_test);
		$this->redirect('Stats:setdetail', $id_set);
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
		$this->redirect('Stats:mysets');
	}

	public function deleteSet()
	{
		$id_set = $this->getParameter('id_set');
		$this->database->deleteSet($id_set);
		$this->redirect('Stats:mysets');
	}	


    /**
     * Load values to second select
     * @param int
     */
    public function handleModelChange($value)
    {
        $models = $this->database->getModels($value)->fetchPairs('model', 'model');

        $this['searchPrinterTestsForm']['model']
            ->setItems($models);

        $this['searchPrinterTestsForm']->setDefaults(['printer' => $value]);

        $this->redrawControl('wrapper');
    }

	public function createComponentSearchPrinterTestsForm()
	{
		$form = new Form;
		$producer = $this->database->getProducers();
		//var_dump($producer);
		$form->addSelect('printer','Značka tlačiarne:', $producer)
			->setAttribute('class','w3-select w3-white w3-border');

		$form->addSelect('model', 'Model', []);
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
		
		if(is_null($prod))
		{
			$models =  $this->database->getModels(reset($producer));
			
		}
		
		$form->addSelect('printer','Značka tlačiarne:',$producer)
			->setAttribute('class','w3-select w3-white w3-border')
			->getControlPrototype()->OnChange('$(this).parent("form").submit()');
		$form->addSelect('model','Model tlačiarne:',$models->fetchPairs('model', 'model'))
			->setAttribute('class','w3-select w3-white w3-border');
		$form->addSubmit('search','Vyhľadať')
			->setAttribute('class','w3-button w3-grey');
		$form->setAction($this->link('search'));
		$form->setMethod('GET');
		
		return $form;
	}
	
	public function actionSearch($printer, $model,$page = 1,$order = 'sum',$lstOrd = 'x',$desc = 0)
	{
		if($this['searchModelForm']->isSubmitted() === $this['searchModelForm']['search'])
		{
			$this->session->getSection('search_filter')->tests = [];
			$this->filterSearchEnabled = false;
			$page = 1;
		}


		$this->template->printer = $printer;
		$this->template->model = $model;
		$this->template->order = $order;		
		if($order == $lstOrd)
		{
			if($desc)
				$desc = 0;
			else
				$desc = 1;
		}
		else if($lstOrd != 'x')
			$desc = 0;

		$this->template->desc = $desc;
		$order = $desc == 1 ?   $order.' DESC' : $order;
		
		$prod = $this->getParameter('printer');
		$this['searchModelForm']->setDefaults(array('printer' => $printer,
													'model' => $model));
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage($this->items); // the number of records on page
		$paginator->setPage($page);

		
		$filter = $this->template->filter = $this->session->getSection('search_filter')->tests;

		$tests = $this->database->findTests($prod,$model);
		
		if ($this->filterSearchEnabled) {
			$tests->where('test', $filter);
		}
		$tests->order($order)->limit($paginator->getLength(), $paginator->getOffset());

		$totalPosts = $this->database->findTestsCount($prod,$model,$this->filterSearchEnabled ? $filter : null);

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;

		$this->template->tests = $tests;		
		$this->template->cnt = array();
	}

	public function renderStats($page,$order = 'sum' ,$lstOrd = 'x',$desc = 0)
	{
		
		$this->page = $page;
		$this->template->order = $order;		
		if($order == $lstOrd)
		{
			if($desc)
				$desc = 0;
			else
				$desc = 1;
		}
		else if($lstOrd != 'x')
			$desc = 0;

		$this->template->desc = $desc;
		$order = $desc == 1 ?   $order.' DESC' : $order;
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemsPerPage($this->items); // the number of records on page
		$paginator->setPage($this->page);
		$this->template->itemsPP = $paginator->getItemsPerPage();
		$filter = $this->template->filter = $this->session->getSection('stats_filter')->tests;
		$tests = $this->database->findBestTests();

		if ($this->filterStatsEnabled) {
			$tests->where('test', $filter);
		}

		$tests->order($order)->limit($paginator->getLength(), $paginator->getOffset());
		
		$totalPosts = $this->database->bestTestCount($this->filterStatsEnabled ? $filter : null);

		$paginator->setItemCount( $totalPosts );
	
		$this->template->totalPosts = $totalPosts;
		$this->template->totalPages = $paginator->getPageCount();
		$this->template->page = $paginator->page;

		$this->template->tests = $tests;		
		$this->template->cnt = array();
	}
	public function createComponentTableForm()
	{
		$form = new Form;
		$form->addSubmit('select', 'Filter')
			->setAttribute('class',"w3-button w3-grey");
		$form->addSubmit('filter', 'Použiť')
		->setAttribute('class',"w3-button w3-grey");
		$form->addSubmit('clear', 'Zrušiť')
		->setAttribute('class',"w3-button w3-grey");
		$form->onSuccess[] = function($form) {
			if ($form->isSubmitted() === $form['clear']) {
				switch ($this->view) {
					case 'stats':
						$this->session->getSection('stats_filter')->tests = [];
						$this->filterStatsEnabled = false;
						break;
					case 'search':
						$this->session->getSection('search_filter')->tests = [];
						$this->filterSearchEnabled = false;
						break;
					case 'setdetail':
						$this->session->getSection('set_filter')->tests = [];
						$this->filterSetEnabled = false;
						break;
					default:
						# code...
						break;
				}			
				
				$this->redirect('this');
				return;
			}

			$data = $form->getHttpData();
			$selected = isset($data['chcktest']) ? $data['chcktest'] : [];
			
			switch ($this->view) {
					case 'stats':
						$this->session->getSection('stats_filter')->tests = array_merge($selected, $this->session->getSection('stats_filter')->tests);
						break;
					case 'search':
						$this->template->filter = $this->session->getSection('search_filter')->tests = array_merge($selected, $this->session->getSection('search_filter')->tests);			
						break;
					case 'setdetail':

						$this->session->getSection('set_filter')->tests = array_merge($selected, $this->session->getSection('set_filter')->tests);
						break;
					default:
						# code...
						break;
				}	
			if ($form->isSubmitted() === $form['filter']) {
				$this->page = 1;
				switch ($this->view) {
					case 'stats':
						$this->filterStatsEnabled = true;
						$this->redirect('this',1);
						break;
					case 'search':
						$this->filterSearchEnabled = true;
						$this->redirect('this',$this->getParameter('printer'),$this->getParameter('model'),1);
						break;
					case 'setdetail':
						$this->filterSetEnabled = true;
						$this->redirect('this',$this->getParameter('id_set'), 1);
						break;
					default:
						# code...
						break;
					
				}
			}
			
		};
		return $form;
	}	
}
