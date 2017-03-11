<?php
// source: /var/www/html/BP/app/presenters/templates/Sign/out.latte

use Latte\Runtime as LR;

class Template530b28be14 extends Latte\Runtime\Template
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

<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Prihlásiť sa pomocou iného účtu.</a></p>
<?php
	}


	function blockSubtitle($_args)
	{
		extract($_args);
?><h2>Boli ste odhlásený</h2>
<?php
	}

}
