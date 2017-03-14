<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
//use Nette\Forms\Controls\Button;

class HomepagePresenter extends BasePresenter
{

	const UPLOAD_DIR = './data/';
	const ANALYZE_DIR = './analyze/';
	private $database;
	
	public function __construct(Model\DatabaseRepository $database)
	{
		$this->database = $database;
	}
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
	protected function createComponentUploadForm()
	{ 
	  
	  $form = new Form;
	  $form->addUpload("file","Vybrať súbor:");
	  $form->addSubmit("save", "Uložit");
	  $form->onSuccess[] = array($this,'saveUploadClicked');
	  return $form;
	}
	protected function createComponentDownloadForm()
	{
		$pattern_width = [
		    '2' => '2 mm',
		    '3' => '3 mm',
		    '4' => '4 mm'
		];
		$form = new Form;		
		$form->addRadioList('width', 'Šírka vzoru:', $pattern_width)
			->setDefaultValue('2');
		$form->addSubmit("download","Stiahnúť");
		
		$form->addSubmit("next","Pokračovať")
			->onClick[] = array($this, 'goUpload');
		$form->onSuccess[] = array($this, 'downloadClicked');
		return $form;
	}
	public function downloadClicked(Form $form)
	{	
		switch ($form->values['width'])
		{
			
			case 2:
				 $this->sendResponse(new Nette\Application\Responses\FileResponse('./ref_data/RGB2.pdf'));	
				break;
			case 3:
				$this->sendResponse(new Nette\Application\Responses\FileResponse('./ref_data/RGB3.pdf'));
				break;
			case 4:
				$this->sendResponse(new Nette\Application\Responses\FileResponse('./ref_data/RGB4.pdf'));
				break;
		}		
					
	}

	public function saveUploadClicked(Form $form)
	{
	
	  $values = $form->values;
	  $file = $values['file'];
	 
	  // kontrola jestli se jedná o obrázek a jestli se nahrál dobře
	  if($file->isOk()) {

	    // oddělení přípony pro účel změnit název souboru na co chceš se zachováním přípony
	    $file_ext=strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
	    // vygenerování náhodného řetězce znaků, můžeš použít i \Nette\Strings::random()	    
	    if($file_ext == '.jpg' || $file_ext == '.pdf' || $file_ext == '.tiff')
	    {

	    	$file_id = uniqid(rand(0,20), TRUE);
	    	
	    	$file_name = $file_id.$file_ext;
		    // přesunutí souboru z temp složky někam, kam nahráváš soubory
		    $file_pos = self::UPLOAD_DIR . $file_name;
		    $file->move($file_pos);
		    $file_ppm = $file_id . '.ppm';
		    $file_ppm_pos = self::UPLOAD_DIR . $file_id . '.ppm';
		    $file_alg_pos = self::UPLOAD_DIR . 'alg_' . $file_ppm;
		    $file_txt_pos = self::UPLOAD_DIR . $file_id . '.txt';
		   
		    if($file_ext == '.jpg')		    {
		    	
		    	exec('jpegtopnm '.$file_pos.' > '.$file_ppm_pos);
		    }
		    elseif ($file_ext == '.pdf')
		    {
		    	exec('pdftoppm '.$file_pos.' > '.$file_ppm_pos);
		    }
		    elseif ($file_ext == '.tiff')
		    {
		   		exec('tifftopnm '.$file_pos.' > '.$file_ppm_pos);
		    }

		    //cesta k datam relativna od priecinka v ktorom je umiestneny analyzator
		    exec(self::ANALYZE_DIR.'analyze '.$file_ppm_pos);
		    exec('rm '.$file_ppm_pos);
		    exec('rm '.$file_pos);
		    if(file_exists($file_alg_pos))
		    {
		    	exec('rm '.$file_alg_pos);
		    }
		   if($this->getUser()->isLoggedIn())
		    { 	
				$id_user = $this->getUser()->getIdentity()->id_user;
				if(count($this->database->findTestSetForUser($id_user)) != 0)
		    		$this->redirect("Homepage:testset", $this->getParameter('width'),$file_txt_pos);
		    	else
		    		$this->redirect("Homepage:newtestset",$this->getParameter('width'),$file_txt_pos);
		    }
		    else
		    {
		    	$this->redirect("Homepage:newtestset",$this->getParameter('width'),$file_txt_pos);
		    }
		}
	    else //nepodporovany format suboru
	    {
	    	$this->flashMessage('Nepodporovaný formát súboru '.$file_ext.'. Podporované formáty: .pdf, .jpg, .tiff.');
	    }   

	  }
	  else
	  {
	  	$this->flashMessage('Súbor sa nepodarilo nahrať');
	  }
	}
	public function renderTestset($width,$file)
	{
		$this->template->width = $width;
		$this->template->file = $file;
	}
	public function createComponentNextForm()
	{	
		

		$form = new Form;
		if($this->getView() == 'default')
		{
			$form->addSubmit("gotest","Otestovať tlačiareň");
		}
		else
		{
			$form->addSubmit("next","Pokračovať");
		}

		$form->onSuccess[] = array($this, 'goNext');
	  	return $form;
	}
	public function goUpload(SubmitButton $button)
	{
		
		$this->redirect('Homepage:upload',$button->getParent()->values['width']);
	}
	public function renderUpload($width)
	{
		$this->template->width = $width;
	}
	public function goNext()
	{
		switch ($this->getView()) {
			case 'default':
				$this->redirect('Homepage:download');		
				break;
			case 'download':				
				$this->redirect('Homepage:upload');
				break;
			
		}
	}
	public function createNewTestSet()
	{	
	 	$this->redirect('Homepage:newtestset',$this->getParameter('width'),$this->getParameter('file'));
	}
	public function renderNewtestset($width,$file)
	{
		$this->template->width = $width;
		$this->template->file = $file;
	}
	public function createComponentNewSetForm()
	{
		$form = new Form;
		$form->addText('producer','Výrobca tlačiarne:')
			->SetRequired('Prosím zadajte výrobcu tlačiarne.');
		$form->addText('model','Model tlačiarne:')
			->SetRequired('Prosím zadajte model tlačiarne.');
		$typ_tlaciarne = array(
			'laserová' =>	'laserová',
			'atramentová' => 'atramentová',
			'iná' => 'iná'
		);		
		
		$form->addSelect('type', 'Typ tlačiarne:', $typ_tlaciarne);
		$form->addText('printer_res','Rozlíšenie tlačiarne:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER)
			->addRule(Form::MIN,'Rozlíšenie tlačiarne musí byť kladné číslo.');
		$form->addText('scanner_res','Rozlíšenie skenera:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER)
			->addRule(Form::MIN,'Rozlíšenie skenera musí byť kladné číslo.');

		if($this->getUser()->isLoggedIn())
		{
			$form->addTextArea('set_note', 'Poznámka k sade:')
				->SetRequired(FALSE)
			    ->addRule(Form::MAX_LENGTH, 'Vaša poznámka je príliš dlhá', 400);
			$form->addTextArea('test_note', 'Poznámka k testu:')
				->SetRequired(FALSE)
			    ->addRule(Form::MAX_LENGTH, 'Vaša poznámka je príliš dlhá', 400);
		}
		else
		{
			$form->addTextArea('set_note', 'Poznámka:')
				->SetRequired(FALSE)
			    ->addRule(Form::MAX_LENGTH, 'Vaša poznámka je príliš dlhá.', 400);
		}
		
		$form->addSubmit('save','Uložiť a pokračovať');
		$form->onSuccess[] = array($this,'infoToNewTestSet' );
			
		return $form;		
	}
	public function createComponentSetForm()
	{
		$id_user = $this->getUser()->getIdentity()->id_user;
		$form = new Form;
		$form->addSelect('id_set','Zaradiť do testovacej sady:',$this->database->findTestSetForUser($id_user));
		
		$form->addTextArea('test_note', 'Poznámka k testu:')
			->SetRequired(FALSE)
		    ->addRule(Form::MAX_LENGTH, 'Vaša poznámka je príliš dlhá.', 4000);
		$form->addText('printer_res','Rozlíšenie tlačiarne:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER)
			->addRule(Form::MIN,'Rozlíšenie tlačiarne musí byť kladné číslo.',0)
			->addRule(Form::MAX,'Rozlíšenie tlačiarne je príliš veľké.',32767);
		$form->addText('scanner_res','Rozlíšenie skenera:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER)
			->addRule(Form::MIN,'Rozlíšenie skenera musí byť kladné číslo.',0)
			->addRule(Form::MAX,'Rozlíšenie skenera je príliš veľké.',32767);
		$form->addSubmit('use_set','Vybrať sadu a uložiť')
			->onClick[] = array($this,'infoToExistTestSet' );
		$form->addSubmit('new_set', 'Vytvoriť novú testovaciu sadu')
    		 ->onClick[] = array($this,'createNewTestSet');    	
    	
		return $form;
	}
	
	public function infoToExistTestSet($button)
	{
		$values = $button->getForm()->getValues();
		
		$this->database->insertResultForExistSet($values,$this->getParameter('file'),$this->getParameter('width'));
		$this->redirect('Homepage:result');
	
	}
	public function infoToNewTestSet($button)
	{	
		$values = $button->getForm()->getValues();

		if($this->getUser()->isLoggedIn())
		{
			$id_user = $this->getUser()->getIdentity()->id_user;
		}
		else $id_user = -99;
		$id_test = $this->database->insertResultForNewSet($values,$this->getParameter('file'),$this->getParameter('width'),$id_user);
		
		$this->redirect('Homepage:result',$id_test,$this->getParameter('width'));
	
	}
	public function renderResult($id,$width)
	{
		$this->template->test = $this->database->getTestResult($id);
		$this->template->ref = $this->database->getReference($width);
	}

}
