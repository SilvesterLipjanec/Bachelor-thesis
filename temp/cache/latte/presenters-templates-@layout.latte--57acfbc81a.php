<?php
// source: /var/www/html/BP/app/presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template57acfbc81a extends Latte\Runtime\Template
{
	public $blocks = [
		'head' => 'blockHead',
		'title' => 'blockTitle',
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'head' => 'html',
		'title' => 'html',
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> | <?php
		}
?>Nette Sandbox</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/css/style.css">
<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('head', get_defined_vars());
?>
	
</head>

<body>
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>	<div<?php if ($_tmp = array_filter(['flash', $flash->type])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>><?php
			echo LR\Filters::escapeHtmlText($flash->message) /* line 37 */ ?></div>
<?php
			$iterations++;
		}
?>
	

	
<?php
		$this->renderBlock('content', $this->params, 'html');
?>

<?php
		$this->renderBlock('scripts', get_defined_vars());
?>
</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 37');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockHead($_args)
	{
		extract($_args);
?>
	<div id="banner">
<?php
		$this->renderBlock('title', get_defined_vars());
?>
	</div>
	<div id="head">
		<ul class = "menu">
			<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Návod</a></li>
			<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:download")) ?>">Otestovať tlačiareň</a></li>
			<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats")) ?>">Štatistiky</a></li>
			<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mytests")) ?>">Moje testy</a></li>
<?php
		if ($user->isLoggedIn()) {
			?>				<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:out")) ?>">Odhlásiť sa</a></li>
<?php
		}
		else {
			?>				<li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:in")) ?>">Prihlásiť sa</a></li>						
<?php
		}
?>

		</ul>	
	</div>
<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>	<h1>Print & Scan</h1>
<?php
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
	<script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 46 */ ?>/js/main.js"></script>
<?php
	}

}
