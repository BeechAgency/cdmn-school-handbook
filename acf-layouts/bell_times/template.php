<?php
/**
 * Bell times Layout Render Template.
 *
 * @array   $layout      Bell times
 * @array   $field       Flexible content field settings
 * @bool    $is_preview  True in Administration
 */?>
<div class="layout-hero <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
	<div class="text-block bell"  style="padding:20px;">
		<?php
		$sub_title  = get_sub_field('sub_title');
		$main_title = get_sub_field('main_title');
		$description = get_sub_field('description');
		?>
		<?php if($sub_title): ?>
			<p><?php echo esc_html($sub_title); ?></p>
		<?php endif; ?>
		<?php if($main_title): ?>
			<h2><?php echo esc_html($main_title); ?></h2>
		<?php endif; ?>
		<?php if($description): ?>
			<p class="desc"><?php echo esc_html($description); ?></p>
		<?php endif; ?>
		<?php $remove = get_sub_field('remove_border');?>
		<?php if (have_rows('tables')): ?>
		<div class="heading-list">
		<div class="row <?php if($remove) echo 'removeborder';?>" style="display:flex;justify-content:space-between;border-top:1px solid #C5C5C5;padding:10px 0 15px">
		<?php while (have_rows('tables')): the_row(); ?>
		<?php $type = get_sub_field('type_of_table');?>
		 <table class="<?php echo $type;?>">
			<?php if (have_rows('rows')): ?>
				<?php while (have_rows('rows')): the_row(); ?>
					<tr>
						<?php if (have_rows('columns')): ?>
							<?php while (have_rows('columns')): the_row(); 
								$content = get_sub_field('cell_content');
								$type = get_sub_field('cell_type') ?: 'td';
								$colspan = get_sub_field('colspan');
								$rowspan = get_sub_field('rowspan');
								$colspan_attr = $colspan ? ' colspan="'.$colspan.'"' : '';
								$rowspan_attr = $rowspan ? ' rowspan="'.$rowspan.'"' : '';
								
							?>
								<<?php echo $type; ?><?php echo $colspan_attr . $rowspan_attr; ?>>
									<?php echo $content; ?>
								</<?php echo $type; ?>>
							<?php endwhile; ?>
						<?php endif; ?>
					</tr>
				<?php endwhile; ?>
			<?php endif; ?>
			</table>
			 <?php endwhile; ?>
			 </div>
			 </div>
			<?php endif; ?>
		</div>
 </div>