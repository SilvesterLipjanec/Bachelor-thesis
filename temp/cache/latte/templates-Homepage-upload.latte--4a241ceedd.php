<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/upload.latte

use Latte\Runtime as LR;

class Template4a241ceedd extends Latte\Runtime\Template
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
<div id=upload>
	<h2>2. krok</h2>
	<ul>
		<li>Oskenujte vytlačený farebný vzor</li>
		<li>Vyberte naskenovaný farebný vzor a sem uložte</li>
	</ul> 
</div>

<?php
		/* line 10 */ $_tmp = $this->global->uiControl->getComponent("uploadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
<progress max="100" value="33"></progress>





<?php
	}

}
