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
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
?>













<?php
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
			<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32 w3-animate-left padding-left-64">Uloženie skenu</h2>

			<ul class="w3-border-bottom w3-ul" style="margin-left: 64px;">
				<li>1. Oskenujte vytlačený farebný vzor</li>
				<li>2. Vyberte naskenovaný farebný vzor a sem uložte</li>
			</ul>	
		</div>
		<div class="w3-center" >
			<h5>Vybrať súbor</h5>
			<div style="margin:auto; display:inline-block;">
<?php
		/* line 16 */ $_tmp = $this->global->uiControl->getComponent("uploadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
			</div>
		</div>
		<div class="w3-container" style="margin-top:60px"></div>

	</div>
		<div class="w3-white w3-border w3-margin-left w3-margin-right">
		  <div id="myBar" class="w3-green" style="height:15px;width:33%"></div>
		</div>
</div>
<?php
	}

}
