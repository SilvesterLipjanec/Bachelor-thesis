<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
//use Nette\Forms\Controls\Button;

class HomepagePresenter extends BasePresenter
{

	const UPLOAD_DIR = __DIR__.'/../../data/';
	const ANALYZE_DIR = __DIR__.'/../../analyze/';
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
				 $this->sendResponse(new Nette\Application\Responses\FileResponse('../data/RGB2.pdf'));
				 break;
			case 3:
				$this->sendResponse(new Nette\Application\Responses\FileResponse('../data/RGB3.pdf'));
				break;
			case 4:
				$this->sendResponse(new Nette\Application\Responses\FileResponse('../data/RGB4.pdf'));
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
	    	echo($file_id);
	    	$file_name = $file_id.$file_ext;
		    // přesunutí souboru z temp složky někam, kam nahráváš soubory
		    $file->move(self::UPLOAD_DIR . $file_name);
		    $file_pos = self::UPLOAD_DIR . $file_name;
		    $file_ppm = $file_id . '.ppm';
		    $file_ppm_pos = self::ANALYZE_DIR . $file_id . '.ppm';
		    
		    if($file_ext == '.jpg')
		    {
		    	//echo('')
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
		    exec(self::ANALYZE_DIR.'analyze '.$file_ppm);
		    //exec('rm '.$file_ppm);
		    exec('rm '.$file_pos);	    
		}
	    else //nepodporovany format suboru
	    {
	    	$this->flashMessage('Nepodporovaný formát súboru '.$file_ext.'. Podporované formáty: .pdf, .jpg, .tiff.');
	    }   

	  }
	}

}
