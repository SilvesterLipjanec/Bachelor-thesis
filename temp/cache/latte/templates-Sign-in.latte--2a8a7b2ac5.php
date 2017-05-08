<?php
// source: /var/www/html/BP/app/presenters/templates/Sign/in.latte

use Latte\Runtime as LR;

class Template2a8a7b2ac5 extends Latte\Runtime\Template
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
<div style="min-height: 830px">
	<div class="w3-container w3-center" style="padding: 150px 16px 100px 16px">
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32">Prihlásenie</h2>

		<div style="margin:auto; display:inline-block"><?php
		/* line 6 */ $_tmp = $this->global->uiControl->getComponent("signInForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?></div>

		<h4 class="w3-border-top w3-padding-16">Ešte nie ste registrovaný?</h4>
		<div><a class="w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("up")) ?>">Registrovať sa</a></div> 
	</div>
</div>

<?php
	}

}
