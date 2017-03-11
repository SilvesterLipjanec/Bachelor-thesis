<?php
// source: /var/www/html/BP/app/presenters/templates/Sign/up.latte

use Latte\Runtime as LR;

class Template689cecbd51 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'subtitle' => 'blockSubtitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'subtitle' => 'html',
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
		$this->renderBlock('subtitle', get_defined_vars());
?>

<?php
		/* line 4 */ $_tmp = $this->global->uiControl->getComponent("signUpForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>

<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Už ste registrovaný? Prihláste sa.</a></p>
<?php
	}


	function blockSubtitle($_args)
	{
		extract($_args);
?><h2>Registrácia</h2>
<?php
	}

}
