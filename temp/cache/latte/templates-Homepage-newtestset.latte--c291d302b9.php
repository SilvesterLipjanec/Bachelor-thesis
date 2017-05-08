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
		<div class="w3-container" style="padding: 150px 16px 0px 16px">
			<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32 w3-animate-left padding-left-64">Zadajte potrebné informácie</h2>
		</div>
			<p class="padding-left-64">* Označené povinné polia</p>
			<div class="margin-left-64">
<?php
		/* line 11 */ $_tmp = $this->global->uiControl->getComponent("newSetForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
			</div>
		</div>
	<div class="w3-panel">
		<div class="w3-white w3-border ">
		  <div id="myBar" class="w3-green" style="height:15px;width:66%"></div>
		</div>
	</div>
</div>
<?php
	}

}
