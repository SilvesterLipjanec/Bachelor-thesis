<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/newtestset.latte

use Latte\Runtime as LR;

class Templatec291d302b9 extends Latte\Runtime\Template
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
?>


<?php
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
<div>
	<h2>Nová testovacia sada</h2>
	
</div>
<?php
		/* line 8 */ $_tmp = $this->global->uiControl->getComponent("newSetForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>


<progress max="100" value="0"></progress>





<?php
	}

}
