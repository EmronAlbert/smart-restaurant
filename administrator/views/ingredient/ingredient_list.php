<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Smart Restaurant
 *
 * An open source application to manage restaurants
 *
 * @package		SmartRestaurant
 * @author		Gjergj Sheldija
 * @copyright	Copyright (c) 2008, Gjergj Sheldija
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @since		Version 1.0
 * @filesource
 * 
 *  Smart Restaurant is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, version 3 of the License.
 *
 *	Smart Restaurant is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.

 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
?>
<script type="text/javascript">
	jQuery().ready(function(){
		jQuery('#firsttable').accordion({
				header: 'div.mytitle',
			    active: false, 
			    alwaysOpen: false
		});
});
$( function(){
	$("table.zebra tr:even").addClass("even");
	$("table.zebra tr:odd").addClass("odd");
});
</script>
<div id="Container">
	<div class="Full">
		<div class="contentRight">
		<div class="contentLeft">
		<div class="col">
			<div class="Left">
				<h2><?=lang('ingredients') ?> :: <?=anchor('ingredient/newIngredient',lang('new_ingredient')) ?></h2>
				<div class="basic" style="float:left;"  id="firsttable">
				<?php 
				$tmp = "";
				foreach($query->result() as $row) {	
					if($tmp != $row->catname) {
						echo '<div class="mytitle">'.$row->catname.'</div>';?>
					<table class="zebra">
						<colgroup>
							<col style='width:15%;' />
							<col style='width:10%;' />
							<col style='width:10%;' />
							<col style='width:25%;' />
						</colgroup>
						<thead>
							<tr>
								<th><?=lang('name'); ?></th>
								<th><?=lang('price'); ?></th>							
								<th><?=lang('sell_price'); ?></th>
								<th><?=lang('action'); ?></th>
							</tr>
						</thead>
					<?php		
					}						
					?>	
						<tr>
							<td><?=$row->name ?></td>					
							<td align="right"><?=$row->price ?></td>
							<td align="right"><?=$row->sell_price?></td>
							<td align="right"><?=anchor_image('ingredient/edit/'.$row->id, '../images/administrator/edit.png');?> :: <?=anchor_image('ingredient/delete/'.$row->id , '../images/administrator/edit_remove.png');?></td>
						</tr>
				<?php 
					$rowtmp = $query->next_row();
					$tmp = $rowtmp->catname;
					if($tmp != $row->catname) {
						$tmp = $row->catname;
						echo "</table>";
					}
				}; 
				?>
				</table>
			</div>
			</div>
			<div class="Right">				
				<?php $this->load->view('ingredient/ingredient_edit') ?>
			</div>
        </div>
        </div>
		</div>
	</div>
</div>
<div class="ClearAll"></div>