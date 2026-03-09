<?php
/**
 * Image + Text Layout Render Template.
 *
 * @array   $layout      Image + text
 * @array   $field       Flexible content field settings
 * @bool    $is_preview  True in Administration
 */?>
<div class="layout-hero <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
	<div style="padding:20px;" class="image-text  <?php if(get_sub_field('type')) echo get_sub_field('type');?>" >
			<?php if(get_sub_field('type') != 'inline'):?>
				<p><?php the_sub_field('sub_title'); ?></p>
				<h2 style="padding:0;margin:0 0 15px"><?php the_sub_field('main_title'); ?></h2>
			<?php endif;?>
			<div class="h" style="display:flex;justify-content:space-between; <?php if(get_sub_field('image_position') == 'Left') echo 'flex-direction:row-reverse';?>">
				<div class="text" style="width:48%">
					<?php if(get_sub_field('type') == 'inline'):?>
						<p><?php the_sub_field('sub_title'); ?></p>
						<h2 style="padding:0;margin:0 0 15px"><?php the_sub_field('main_title'); ?></h2>
					<?php endif;?>
					<div class="wysiwyg"><?php the_sub_field('text_area'); ?></div>
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
				<div class="image" style="width:48%">
					<?php 
					$img_id = get_sub_field('image'); 
					echo wp_get_attachment_image($img_id, 'full'); 
					?>
				</div>
			</div>
		</div>
</div>