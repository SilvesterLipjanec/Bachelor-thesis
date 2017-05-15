<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/mysets.latte

use Latte\Runtime as LR;

class Template986a53521f extends Latte\Runtime\Template
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
		if (isset($this->params['set'])) trigger_error('Variable $set overwritten in foreach on line 50');
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
	<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Moje sady testov</h2>
	<p class="w3-center">Zatiaľ nemáte žiadne testy. Otestovať svoju tlačiareň môžete kliknutím na tlačidlo Otestovať tlačiareň.</p>
	</div>
	<div class="w3-center" style='margin-bottom:0px'>
		<div style="margin:auto; display:inline-block"><?php
			/* line 14 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
			$_tmp->render();
?></div>
	</div>
<?php
		}
		else {
?>
	<div class="w3-container" style="padding: 126px 16px 50px 16px">
<?php
			if ($user->getIdentity()->role == 'admin') {
?>
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Sady testov</h2>
<?php
			}
			else {
?>
		<h2 class="w3-xxlarge w3-border-bottom w3-section w3-padding-16 padding-left-64">Moje sady testov</h2>
<?php
			}
			for ($i = 1;
			$i <= $totalPages;
			$i++) {
?>
				
<?php
				if ($i == $page) {
?>
			    	<div class="padding-l-r-32">
					<table class="w3-table-all w3-small w3-hoverable">
						<tr class="w3-hover-white">
							<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "producer" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 29 */ ?>">
								<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [1, 'producer',$order, $desc])) ?>">Značka tlačiarne</a>
							</th>
							<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "model" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 32 */ ?>">
								<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [1, 'model',$order, $desc])) ?>">Model tlačiarne</a>
							</th>
						   <th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "type" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 35 */ ?>">
								<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [1, 'type',$order, $desc])) ?>">Typ tlačiarne</a>
							</th>
							<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "set_note" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 38 */ ?>">
								<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [1, 'set_note',$order, $desc])) ?>">Poznámka</a>
							</th>						    
<?php
					if ($user->getIdentity()->role == 'admin') {
						?>						    	<th class=<?php echo LR\Filters::escapeHtmlAttrUnquoted($order == "id_user" ? "va-sort-bold w3-hover-opacity" : "va_sort w3-hover-grey") /* line 42 */ ?>">
									<a class="va-sort" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [1, 'id_user',$order, $desc])) ?>">Vytvoril</a>
								</th>
<?php
					}
?>
						    <th class="va-sort"><a class="va-sort">Počet testov</th>
						    <th>&nbsp;</th>
						    <th>&nbsp;</th>			
						</tr>	
<?php
					$iterations = 0;
					foreach ($sets as $set) {
?>
						<tr>
						    <td><?php echo LR\Filters::escapeHtmlText($set->producer) /* line 52 */ ?></td>
						    <td><?php echo LR\Filters::escapeHtmlText($set->model) /* line 53 */ ?></td>
						    <td><?php echo LR\Filters::escapeHtmlText($set->type) /* line 54 */ ?></td>
						    <td><?php echo LR\Filters::escapeHtmlText($set->set_note) /* line 55 */ ?></td>						    
<?php
						if ($user->getIdentity()->role == 'admin') {
							if ($userlogin[$set->id_set] != null) {
								?>						    		<td><?php echo LR\Filters::escapeHtmlText($userlogin[$set->id_set]) /* line 58 */ ?></td>
<?php
							}
							else {
?>
						    		<td>neregistrovaný</td>
<?php
							}
?>
						    	
<?php
						}
						?>						    <td><?php echo LR\Filters::escapeHtmlText($cnt[$set->id_set]) /* line 64 */ ?></td>
<?php
						if ($cnt[$set->id_set] > 0) {
?>
							    <td>
							    	<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:setdetail", [$set->id_set])) ?>">Detail</a>
							    </td>
<?php
						}
						else {
?>
								<td>&nbsp;</td>
<?php
						}
						if ($set->id_set != 0) {
?>
								<td>
								   	<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:deleteset", [$set->id_set])) ?>">Vymazať</a>
								</td>
<?php
						}
						else {
?>
								<td>&nbsp;</td>
<?php
						}
?>
							
						</tr>
<?php
						$iterations++;
					}
?>
				</table>
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
				?>	  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [$page-1, $order, 'x', $desc])) ?>">&laquo;</a>
<?php
			}
?>
	  
<?php
			for ($i = 1;
			$i <= $totalPages;
			$i++) {
				if ($i == $page) {
					?>	  			<a class="w3-bar-item w3-button w3-grey" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [$i, $order, 'x', $desc])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 98 */ ?></a>
<?php
				}
				else {
					?>	  			<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [$i, $order, 'x', $desc])) ?>"><?php
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
				?>	  	<a class="w3-bar-item w3-button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mysets", [$page+1, $order, 'x', $desc])) ?>">&raquo;</a>
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
