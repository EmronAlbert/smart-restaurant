<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Smart Restaurant
 *
 * An open source application to manage restaurants
 *
 * @package		SmartRestaurant
 * @author		Gjergj Sheldija
 * @copyright	Copyright (c) 2008-2009, Gjergj Sheldija
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
<script type="text/javascript" charset="utf-8">
	<!--
        jQuery(function($)
        {
            $("#picker1").attachColorPicker();
           // $("#picker1").change(function() {$(document.body).css("background-color",$("#picker1").getValue())});
        });
	//-->
</script>
<?php if( isset($edit) ) { ?>
<?=form_open_multipart('category/save');?>
<table>
	<thead>
		<tr>
			<th colspan="2">
			<h2><?=lang('category_info'); ?></h2>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=form_hidden('id',$edit[0]->id) ?> <?=form_label(lang('name'));?> :</td>
			<td><?=form_input('name',$edit[0]->name); ?></td>
		</tr>
		<tr>
			<td><?=form_label(lang('color'));?> :</td>
			<td><?=form_input(array('name'=>'htmlcolor', 'id'=>'picker1','size'=>'7','value'=>$edit[0]->htmlcolor));?></td>
		</tr>
		<tr>
			<td><?=form_label(lang('image'));?> :</td>
			<td><?=form_upload('image',$edit[0]->image); ?></td>
		</tr>
		<tr>
			<td><?=isset($edit[0]->image) ? img('../'.$edit[0]->image) : 'nuk ka'; ?></td>
			<td></td>
		</tr>
		<tr>
			<td><input type="submit" value="<?=lang('save'); ?>"></td>
			<td></td>
		</tr>
	</tbody>
</table>	
</form>
<?php } elseif( isset($newcat) ) {?>
<?=form_open_multipart('category/addnew');?>
<table>
	<thead>
		<tr>
			<th colspan="2">
			<h2><?=lang('new_category'); ?></h2>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=form_hidden('id') ?> <?=form_label(lang('name'));?> :</td>
			<td><?=form_input('name'); ?></td>
		</tr>
		<tr>
			<td><?=form_label(lang('color'));?> :</td>
			<td><?=form_input(array('name'=>'htmlcolor', 'id'=>'picker1','size'=>'7'));?></td>
		</tr>
		<tr>
			<td><?=form_label(lang('image'));?> :</td>
			<td><?=form_upload('image'); ?></td>
		</tr>
		<tr>
			<td><?=lang('no_info'); ?></td>
			<td></td>
		</tr>
		<tr>
			<td><input type="submit" value="<?=lang('save'); ?>"></td>
			<td></td>
		</tr>
	</tbody>
</table>	
</form>
<?php } ?>