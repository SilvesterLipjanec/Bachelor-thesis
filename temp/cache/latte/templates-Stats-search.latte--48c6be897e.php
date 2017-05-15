<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/search.latte

use Latte\Runtime as LR;

class Template48c6be897e extends Latte\Runtime\Template
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
		if (isset($this->params['test'])) trigger_error('Variable $test overwritten in foreach on line 62');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div style="min-height: 830px">
	<div class="w3-container" style="padding: 126px 16px 50px 16px">
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Vyhľadanie testov podľa tlačiarne</h2>
		<div class="w3-container" style="padding-left:64px">
			<div class="w3-third">
				<?php
		/* line 8 */
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["searchModelForm"], []);
?>

					<?php if ($_label = end($this->global->formsStack)["printer"]->getLabel()) echo $_label ?>

					<?php echo end($this->global->formsStack)["printer"]->getControl() /* line 10 */ ?>

					<div class = "w3-margin-top">
						<?php if ($_label = end($this->global->formsStack)["model"]->getLabel()) echo $_label ?>

						<?php echo end($this->global->formsStack)["model"]->getControl() /* line 13 */ ?>

					</div>
					<div class="w3-section">
						<?php echo end($this->global->formsStack)["search"]->getControl() /* line 16 */ ?>

					</div>
				<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

			</div>
		</div>
<?php
		if (isset($tests[0])) {
?>
				<h3 class="w3-border-top" style="padding-left:32px; padding-top:16px">Testy vytvorené na danej tlačiarni</h3>
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
					/* line 27 */
					echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["tableForm"], []);
?>

					    		<div class="w3-right" style="margin-bottom: 5px"> 
					    		<?php echo end($this->global->formsStack)["select"]->getControl() /* line 29 */ ?>

								<?php echo end($this->global->formsStack)["clear"]->getControl() /* line 30 */ ?>

								<?php echo end($this->global->formsStack)["filter"]->getControl() /* line 31 */ ?>

								</div>
								<table class="w3-table-all w3-small w3-hoverable">
									<tr class="w3-hover-white">

										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "date_time" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 36 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'date_time',$order, $desc ])) ?>">Dátum testu</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "width" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 39 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'width',$order, $desc ])) ?>">Šírka vzoru (mm)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_r" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 42 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'dif_r',$order, $desc ])) ?>">Rozdiel červenej (%)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_g" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 45 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'dif_g',$order, $desc ])) ?>">Rozdiel zelenej (%)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_b" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 48 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'dif_b',$order, $desc ])) ?>">Rozdiel modrej (%)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "sum" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 51 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'sum',$order, $desc ])) ?>">Rozdiel spolu (abs %)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "printer_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 54 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'printer_res',$order, $desc ])) ?>">Rozlíšenie tlačiarne (dpi)</a>
										</th>
										<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "scanner_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 57 */ ?>">
											<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model,1, 'scanner_res',$order, $desc ])) ?>">Rozlíšenie skenera (dpi)</a>
										</th>
										<th>&nbsp;</th>			
									</tr>		
<?php
					$iterations = 0;
					foreach ($tests as $test) {
?>
												<tr>
												    <td><?php echo LR\Filters::escapeHtmlText($test->date_time->format('d-m-Y H:i:s')) /* line 64 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->width) /* line 65 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->dif_r) /* line 66 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->dif_g) /* line 67 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->dif_b) /* line 68 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->sum) /* line 69 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->printer_res) /* line 70 */ ?></td>
												    <td><?php echo LR\Filters::escapeHtmlText($test->scanner_res) /* line 71 */ ?></td>
								    				<td><input name="chcktest[]" type="checkbox" value="<?php echo LR\Filters::escapeHtmlAttr($test->test) /* line 72 */ ?>" <?php
						if (in_array($test->test, $filter)) {
							?>checked<?php
						}
?>></td>  
												</tr>
<?php
						$iterations++;
					}
?>
									</tr>
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
<?php
		if (isset($tests[0])) {
?>
		<div class="w3-center" style="margin-bottom:16px">
				<div class="w3-bar">
<?php
			if ($page == 1) {
?>
				  	<a href="#" class="w3-bar-item w3-button">&laquo;</a>
<?php
			}
			else {
				?>				  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model, $page-1, $order,'x', $desc])) ?>">&laquo;</a>
<?php
			}
?>
				  
<?php
			for ($i = 1;
			$i <= $totalPages;
			$i++) {
				if ($i == $page) {
					?>				  			<a class="w3-bar-item w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model, $i, $order,'x', $desc ])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 94 */ ?></a>
<?php
				}
				else {
					?>				  			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model, $i, $order,'x', $desc])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 96 */ ?></a>
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
				?>				  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:search", [$printer, $model, $page+1, $order,'x', $desc])) ?>">&raquo;</a>
<?php
			}
?>
			</div>
		</div>
<?php
		}
?>
</div>
<?php
	}

}
