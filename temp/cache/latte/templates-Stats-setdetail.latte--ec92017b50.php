<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/setdetail.latte

use Latte\Runtime as LR;

class Templateec92017b50 extends Latte\Runtime\Template
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
		if (isset($this->params['test'])) trigger_error('Variable $test overwritten in foreach on line 21');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<h2>Testovacia sada - Detail</h2>

<?php
		/* line 6 */ $_tmp = $this->global->uiControl->getComponent("setDetailForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>

<table>
<tr>
	<th>Dátum testu</th>
	<th>Šírka vzoru</th>
	<th>Červená</th>
	<th>Zelená</th>
	<th>Modrá</th>
	<th>Rozlíšenie tlačiarne</th>
	<th>Rozlíšenie skenera</th>
	<th>Poznámka</th>
	<th>&nbsp;</th>

	
<?php
		$iterations = 0;
		foreach ($tests as $test) {
?>
			<tr>
			    <td><?php echo LR\Filters::escapeHtmlText($test->date_time->format('d-m-Y H:m:s')) /* line 23 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->width) /* line 24 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->red) /* line 25 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->green) /* line 26 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->blue) /* line 27 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->printer_res) /* line 28 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->scanner_res) /* line 29 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($test->test_note) /* line 30 */ ?></td>	
			    <td>
					<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:deletetest", [$test->id_test])) ?>">Vymazať</a>
				</td>		    
			</tr>
<?php
			$iterations++;
		}
		/* line 36 */ $_tmp = $this->global->uiControl->getComponent("backForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
</tr>


</table>




<?php
	}

}
