<?php
$footer_buttons = $args['footer_buttons'] ?? array();

if (! empty($footer_buttons) && is_array($footer_buttons)) :
?>
	<div
		class="flex flex-wrap justify-center gap-9 md:justify-start md:gap-6 [&_.saop-button]:px-4 [&_.saop-button]:py-2 [&_.saop-button]:text-base md:[&_.saop-button]:text-xs">
		<?php
		foreach ($footer_buttons as $button) :

			if (empty($button) || ! is_array($button)) {
				continue;
			}

			echo wp_kses_post(saop_render_button($button['link'], 'secondary'));
		endforeach;
		?>
	</div>
<?php endif; ?>