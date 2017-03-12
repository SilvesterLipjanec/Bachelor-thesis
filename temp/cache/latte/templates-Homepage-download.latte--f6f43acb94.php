<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/download.latte

use Latte\Runtime as LR;

class Templatef6f43acb94 extends Latte\Runtime\Template
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
<div id=download>
	<h2>Výber farebného vzoru</h2>
	<ul>
		<li>Vyberte požadovanú šírku farebného vzoru</li>
		<li>Stiahnite farebný vzor</li>
		<li>Stiahnutý farebný vzor vytlačte</li> 
	</ul>
</div>

<?php
		/* line 13 */ $_tmp = $this->global->uiControl->getComponent("downloadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>

<progress max="100" value="0"></progress>





<?php
	}

}
