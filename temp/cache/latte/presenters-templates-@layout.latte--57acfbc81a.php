<?php
// source: /var/www/html/BP/app/presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template57acfbc81a extends Latte\Runtime\Template
{
	public $blocks = [
		'head' => 'blockHead',
		'scripts' => 'blockScripts',
		'footer' => 'blockFooter',
	];

	public $blockTypes = [
		'head' => 'html',
		'scripts' => 'html',
		'footer' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Print&Scan</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 11 */ ?>/css/style.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<body class="w3-light-grey">

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
			echo LR\Filters::escapeHtmlText($flash->message) /* line 50 */ ?></div>
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

<?php
		$this->renderBlock('footer', get_defined_vars());
?>

</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 50');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockHead($_args)
	{
		extract($_args);
?>
	<div id="banner">
	</div>
	
	<div class="w3-top w3-white">
		<div class="w3-center w3-padding-16">
			<a class="w3-text-red w3-xxlarge">Print</a>
			<a class="w3-text-green w3-xlarge">&</a>
			<a class="w3-text-blue w3-xxlarge">Scan</a>
		</div> 
		<div class="w3-bar w3-deep-purple w3-card-2 w3-left-align">
			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Návod</a>
			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:download")) ?>">Otestovať tlačiareň</a>
			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats")) ?>">Štatistiky</a>
			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:find")) ?>">Vyhľadať tlačiareň</a>
<?php
		if ($user->isLoggedIn()) {
			?>				<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mytests")) ?>">Moje testy</a>
				<a class="w3-bar-item w3-button w3-right" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:out")) ?>">Odhlásiť sa</a>
<?php
		}
		else {
			?>				<a class="w3-bar-item w3-button w3-right"  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Sign:in")) ?>">Prihlásiť sa</a>					
<?php
		}
?>

		</div>	
	</div>
<?php
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
	<script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 59 */ ?>/js/main.js"></script>
	
<?php
	}


	function blockFooter($_args)
	{
?>  <footer class="w3-container w3-deep-purple w3-center" style="width:100%;padding-top:16px; padding-bottom:40px">
	  <p>Vysoké učení technické v Brne</p>
	  <p>Autor: Silvester Lipjanec, 2017</p>
	  <a class="fa fa-facebook-official w3-hover-opacity" href="https://www.facebook.com/silvesterl"></a>
	 
  </footer>
<?php
	}

}
