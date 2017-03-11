<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/info.latte

use Latte\Runtime as LR;

class Template3012ad532d extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>

<h2>3. krok</h2>
<h3>Zadajte informácie o tlačiarni</h3>

	
<?php
		/* line 7 */ $_tmp = $this->global->uiControl->getComponent("newSetForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
<progress max="100" value="66"></progress>




<?php
	}

}
