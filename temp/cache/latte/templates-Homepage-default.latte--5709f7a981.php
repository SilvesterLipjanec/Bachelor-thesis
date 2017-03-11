<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template5709f7a981 extends Latte\Runtime\Template
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


<?php
		/* line 6 */ $_tmp = $this->global->uiControl->getComponent("downloadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
<progress max="100" value="80"></progress>
<?php
		/* line 8 */ $_tmp = $this->global->uiControl->getComponent("uploadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>




<?php
	}

}
