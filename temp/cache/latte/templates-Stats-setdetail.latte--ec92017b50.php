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
?>


<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['test'])) trigger_error('Variable $test overwritten in foreach on line 76');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
	<div style="min-height: 830px">
	<div class="w3-container" style="padding: 126px 16px 50px 16px">
	<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 w3-animate-left padding-left-64">Testovacia sada - Detail</h2>
	<div class="w3-panel" style="margin-left:48px">
	<div class="w3-third">
	<?php
		/* line 9 */
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["setDetailForm"], []);
?>

		<div >
			<?php if ($_label = end($this->global->formsStack)["producer"]->getLabel()) echo $_label ?>

			<?php echo end($this->global->formsStack)["producer"]->getControl() /* line 12 */ ?>

		</div>
		<div class="w3-margin-top">
			<?php if ($_label = end($this->global->formsStack)["model"]->getLabel()) echo $_label ?>

			<?php echo end($this->global->formsStack)["model"]->getControl() /* line 16 */ ?>

		</div>
		<div class="w3-margin-top">
			<?php if ($_label = end($this->global->formsStack)["type"]->getLabel()) echo $_label ?>

			<?php echo end($this->global->formsStack)["type"]->getControl() /* line 20 */ ?>

		</div>
		<div class="w3-margin-top">
			<?php if ($_label = end($this->global->formsStack)["set_note"]->getLabel()) echo $_label ?>

			<?php echo end($this->global->formsStack)["set_note"]->getControl() /* line 24 */ ?>

		</div>	
	<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

	</div>
	</div>
<?php
		if ($totalPages == 0) {
?>
		<p>Zatiaľ neexistujú žiadne testy. Otestovať svoju tlačiareň môžete kliknutím na tlačidlo.</p>
<?php
			/* line 31 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
			$_tmp->render();
		}
		else {
?>
		<h3 class="w3-border-top" style="padding-left:32px; padding-top:16px">Zoznam testov</h3>
<?php
			for ($i = 1;
			$i <= $totalPages;
			$i++) {
?>
				
<?php
				if ($i == $page) {
?>
			    	<div class="padding-l-r-32">
			    	<?php
					/* line 38 */
					echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["tableForm"], []);
?>

			    		<div class="w3-right" style="margin-bottom: 5px"> 
			    		<?php echo end($this->global->formsStack)["select"]->getControl() /* line 40 */ ?>

						<?php echo end($this->global->formsStack)["clear"]->getControl() /* line 41 */ ?>

						<?php echo end($this->global->formsStack)["filter"]->getControl() /* line 42 */ ?>

						</div>
						<table class="w3-table-all w3-small w3-hoverable">
							<tr class="w3-hover-white">
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "date_time" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 46 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'date_time',$order, $desc])) ?>">Dátum testu</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "width" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 49 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'width',$order, $desc])) ?>">Šírka vzoru (mm)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_r" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 52 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'dif_r',$order, $desc])) ?>">Rozdiel červenej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_g" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 55 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'dif_g',$order, $desc])) ?>">Rozdiel zelenej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_b" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 58 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'dif_b',$order, $desc])) ?>">Rozdiel modrej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "sum" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 61 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'sum',$order, $desc])) ?>">Rozdiel spolu (abs %)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "printer_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 64 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'printer_res',$order, $desc])) ?>">Rozlíšenie tlačiarne (dpi)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "scanner_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 67 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'scanner_res',$order, $desc])) ?>">Rozlíšenie skenera (dpi)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "test_note" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 70 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set,1, 'test_note',$order, $desc])) ?>">Poznámka</a>
								</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>	
<?php
					$iterations = 0;
					foreach ($tests as $test) {
?>
								<tr>
									<td><?php echo LR\Filters::escapeHtmlText($test->date_time->format('d-m-Y H:m:s')) /* line 78 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->width) /* line 79 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->dif_r) /* line 80 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->dif_g) /* line 81 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->dif_b) /* line 82 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->sum) /* line 83 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->printer_res) /* line 84 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->scanner_res) /* line 85 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($test->test_note) /* line 86 */ ?></td>	
<?php
						if ($test->test == 2 or $test->test == 3 or $test->test == 4) {
?>
									    <td>&nbsp;</td>
<?php
						}
						else {
?>
									    <td>
											<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:deletetest", [$test->test,$id_set])) ?>">Vymazať</a>
									    </td>
<?php
						}
						?>									<td><input name="chcktest[]" type="checkbox" value="<?php echo LR\Filters::escapeHtmlAttr($test->test) /* line 94 */ ?>" 
									<?php
						if (!is_null($filter)) {
							if (in_array($test->test, $filter)) {
								?>checked<?php
							}
						}
?>></td>
								</tr>
<?php
						$iterations++;
					}
?>
						</table>
					<?php
					echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

					</div>
<?php
				}
			}
		}
?>
	</div>

	 <div class="w3-center" style="margin-bottom:16px">
		<div class="w3-bar">
<?php
		if ($page == 1) {
?>
		  	<a href="#" class="w3-bar-item w3-button">&laquo;</a>
<?php
		}
		else {
			?>		  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set, $page-1,$order,'x', $desc])) ?>">&laquo;</a>
<?php
		}
?>
		  
<?php
		for ($i = 1;
		$i <= $totalPages;
		$i++) {
			if ($i == $page) {
				?>		  			<a class="w3-bar-item w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set, $i,$order,'x', $desc])) ?>"><?php
				echo LR\Filters::escapeHtmlText($i) /* line 116 */ ?></a>
<?php
			}
			else {
				?>		  			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set, $i,$order,'x', $desc])) ?>"><?php
				echo LR\Filters::escapeHtmlText($i) /* line 118 */ ?></a>
<?php
			}
		}
?>
		 
<?php
		if ($page == $totalPages) {
?>
		  	<a href="#" class="w3-bar-item w3-button">&raquo;</a>
<?php
		}
		else {
			?>		  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$id_set, $page+1,$order,'x', $desc])) ?>">&raquo;</a>
<?php
		}
?>
		</div>
	</div>

	<div class="w3-panel">
<?php
		/* line 131 */ $_tmp = $this->global->uiControl->getComponent("backForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
		$_tmp->render();
?>
	</div>
</div>









<?php
	}

}
