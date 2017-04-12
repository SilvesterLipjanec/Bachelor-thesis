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
<div style="min-height: 810px">
	<div class="w3-container w3-center" style="padding: 300px 16px 300px 16px">
	 	  <h1 class="w3-margin w3-jumbo w3-animate-top">Otestujte svoju tlačiareň</h1>
		  <p class="w3-large" >Metóda Print & Scan umožnuje jednoducho a rýchlo otestovať kvalitu vašej tlačiarne iba v troch krokoch</p>
		  <div style="margin:auto; display:inline-block"><?php
		/* line 8 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?></div>
		
	</div>
</div>
<?php
	}

}
