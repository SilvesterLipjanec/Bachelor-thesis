<?php
// source: /var/www/html/BP/app/presenters/templates/Sign/out.latte

use Latte\Runtime as LR;

class Template530b28be14 extends Latte\Runtime\Template
{
	public $blocks = [
		'subtitle' => 'blockSubtitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'subtitle' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
?>

<div class="w3-container" style="padding: 150px 16px">
<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('subtitle', get_defined_vars());
?>

<p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Prihlásiť sa pomocou iného účtu.</a></p>
</div>


<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockSubtitle($_args)
	{
		extract($_args);
?><h2>Boli ste odhlásený</h2>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div style="min-height: 830px">
	<div class="w3-container w3-center" style="padding: 150px 16px 100px 16px">
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-32">Boli ste odhlásený</h2>
		<h4 class="w3-padding-16">Prihlásiť sa pomocou iného účtu</h4>
		<div><a class="w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("in")) ?>">Prihlásiť sa</a></div> 
	</div>
</div>

<?php
	}

}
