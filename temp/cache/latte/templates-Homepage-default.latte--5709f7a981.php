<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template5709f7a981 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'title' => 'blockTitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'title' => 'html',
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
<div id="banner">
<?php
		$this->renderBlock('title', get_defined_vars());
?>
</div>

<div id="content">
	<ul class = "menu">
		<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Home</a></li>
		<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:help")) ?>">Naávod</a></li>

	</ul>
</div>
<?php
		/* line 15 */ $_tmp = $this->global->uiControl->getComponent("downloadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
		/* line 16 */ $_tmp = $this->global->uiControl->getComponent("uploadForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>




<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>	<h1>Serus Imoo!</h1>
<?php
	}

}
