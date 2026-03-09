<?php
/**
 * Text Block Layout Render Template.
 *
 * @array   $layout      Text Block
 * @array   $field       Flexible content field settings
 * @bool    $is_preview  True in Administration
 */?>
 <style>
 .btn {
	background-color:#fff;
	color: #000;
	border: 1px solid #000;
	padding-inline: 2rem;
	text-decoration:none;
	padding-block: 0.5rem;
	border-radius: 2rem;
	display: inline-block;
	transition: all 150ms ease;
	position: relative;
	text-align: center;
	cursor: pointer;
}
.btn:hover {
	background-color: #000;
	color: #fff;
	/* box-shadow: 0px 0px 13px -5px var(--text-color, black); */
}
.btn.btn-secondary {
	color:#fff;
	background-color: #000;
}

.btn.btn-secondary:hover {
    background-color:#fff;
    color:#000;
}
img{max-width:100%;height:auto}
table{width: 100%;border-collapse: collapse;border:1px solid #000;}
table th{border:1px solid #000;vertical-align:top;padding:12px 15px;text-align:left;font-family: var(--ff-serif);font-size:var(--fs-h4);color:var(--secondary-dark)}
table td{border:1px solid #000;vertical-align:top;padding:16px 15px;text-align:left;}
table.compact{width: 100%;border:none}
table.compact th,
table.compact td{padding:7px 0;border:none;text-align:left}
table.background td:first-child{background:#122D4D26}
</style>
<div class="layout-hero <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
	<div class="text-block" style="padding:20px;">
		<?php if(get_sub_field('sub_title')): ?>
			<p><?php the_sub_field('sub_title'); ?></p>
		<?php endif; ?>
		<?php if(get_sub_field('main_title')): ?>
			<h2 style="padding:0;margin:0  0 15px"><?php the_sub_field('main_title'); ?></h2>
		<?php endif; ?>
		<?php if(get_sub_field('description')): ?>
			<p class="desc"><?php the_sub_field('description'); ?></p>
		<?php endif; ?>
		<?php if(get_sub_field('type') == 'heading'):?>
			<?php if($list = get_sub_field('headings_list')): ?>
				<div class="heading-list" >
					<?php foreach($list as $el): ?>
					<div class="row"  style="display:flex;justify-content:space-between;border-top:1px solid #C5C5C5;padding:10px 0 15px">
							<?php if($el['title']) echo '<div class="left" style="width:35%">'.$el['title'].'</div>'; ?>
							<?php if($el['content']) echo '<div class="right" style="width:60%">'.$el['content'].'</div>'; ?>
					</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php elseif(get_sub_field('type') == 'columns'):?>
			<div class="h" style="display:flex;justify-content:space-between;">
				<div class="col" style="width:48%"><?php echo get_sub_field('left_column');?></div>
				<div class="col" style="width:48%"><?php echo get_sub_field('right_column');?></div>
			</div>
		<?php elseif(get_sub_field('type') == 'columnsthree'):?>
			<div class="h" style="display:flex;justify-content:space-between;">
				<div class="col" style="width:30%"><?php echo get_sub_field('column_one');?></div>
				<div class="col"  style="width:30%"><?php echo get_sub_field('column_two');?></div>
				<div class="col"  style="width:30%"><?php echo get_sub_field('column_three');?></div>
			</div>
		<?php else:?>
			<?php if(get_sub_field('text_area')): ?>
				<div class="wysiwyg"><?php the_sub_field('text_area'); ?></div>
			<?php endif; ?>
		<?php endif;?>
		
		<?php if( have_rows('buttons_list') ): ?>
			<div class="buttons-h">
				<?php $i = 0; while( have_rows('buttons_list') ): the_row(); $i++;?>
					<?php 
					$link = get_sub_field('button');
					if( $link ): 
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						?>
						<a class="btn <?php if($i > 1) echo 'btn-secondary';?>" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>