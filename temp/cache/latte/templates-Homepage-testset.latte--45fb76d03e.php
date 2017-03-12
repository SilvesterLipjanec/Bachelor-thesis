<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/testset.latte

use Latte\Runtime as LR;

class Template45fb76d03e extends Latte\Runtime\Template
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
	<h2>Zadajte potrebné informácie</h2>
	
</div>
<?php
		/* line 8 */ $_tmp = $this->global->uiControl->getComponent("setForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>


<progress max="100" value="0"></progress>





<?php
	}

}
