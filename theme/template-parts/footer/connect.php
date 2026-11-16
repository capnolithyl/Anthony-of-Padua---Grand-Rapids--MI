<?php
$footer_connect_heading = $args['footer_connect_heading'] ?? __('Connect With Us', 'saop');
$social_links           = $args['social_links'] ?? array();
$phone                  = $args['phone'] ?? '';
$fax                    = $args['fax'] ?? '';
$email                  = $args['email'] ?? '';
$phone_link             = $phone ? preg_replace('/[^0-9+]/', '', $phone) : '';
$display_phone          = $phone ?: '(000)000-0000';
$display_fax            = $fax ?: '(000)000-0000';
$display_email          = $email ?: 'pjapdfaosd.com';

if (empty($social_links) || ! is_array($social_links)) {
	$social_links = array(
		array('icon' => 'facebook'),
		array('icon' => 'instagram'),
		array('icon' => 'youtube'),
	);
}
?>

<section class="mt-9 lg:mt-10" aria-labelledby="footer-connect-heading">
	<div class="flex items-center gap-3">
		<h2 id="footer-connect-heading" class="text-secondary-3 shrink-0 text-xl font-bold uppercase">
			<?php echo esc_html($footer_connect_heading); ?>
		</h2>
		<span class="bg-secondary-3 h-0.5 flex-1" aria-hidden="true"></span>
	</div>

	<div class="mt-5 grid grid-cols-[7.5rem_minmax(0,1fr)] gap-x-5">
		<ul class="text-secondary-3 flex items-start justify-between"
			aria-label="<?php esc_attr_e('Social media', 'saop'); ?>">
			<?php foreach ($social_links as $social_link) :

				$icon_name  = strtolower((string) ($social_link['icon'] ?? ''));
				$social_url = $social_link['link'] ?? '';

				if (is_array($social_url)) :
					$social_url = $social_url['link'] ?? '';
				endif;

				if (false !== strpos($icon_name, 'instagram')) :
					$label     = __('Instagram', 'saop');
				elseif (false !== strpos($icon_name, 'youtube')) :
					$label     = __('YouTube', 'saop');
				else :
					$label     = __('Facebook', 'saop');
				endif;
			?>
				<li>
					<?php if ($social_url) : ?>
						<a class="hover:text-primary-2 block transition-colors" href="<?php echo esc_url($social_url); ?>"
							target="_blank" rel="noopener noreferrer">
						<?php else : ?>
							<span class="block">
							<?php endif; ?>

							<span class="sr-only">
								<?php echo esc_html($label); ?>
							</span>

							<?php echo $social_link['icon']; ?>

							<?php if ($social_url) : ?>
						</a>
					<?php else : ?>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<ul class="text-sm leading-tight text-black">
			<li>
				<span>
					<?php esc_html_e('Phone:', 'saop'); ?>
				</span>

				<?php if ($phone) : ?>

					<a class="hover:text-secondary-3" href="<?php echo esc_url('tel:' . $phone_link); ?>">
						<?php echo esc_html($display_phone); ?>
					</a>

				<?php else : ?>

					<span>
						<?php echo esc_html($display_phone); ?>
					</span>

				<?php endif; ?>
			</li>

			<li>
				<span>
					<?php esc_html_e('Fax:', 'saop'); ?>
				</span>

				<span>
					<?php echo esc_html($display_fax); ?>
				</span>
			</li>

			<li class="break-all">
				<span>
					<?php esc_html_e('Email:', 'saop'); ?>
				</span>

				<?php if ($email) : ?>
					<a class="hover:text-secondary-3" href="<?php echo esc_url('mailto:' . sanitize_email($email)); ?>">
						<?php echo esc_html(antispambot($display_email)); ?>
					</a>

				<?php else : ?>

					<span>
						<?php echo esc_html($display_email); ?>
					</span>

				<?php endif; ?>
			</li>
		</ul>
	</div>
</section>