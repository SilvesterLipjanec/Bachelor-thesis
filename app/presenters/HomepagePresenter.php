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

}
