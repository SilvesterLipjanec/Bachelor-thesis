<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/help.latte

use Latte\Runtime as LR;

class Template212a2b34c2 extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
		?>som na helpe<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
