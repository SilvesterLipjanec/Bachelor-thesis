<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
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
	  $form->onSuccess[] = array($this, 'saveUploadClicked');
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
		$this->redirect('Homepage:upload');	
		
			
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
		    $this->insertResult($file_txt_pos);
		    if($this->getUser()->isLoggedIn())
		    	$this->redirect("Homepage:testset");
		    else
		    	$this->redirect("Homepage:info");
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
	public function insertResult($file)
	{
		$myfile = fopen($file,'rb');
		if(!$myfile)
		{
			$this->flashMessage('Vyskytla sa chyba pri otváraní súboru!');
			exit(1);
		}
		fscanf($myfile , "%f %f %f",$r,$g,$b);
		

		fclose($myfile);

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
	public function goNext()
	{
		switch ($this->getView()) {
			case 'default':
				$this->redirect('Homepage:download');		
				break;
			case 'download':
				$this->redirect('Homepage:upload');
				break;
			case 'upload':
				$this->redirect('Homepage:info');
				break;
		}
	}
	public function createNewTestSet()
	{	
		//echo('tu som');
	 	$this->redirect('Homepage:newtestset');
	}
	public function createComponentNewSetForm()
	{
		$form = new Form;
		$form->addText('producer','Výrobca:')
			->SetRequired('Prosím zadajte výrobcu tlačiarne.');
		$form->addText('model','Model:')
			->SetRequired('Prosím zadajte model tlačiarne.');
		$typ_tlaciarne = array(
			'laser' =>	'laserová',
			'ink' => 'atramentová',
			'oth' => 'iná'
		);
		//var_dump($this->database->findTestSetForUser($id_user)->id_set);
		
		$form->addSelect('typ', 'Typ tlačiarne:', $typ_tlaciarne);
		$form->addText('print_res','Rozlíšenie tlačiarne:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER);
		$form->addText('scan_res','Rozlíšenie skenera:')
			->SetRequired(FALSE)
			->addRule(Form::INTEGER);
		$form->addTextArea('note', 'Poznámka:')
			->SetRequired(FALSE)
		    ->addRule(Form::MAX_LENGTH, 'Your note is way too long', 4000);

		$form->addSubmit('save','Uložiť a pokračovať');
		$form->onSuccess[] = array($this,'infoFormSuccedeed' );
			
		return $form;		
	}
	public function createComponentSetForm()
	{
		$id_user = $this->getUser()->getIdentity()->id_user;
		$form = new Form;
		$form->addSelect('test_set','Zaradiť do testovacej sady:',$this->database->findTestSetForUser($id_user));
		$form->addSubmit('use_set','Vybrať sadu')
			->onClick[] = array($this,'infoFormSuccedeed' );
		$form->addSubmit('new_set', 'Vytvoriť novú testovaciu sadu')
    		 ->onClick[] = array($this,'createNewTestSet');
    	
    	
		return $form;
	}
	
	public function infoFormSuccedeed()
	{
		$this->redirect('Homepage:result');
	}

}
