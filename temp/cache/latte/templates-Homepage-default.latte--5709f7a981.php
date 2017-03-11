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
<div id=introduction>
	<h2>Otestujte svoju tlačiareň</h2>
	<p>Metóda Print & Scan umožnuje jednoducho a rýchlo otestovať kvalitu vašej tlačiarne iba v troch krokoch</p>
	<ol class = "menu">
			<li>Stiahnite si farebný vzor </li>
			<li>Vytlačte a následne naskenujte farebný vzor</li>
			<li>Uložte výsledný sken na stránku a zistite výsledok</li>
	</ol>
</div>
<?php
		/* line 13 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>




<?php
	}

}
