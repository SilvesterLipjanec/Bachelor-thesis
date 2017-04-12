<?php
// source: /var/www/html/BP/app/presenters/templates/Stats/mytests.latte

use Latte\Runtime as LR;

class Templatea61072f42d extends Latte\Runtime\Template
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
		if (isset($this->params['set'])) trigger_error('Variable $set overwritten in foreach on line 24');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<h2>Moje testy</h2>


<?php
		if (isset($nosets)) {
?>
	<p>Zatiaľ nemáte žiadne testy. Otestovať svoju tlačiareň môžete kliknutím na tlačidlo.</p>
<?php
			/* line 9 */ $_tmp = $this->global->uiControl->getComponent("nextForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(NULL, FALSE);
			$_tmp->render();
		}
		else {
?>

<?php
			for ($i = 1;
			$i <= $totalPosts;
			$i++) {
?>

<?php
				if ($i == $page) {
?>

        <table>
		<tr>
		    <th>Výrobca tlačiarne</th>
		    <th>Model tlačiarne</th>
		    <th>Typ tlačiarne</th>
		    <th>Poznámka</th>
		    <th>Počet testov</th>
			
<?php
					$iterations = 0;
					foreach ($sets as $set) {
?>
			<tr>
			    <td><?php echo LR\Filters::escapeHtmlText($set->producer) /* line 26 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($set->model) /* line 27 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($set->type) /* line 28 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($set->set_note) /* line 29 */ ?></td>
			    <td><?php echo LR\Filters::escapeHtmlText($cnt[$set->id_set]) /* line 30 */ ?></td>
			    
			</tr>
<?php
						$iterations++;
					}
?>
	</table>

<?php
				}
				else {
?>

        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Stats:mytests", [$i, $totalPosts])) ?>"><?php
					echo LR\Filters::escapeHtmlText($i) /* line 38 */ ?></a>

<?php
				}
?>

<?php
			}
?>



	
<?php
		}
?>








<?php
	}

}
