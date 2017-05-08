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
<div style="min-height: 830px">
	<div style="min-height: 790px">

		<div class="w3-container" style="padding: 150px 16px 50px 16px">
			<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32 w3-animate-left padding-left-64">Výber farebného vzoru</h2>

			<ul class="w3-border-bottom w3-ul" style="margin-left: 64px;">
				<li>1. Vyberte požadovanú šírku farebného vzoru</li>
				<li>2. Stiahnite farebný vzor</li>
				<li>3. Stiahnutý farebný vzor vytlačte</li> 
			</ul>	
		</div>
		<div class="w3-center" >
			<h5>Šírka vzoru</h5>
			<div style="margin:auto; display:inline-block;"><?php
		/* line 18 */ $_tmp = $this->global->uiControl->getComponent("downloadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?></div>
		</div>
	</div>

	<div class="w3-white w3-border w3-margin-left w3-margin-right">
		<div id="myBar" class="w3-green" style="height:15px;width:0%"></div>
	</div>
</div>









<?php
	}

}
