<div class="w-full bg-secondary-3 px-9 py-9 text-white md:w-1/2 md:px-6 md:py-6 lg:w-2/3 lg:px-10">
	<div class="grid h-full grid-cols-1 content-between lg:grid-cols-2 lg:grid-rows-[auto_1fr_auto] lg:gap-x-16">
		<div class="order-1 justify-self-center md:justify-self-start lg:col-start-1 lg:row-start-1">
			<?php get_template_part('template-parts/footer/logo', null, $args); ?>
		</div>

		<div class="order-3 mt-12 w-fit md:order-2 md:mt-8 lg:col-start-2 lg:row-start-1 lg:mt-0">
			<?php get_template_part('template-parts/footer/quicklinks', null, $args); ?>
		</div>

		<div
			class="order-2 mt-12 flex flex-col items-center gap-8 md:order-3 md:mt-8 md:items-start md:gap-4 lg:col-start-1 lg:row-start-3 lg:mt-0 lg:gap-6">
			<?php get_template_part('template-parts/footer/address', null, $args); ?>
			<?php get_template_part('template-parts/footer/buttons', null, $args); ?>
		</div>

		<div
			class="order-4 mt-10 justify-self-end md:mt-6 md:justify-self-start lg:col-start-2 lg:row-start-3 lg:mt-0 lg:place-self-end">
			<?php get_template_part('template-parts/footer/site-info', null, $args); ?>
		</div>
	</div>
</div>