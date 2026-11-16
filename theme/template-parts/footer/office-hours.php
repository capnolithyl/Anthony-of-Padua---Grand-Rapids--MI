<?php
if (have_rows('office_hours', 'option')) :
	$office_hours_heading = $args['office_hours_heading'];

?>

	<div class="mt-auto flex flex-col pt-8 text-sm leading-tight">
		<span class="font-medium">
			<?php echo esc_html($office_hours_heading); ?>:
		</span>

		<ul class="inline-flex flex-wrap gap-x-2">
			<?php while (have_rows('office_hours', 'option')) : the_row(); ?>
				<li class="inline">
					<span>
						<?php echo esc_html(get_sub_field('label') ?? ''); ?>
					</span>

					<span>
						<?php echo esc_html(get_sub_field('hours') ?? ''); ?>
					</span>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>

<?php endif;
