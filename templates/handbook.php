<?php
/**
 * Template Name: Handbook
 */

defined('ABSPATH') || exit;

get_header();

$site_title     = get_field('site_title');
$site_sub_title = get_field('site_sub_title');
$site_desc      = get_field('site_description');
$logo           = get_field('logo');
$sidebar_text   = get_field('sidebar_text');
?>

<div class="handbook-wrapper" >
	<!-- Sidebar -->
	<aside class="handbook-sidebar">
		<div class="top">
			<?php if($site_title) echo '<h4>' . esc_html($site_title) . '</h4>';?>
			<h5>Navigation</h5>
			<ul>
				<?php if( have_rows('handbook_content') ): $counter = 0; ?>
					<?php while( have_rows('handbook_content') ): the_row(); $counter++; ?>
						<?php $main_title = get_sub_field('main_title'); ?>
						<?php if($main_title): ?>
							<li><a href="#block-<?php echo $counter; ?>"><?php echo esc_html($main_title); ?></a></li>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>
			</ul>
		</div>
		<?php if($sidebar_text) echo '<div class="info">' . $sidebar_text . '</div>';?>
	</aside>

	<!-- Content -->
	<div class="handbook-content">
		<?php if($logo || $site_title || $site_sub_title || $site_desc):?>
			<div class="header-bar">
				<?php if($logo) echo '<div class="logo-h">' . wp_get_attachment_image($logo, 'full', 0, array('title'=> '')) . '</div>'; ?>
				<div class="site-info">
					<a class="hamburger"><span></span><span></span><span></span></a>
					<a href="<?php echo esc_url(home_url('/')); ?>" class="back">&larr; Back to main site</a>
					<?php if($site_title) echo '<h1>' . esc_html($site_title) . '</h1>';?>
					<?php if($site_sub_title) echo '<h3>' . esc_html($site_sub_title) . '</h3>';?>
					<?php if($site_desc) echo '<p>' . esc_html($site_desc) . '</p>';?>
				</div>
			</div>
		<?php endif;?>
		<?php if( have_rows('handbook_content') ): $counter = 0; ?>
			<?php while( have_rows('handbook_content') ): the_row(); $counter++; ?>
				<?php $layout = get_row_layout(); ?>
				<div class="handbook-block <?php if($layout === 'images_block') echo 'small';?>" id="block-<?php echo $counter; ?>" >

					<?php if( $layout === 'text_block' ): ?>
						<?php
						$sub_title   = get_sub_field('sub_title');
						$main_title  = get_sub_field('main_title');
						$description = get_sub_field('description');
						$type        = get_sub_field('type');
						$text_area   = get_sub_field('text_area');
						?>
						<div class="text-block">
							<?php if($sub_title): ?>
								<p><?php echo esc_html($sub_title); ?></p>
							<?php endif; ?>
							<?php if($main_title): ?>
								<h2><?php echo esc_html($main_title); ?></h2>
							<?php endif; ?>
							<?php if($description): ?>
								<p class="desc"><?php echo esc_html($description); ?></p>
							<?php endif; ?>
							<?php if($type === 'heading'):?>
								<?php $list = get_sub_field('headings_list'); ?>
								<?php if($list): ?>
									<div class="heading-list">
										<?php foreach($list as $el): ?>
										<div class="row">
												<?php if($el['title']) echo '<div class="left">' . $el['title'] . '</div>'; ?>
												<?php if($el['content']) echo '<div class="right">' . $el['content'] . '</div>'; ?>
										</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							<?php elseif($type === 'columns'):?>
								<div class="h">
									<div class="col"><?php echo get_sub_field('left_column');?></div>
									<div class="col"><?php echo get_sub_field('right_column');?></div>
								</div>
							<?php elseif($type === 'columnsthree'):?>
								<div class="h three-col">
									<div class="col"><?php echo get_sub_field('column_one');?></div>
									<div class="col"><?php echo get_sub_field('column_two');?></div>
									<div class="col"><?php echo get_sub_field('column_three');?></div>
								</div>
							<?php else:?>
								<?php if($text_area): ?>
									<div class="wysiwyg"><?php echo $text_area; ?></div>
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

					<?php elseif( $layout === 'image_text' ): ?>
						<?php
						$sub_title      = get_sub_field('sub_title');
						$main_title     = get_sub_field('main_title');
						$type           = get_sub_field('type');
						$text_area      = get_sub_field('text_area');
						$image_position = get_sub_field('image_position');
						$img_id         = get_sub_field('image');
						?>
						<div class="image-text <?php echo esc_attr($image_position);?> <?php if($type) echo esc_attr($type);?>" >
							<?php if($type !== 'inline'):?>
								<p><?php echo esc_html($sub_title); ?></p>
								<h2><?php echo esc_html($main_title); ?></h2>
							<?php endif;?>
							<div class="h">
								<div class="text">
									<?php if($type === 'inline'):?>
										<p><?php echo esc_html($sub_title); ?></p>
										<h2><?php echo esc_html($main_title); ?></h2>
									<?php endif;?>
									<div class="wysiwyg"><?php echo $text_area; ?></div>
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
								<div class="image" >
									<?php echo wp_get_attachment_image($img_id, 'full'); ?>
								</div>
							</div>
						</div>

					<?php elseif( $layout === 'images_block' ): ?>
						<?php $type = get_sub_field('type'); ?>
						<div class="images-block <?php if($type) echo ' type-' . esc_attr($type) . ' ';?>">
						<?php if($type === 'slider'):?>
							<?php
							$images = get_sub_field('images_gallery');
							if( $images ): ?>
								<div class="slider">
									<?php foreach( $images as $image_id ): ?>
										<div class="img">
											<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
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
					<?php elseif( $layout === 'team_member' ): ?>
						<?php
						$sub_title  = get_sub_field('sub_title');
						$main_title = get_sub_field('main_title');
						?>
						<div class="team-member">
							<p><?php echo esc_html($sub_title); ?></p>
							<h2><?php echo esc_html($main_title); ?></h2>
							<?php if( have_rows('team_section') ): ?>
								<?php while( have_rows('team_section') ): the_row(); ?>
									<?php
									$category_title = get_sub_field('category_title');
									$items          = get_sub_field('items_in_row');
									$gall           = get_sub_field('gallery_on_mobile');
									?>
									<h3><?php echo esc_html($category_title); ?></h3>
									<?php if( have_rows('team_list') ): ?>
										<div class="team-list <?php if($gall) echo 'gallery';?> <?php echo esc_attr($items);?>">
											<?php while( have_rows('team_list') ): the_row(); ?>
												<?php
												$img_id   = get_sub_field('image');
												$name     = get_sub_field('name');
												$position = get_sub_field('position');
												?>
												<div class="team-member-item">
													<?php echo wp_get_attachment_image($img_id, 'medium'); ?>
													<h6><?php echo esc_html($name); ?></h6>
													<small><?php echo esc_html($position); ?></small>
												</div>
											<?php endwhile; ?>
										</div>
									<?php endif; ?>
								<?php endwhile; ?>
							<?php endif; ?>
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
					<?php elseif( $layout === 'bell_times' ): ?>
					<div class="text-block bell">
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
						<div class="row <?php if($remove) echo 'removeborder';?>">
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
					<?php endif; ?>

				</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
