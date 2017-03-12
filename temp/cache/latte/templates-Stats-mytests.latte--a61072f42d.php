<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/mytests.latte

use Latte\Runtime as LR;

class Templatea61072f42d extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
