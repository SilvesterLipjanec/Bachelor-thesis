<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/stats.latte

use Latte\Runtime as LR;

class Templatee175889135 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'_wrapper' => 'blockWrapper',
		'_secondSnippet' => 'blockSecondSnippet',
		'_thirdSnippet' => 'blockThirdSnippet',
		'js' => 'blockJs',
		'jsCallback' => 'blockJsCallback',
	];

	public $blockTypes = [
		'content' => 'html',
		'_wrapper' => 'html',
		'_secondSnippet' => 'html',
		'_thirdSnippet' => 'html',
		'js' => 'html',
		'jsCallback' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
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

<?php
		$this->renderBlock('_wrapper', $this->params);
?>

<?php
	}


	function blockWrapper($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("wrapper", "area");
		?>    <?php
		/* line 4 */
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["selectForm"], []);
?>

        <?php if ($_label = end($this->global->formsStack)["first"]->getLabel()) echo $_label ?> <?php echo end($this->global->formsStack)["first"]->getControl() /* line 5 */ ?>

<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('secondSnippet')) ?>"><?php
		$this->renderBlock('_secondSnippet', $this->params) ?></div>        <?php echo end($this->global->formsStack)["send"]->getControl() /* line 15 */ ?>

    <?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

<?php
		$this->global->snippetDriver->leave();
		
	}


	function blockSecondSnippet($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("secondSnippet", "static");
		?>            <?php if ($_label = end($this->global->formsStack)["second"]->getLabel()) echo $_label ?> <?php
		echo end($this->global->formsStack)["second"]->getControl() /* line 7 */ ?>


<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('thirdSnippet')) ?>"><?php $this->renderBlock('_thirdSnippet', $this->params) ?></div>
<?php
		$this->renderBlock('js', $this->params, 'html');
		$this->global->snippetDriver->leave();
		
	}


	function blockThirdSnippet($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("thirdSnippet", "static");
		?>                <?php if ($_label = end($this->global->formsStack)["third"]->getLabel()) echo $_label ?> <?php
		echo end($this->global->formsStack)["third"]->getControl() /* line 10 */ ?>

<?php
		$this->global->snippetDriver->leave();
		
	}


	function blockJs($_args)
	{
		extract($_args);
?>

<?php
		$this->renderBlock('jsCallback', ['input' => 'first', 'link' => 'firstChange'] + $this->params, 'html');
		$this->renderBlock('jsCallback', ['input' => 'second', 'link' => 'secondChange'] + $this->params, 'html');
?>

<?php
	}


	function blockJsCallback($_args)
	{
		extract($_args);
?>
<script>

$('#' + <?php echo LR\Filters::escapeJs($control["selectForm"][$input]->htmlId) /* line 33 */ ?>).off('change').on('change', function () {
    $.nette.ajax({
        type: 'GET',
        url: <?php echo LR\Filters::escapeJs($this->global->uiControl->link("{$link}!")) ?>,
        data: {
            'value': $(this).val(),
        }
    });
});

</script>
<?php
	}

}
