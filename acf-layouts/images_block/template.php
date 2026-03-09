<?php
/**
 * Images Block Layout Render Template.
 *
 * @array   $layout      Image block
 * @array   $field       Flexible content field settings
 * @bool    $is_preview  True in Administration
 */?>
<div class="layout-hero <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
	<div class="images-block <?php if(get_sub_field('type')) echo ' type-'.get_sub_field('type').' ';?>">
		<?php if(get_sub_field('type') == 'slider'):?>
			<?php 
			$images = get_sub_field('images_gallery');
			$size = 'full'; 
			if( $images ): ?>
				<div class="slider" style="display:flex;">
					<?php foreach( $images as $image_id ): ?>
						<div class="img">
							<?php echo wp_get_attachment_image( $image_id, $size ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php else:?>
				<?php 
				$img_id = get_sub_field('image'); 
				if($img_id) echo wp_get_attachment_image($img_id, 'full'); 
				?>
		<?php endif;?>
		</div>
</div>