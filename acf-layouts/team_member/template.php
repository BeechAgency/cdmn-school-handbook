<?php
/**
 * Team Member Layout Render Template.
 *
 * @array   $layout      Team member
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
</style>
<div class="layout-hero <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
	<div class="team-member" style="padding:20px">
		<p><?php the_sub_field('sub_title'); ?></p>
		<h2 style="padding:0;margin:0 0 15px"><?php the_sub_field('main_title'); ?></h2>
		<?php if( have_rows('team_section') ): ?>
			<?php while( have_rows('team_section') ): the_row(); ?>
				<h3><?php the_sub_field('category_title'); ?></h3>
				<?php $items = get_sub_field('items_in_row');?>
				<?php if( have_rows('team_list') ): ?>
					<div class="team-list <?php echo $items;?>" style="display:flex;flex-wrap:wrap;gap:20px;">
						<?php while( have_rows('team_list') ): the_row(); ?>
							<div class="team-member-item" style="margin:0 0 20px;<?php if($items == 'three') echo 'width:30%;';?> <?php if($items == 'four') echo 'width:22%;';?>" >
								<?php 
								$img_id = get_sub_field('image'); 
								echo wp_get_attachment_image($img_id, 'medium'); 
								?>
								<h6 style="margin:5px 0 5px"><?php the_sub_field('name'); ?></h6>
								<small><?php the_sub_field('position'); ?></small>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php endif; ?>
		<?php if( have_rows('buttons_list') ): ?>
			<div class="buttons-h" style="padding-top:20px">
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