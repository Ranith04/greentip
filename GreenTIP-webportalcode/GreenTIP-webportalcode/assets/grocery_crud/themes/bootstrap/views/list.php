<?php 

	$column_width = (int)(80/count($columns));
	
	if(!empty($list)){
?><div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->subject_plural; ?>
        </div>
    <div class="table-responsive bDiv">
		<table cellspacing="0" cellpadding="0" class="table table-striped b-t b-light" border="0" id="flex1">
		<thead>
			<tr class='hDiv'>
				<?php foreach($columns as $column){?>
				<th style="cursor: pointer;" class="sorting<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo '_'.$order_by[1]?><?php }?>" width='<?php echo $column_width?>%'>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>"
						rel='<?php echo $column->field_name?>'>
						<?php echo $column->display_as?>
					</div>
				</th>
				<?php }?>
				<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th align="right" abbr="tools" axis="col1" class="" width='20%'>
					<div class="text-center">
						<?php echo $this->l('list_actions'); ?>
					</div>
				</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
<?php foreach($list as $num_row => $row){ ?>
		<tr  <?php if($num_row % 2 == 1){?>class="erow"<?php }?>>
			<?php foreach($columns as $column){?>
			<td width='<?php echo $column_width?>%' class='<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>'>
				<div class='text-left'><?php echo $row->{$column->field_name} != '' ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
			</td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td align="right" width='20%'>
				<div class='tools text-center'>
					<?php if(!$unset_delete){?>
                    	<a href='<?php echo $row->delete_url?>' title='<?php echo $this->l('list_delete')?> <?php echo $subject?>' class="delete-row m-l-md" >
                    			<i class="delete-icon glyphicon glyphicon-trash"></i>
                    	</a>
                    <?php }?>
                    <?php if(!$unset_edit){?>
						<a class="active m-l-md" ui-toggle-class href='<?php echo $row->edit_url?>' title='<?php echo $this->l('list_edit')?> <?php echo $subject?>' class="edit_button"><i class="edit-icon glyphicon glyphicon-edit"></i></a>
					<?php }?>
					<?php if(!$unset_read){?>
						<a href='<?php echo $row->read_url?>' title='<?php echo $this->l('list_view')?> <?php echo $subject?>' class="edit_button m-l-md"><i class="read-icon glyphicon  glyphicon-eye-open"></i></a>
					<?php }?>
					<?php
					if(!empty($row->action_urls)){
						foreach($row->action_urls as $action_unique_id => $action_url){
							$action = $actions[$action_unique_id];
					?>
							<a href="<?php echo $action_url; ?>" class="<?php echo $action->css_class; ?> crud-action" title="<?php echo $action->label?>"><?php
								if(!empty($action->image_url))
								{
									?><img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" /><?php
								}
							?></a>
					<?php }
					}
					?>
                    <div class='clear'></div>
				</div>
			</td>
			<?php }?>
		</tr>
<?php } ?>
		</tbody>
		</table>
	</div>
<?php }else{?>
	<br/>
    <div class="row">
    <div class="col-lg-4"></div>
    <div class="panel-heading col-lg-4">
        <div class="alert ng-isolate-scope alert-warning alert-dismissable">
            <div><span class="ng-binding ng-scope">Whoops! <?php echo $this->l('list_no_items'); ?></span></div>
        </div>
    </div>
    <div class="col-lg-4"></div>
    </div>
	<br/>
<?php }?>
</div>
