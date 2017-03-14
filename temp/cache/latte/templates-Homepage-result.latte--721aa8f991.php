<?php
// source: /var/www/html/BP/app/presenters/templates/Homepage/result.latte

use Latte\Runtime as LR;

class Template721aa8f991 extends Latte\Runtime\Template
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

<h2>Výsledok</h2>
<p><?php echo LR\Filters::escapeHtmlText($test->red) /* line 4 */ ?></p>
<p><?php echo LR\Filters::escapeHtmlText($test->green) /* line 5 */ ?></p>
<p><?php echo LR\Filters::escapeHtmlText($test->blue) /* line 6 */ ?></p>

<progress max="100" value="100"></progress>




<?php
	}

}
