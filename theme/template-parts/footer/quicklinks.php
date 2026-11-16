<?php if (has_nav_menu('menu-2')) : ?>
<div class="flex w-full flex-col">
    <h2 class="mb-4 text-xl font-bold text-white uppercase">
        <?php echo esc_html(wp_get_nav_menu_name('menu-2')); ?>
    </h2>

    <nav class="border-t-2 border-secondary-2 pt-4 text-white" aria-label="<?php esc_attr_e('Footer Menu', 'saop'); ?>">
        <?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-2',
					'menu_class'     => 'grid grid-cols-2 gap-x-20 gap-y-5 text-sm [&_a]:transition-colors [&_a]:hover:text-secondary-2',
					'depth'          => 1,
					'container'      => false,
				)
			);
			?>
    </nav>
</div>
<?php endif; ?>