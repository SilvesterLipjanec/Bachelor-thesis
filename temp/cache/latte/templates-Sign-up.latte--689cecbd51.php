<?php
// source: /var/www/html/BP/app/presenters/templates/Sign/up.latte

use Latte\Runtime as LR;

class Template689cecbd51 extends Latte\Runtime\Template
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

<div class="w3-container" style="padding: 150px 16px 200px 16px">
	<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32" style="padding-left:64px">Registrácia</h2>

	<div style="padding-left:64px"><?php
		/* line 6 */ $_tmp = $this->global->uiControl->getComponent("signUpForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?></div>

	<h4 class="w3-border-top w3-padding-16" style="padding-left:64px">Už ste registrovaný?</h4>
	<a class="w3-button w3-grey" style="margin-left:64px" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Prihlásiť sa</a></button> 
</div>

<?php
	}

}
