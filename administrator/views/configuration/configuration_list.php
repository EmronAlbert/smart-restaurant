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
<script type="text/javascript">
jQuery().ready(function(){
   	$("tr:odd").css("background","#F4F7FB");
});
</script>
<div id="Container">
	<div class="Full">
		<div class="contentRight">
		<div class="contentLeft">
		<div class="col">
				<h2><?php echo lang('configuration'); ?></h2>
					<table cellpadding="2" class="zebra">
					<colgroup>
						<col style='width:99%;' />
						<col style='width:1%;' />
					</colgroup>
					<thead>
					<tr>
						<th><?php echo lang('name') ?></th>
						<th align="right"><?php echo lang('value') ?></th>
					</tr>
					</thead>					
					<tbody>
					<?php foreach($query as $row): ?>
						<tr>
							<td align="left" title="<?php echo lang('hint_' . $row->name); ?>" id="helphint<?php echo $row->id ?>"><?php echo lang('desc_' . $row->name); ?></td>
							<script type="text/javascript">
							   	$('#helphint<?php echo $row->id; ?>').
							   		cluetip({attribute: 'id', hoverClass: 'highlight', local:'true',arrows: true, cursor:'pointer',sticky: true, closePosition: 'body',closeText: '<?php echo img('../images/administrator/cross.png');?>', positionBy: 'Top'});
							</script>
							<td align="right">
								<div id="value_<?php echo $row->id; ?>"><?php if ($row->bool) echo $row->value == '1' ? lang('yes') : lang('no'); else echo $row->value;?>
								</div>
								<?php echo form_hidden('value_'.$row->id,$row->id); ?><?php echo form_hidden('bool_'.$row->id,$row->bool); ?>	
							</td>
						</tr>
						<?php if($row->bool && $row->name != "printing_system") { ?>
						<script type="text/javascript">
							jQuery().ready(function() {
							    $("#value_<?php echo $row->id; ?>").editable("<?php echo base_url() . '?c=configuration&m=updateValue';?>", { 
							    	data	  : "{'1':'<?php echo lang('yes');?>','0':'<?php echo lang('no');?>'}",
							    	type      : 'select',
							        indicator : '<?php echo lang('saving');?>',
							        submit    : 'OK',
							        submitdata: { value_id: $('input[@name=value_<?php echo $row->id ?>]').val() , bool: $('input[@name=bool_<?php echo $row->id ?>]').val() }
							    });
							});
							</script>	
						<?php } elseif($row->name == "printing_system") { ?>
						<script type="text/javascript">
							jQuery().ready(function() {
							    $("#value_<?php echo $row->id; ?>").editable("<?php echo base_url() . '?c=configuration&m=updateValue';?>", { 
							    	data	  : "{'win':'Windows','lp':'Linux'}",
							    	type      : 'select',
							        indicator : '<?php echo lang('saving');?>',
							        submit    : 'OK',
							        submitdata: { value_id: $('input[@name=value_<?php echo $row->id ?>]').val() , bool: $('input[@name=bool_<?php echo $row->id ?>]').val() }
							    });
							});
							</script>					
						<?php } elseif($row->name == "default_timezone") { ?>
						<script type="text/javascript">
							jQuery().ready(function() {
							    $("#value_<?php echo $row->id; ?>").editable("<?php echo base_url() . '?c=configuration&m=updateValue';?>", { 
							    	loadurl   : '<?php echo base_url() . '?c=configuration&m=timezoneList';?>',
							    	type      : 'select',
							        indicator : '<?php echo lang('saving');?>',
							        submit    : 'OK',
							        submitdata: { value_id: $('input[@name=value_<?php echo $row->id ?>]').val() , bool: $('input[@name=bool_<?php echo $row->id ?>]').val() }
							    });
							});
							</script>					
						<?php } else { ?>
							<script type="text/javascript">
							jQuery().ready(function() {
							    $("#value_<?php echo $row->id; ?>").editable("<?php echo base_url() . '?c=configuration&m=updateValue';?>", { 
							        indicator : '<?php echo lang('saving');?>',
							        submitdata: { value_id: $('input[@name=value_<?php echo $row->id ?>]').val() }
							    });
							});
							</script>							
						<?php } ?>
					<?php endforeach; ?>
					</tbody>
				</table>
        </div>
        </div>
		</div>
	</div>
</div>
<div class="ClearAll"></div>