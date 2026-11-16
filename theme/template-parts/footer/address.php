<?php
$address = $args['address'] ?? '';

if ($address) :
?>
	<address class="text-center text-sm leading-snug text-white not-italic md:text-left [&_p]:m-0">
		<?php echo wp_kses_post($address); ?>
	</address>
<?php endif; ?>