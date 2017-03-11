<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
//use Nette\Forms\Controls\Button;

class StatsPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
	
}
