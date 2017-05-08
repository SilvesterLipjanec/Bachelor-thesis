<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/stats.latte

use Latte\Runtime as LR;

class Templatee175889135 extends Latte\Runtime\Template
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
<div style="min-height: 830px">
<?php
		if ($totalPages == 0) {
?>
	

	<div class="w3-container" style="padding: 126px 16px 50px 16px ">
	<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Najlepšie testy</h2>
	<p class="w3-center">Zatiaľ neexistujú žiadne testy. Otestovať svoju tlačiareň môžete kliknutím na tlačidlo Otestovať tlačiareň.</p>
	</div>
	<div class="w3-center" style='margin-bottom:0px'>
		<div style="margin:auto; display:inline-block"><?php
			/* line 11 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
			$_tmp->render();
?></div>
	</div>
<?php
		}
		else {
?>
		<div class="w3-container" style="padding: 126px 16px 50px 16px">
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Najlepšie testy</h2>
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
					/* line 20 */
					echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["tableForm"], []);
?>

			    		<div class="w3-right" style="margin-bottom: 5px"> 
			    		<?php echo end($this->global->formsStack)["select"]->getControl() /* line 22 */ ?>

						<?php echo end($this->global->formsStack)["clear"]->getControl() /* line 23 */ ?>

						<?php echo end($this->global->formsStack)["filter"]->getControl() /* line 24 */ ?>

						</div>
						<table class="w3-table-all w3-small w3-hoverable">
							<tr class="w3-hover-white">
								<th>&nbsp;</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "producer" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 29 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'producer',$order, $desc])) ?>">Značka</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "model" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 32 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'model',$order, $desc])) ?>">Model</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_r" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 35 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'dif_r', $order, $desc])) ?>">Rozdiel červenej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_g" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 38 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'dif_g', $order, $desc])) ?>">Rozdiel zelenej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "dif_b" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 41 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'dif_b', $order, $desc])) ?>">Rozdiel modrej (%)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "sum" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 44 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'sum', $order, $desc])) ?>">Rozdiel spolu (abs %)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "width" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 47 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'width', $order, $desc])) ?>">Šírka vzoru (mm)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "date_time" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 50 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'date_time', $order, $desc])) ?>">Dátum testu</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "printer_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 53 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'printer_res', $order, $desc])) ?>">Rozlíšenie tlačiarne (dpi)</a>
								</th>
								<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "scanner_res" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 56 */ ?>">
									<a class="va-sort " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [1, 'scanner_res', $order, $desc])) ?>">Rozlíšenie skenera (dpi)</a>
								</th>
								<th>&nbsp;</th>		
							</tr>	
<?php
					for ($j = 0;
					$j < count($tests) ;
					$j++) {
?>
								<tr>
									<td class="w3-border-right"><?php echo LR\Filters::escapeHtmlText((($page-1)*$itemsPP)+$j+1) /* line 63 */ ?></td>
									<td><?php echo LR\Filters::escapeHtmlText($tests[$j]->producer) /* line 64 */ ?></td>
									<td><?php echo LR\Filters::escapeHtmlText($tests[$j]->model) /* line 65 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->dif_r) /* line 66 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->dif_g) /* line 67 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->dif_b) /* line 68 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->sum) /* line 69 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->width) /* line 70 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->date_time->format('d.m.Y H:i:s')) /* line 71 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->printer_res) /* line 72 */ ?></td>
								    <td><?php echo LR\Filters::escapeHtmlText($tests[$j]->scanner_res) /* line 73 */ ?></td>
								    <td><input name="chcktest[]" type="checkbox" value="<?php echo LR\Filters::escapeHtmlAttr($tests[$j]->test) /* line 74 */ ?>" <?php
						if (!is_null($filter)) {
							if (in_array($tests[$j]->test, $filter)) {
								?>checked<?php
							}
						}
?>></td>
   
								</tr>
<?php
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
?>
	

	</div>

	 <div class="w3-center" style="margin-bottom:32px">
		<div class="w3-bar">
<?php
			if ($page == 1) {
?>
		  	<a href="#" class="w3-bar-item w3-button">&laquo;</a>
<?php
			}
			else {
				?>		  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [$page-1, $order, 'x', $desc])) ?>">&laquo;</a>
<?php
			}
?>
		  
<?php
			for ($i = 1;
			$i <= $totalPages;
			$i++) {
				if ($i == $page) {
					?>		  			<a class="w3-bar-item w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [$i, $order, 'x', $desc])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 98 */ ?></a>
<?php
				}
				else {
					?>		  			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [$i, $order, 'x', $desc])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 100 */ ?></a>
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
				?>		  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:stats", [$page+1, $order, 'x', $desc])) ?>">&raquo;</a>
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
