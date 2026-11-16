<?php
/**
 * Ministry directory archive.
 *
 * @package St._Anthony_of_Padua
 */

$saop_ministry_taxonomy      = saop_get_ministry_taxonomy();
$saop_ministry_groups        = saop_get_ministry_groups();
$saop_ministries_by_group    = array();
$saop_ungrouped_ministries   = array();
$saop_ministry_archive_image = saop_get_ministry_archive_image();

foreach ( $saop_ministry_groups as $saop_ministry_group ) {
	$saop_ministries_by_group[ $saop_ministry_group->term_id ] = array();
}

global $wp_query;

foreach ( $wp_query->posts as $saop_ministry ) {
	$saop_assigned_to_group = false;
	$saop_assigned_groups   = $saop_ministry_taxonomy ? get_the_terms( $saop_ministry->ID, $saop_ministry_taxonomy ) : array();

	if ( $saop_assigned_groups && ! is_wp_error( $saop_assigned_groups ) ) {
		foreach ( $saop_assigned_groups as $saop_assigned_group ) {
			if ( isset( $saop_ministries_by_group[ $saop_assigned_group->term_id ] ) ) {
				$saop_ministries_by_group[ $saop_assigned_group->term_id ][] = $saop_ministry;

				$saop_assigned_to_group = true;
			}
		}
	}

	if ( ! $saop_assigned_to_group ) {
		$saop_ungrouped_ministries[] = $saop_ministry;
	}
}

$saop_ministry_sections = array();

foreach ( $saop_ministry_groups as $saop_ministry_group ) {
	$saop_ministry_sections[] = array(
		'id'         => 'ministry-group-' . sanitize_title( $saop_ministry_group->slug ),
		'label'      => $saop_ministry_group->name,
		'ministries' => $saop_ministries_by_group[ $saop_ministry_group->term_id ],
	);
}

if ( $saop_ungrouped_ministries ) {
	$saop_ministry_sections[] = array(
		'id'         => 'ministry-group-other',
		'label'      => __( 'Other Ministries', 'saop' ),
		'ministries' => $saop_ungrouped_ministries,
	);
}

get_header();
?>

<main id="primary" class="not-prose bg-white" data-ministry-archive>
	<header class="relative h-60 overflow-hidden bg-primary-1 md:h-[357px] lg:h-[clamp(357px,30vw,455px)]">
		<?php if ( $saop_ministry_archive_image['id'] ) : ?>
			<?php
			echo wp_get_attachment_image(
				$saop_ministry_archive_image['id'],
				'full',
				false,
				array(
					'alt'           => '',
					'class'         => 'absolute inset-0 h-full w-full object-cover',
					'decoding'      => 'async',
					'fetchpriority' => 'high',
					'loading'       => 'eager',
				)
			);
			?>
		<?php elseif ( $saop_ministry_archive_image['url'] ) : ?>
			<img class="absolute inset-0 h-full w-full object-cover"
				src="<?php echo esc_url( $saop_ministry_archive_image['url'] ); ?>" alt="" loading="eager" fetchpriority="high">
		<?php endif; ?>

		<div class="absolute inset-0 bg-linear-to-t from-black/65 via-black/10 to-transparent" aria-hidden="true"></div>

		<div class="relative mx-auto flex h-full w-[calc(100%-2.5rem)] max-w-wide items-end pb-4 md:pb-8 lg:w-[calc(100%-3rem)] lg:pb-10">
			<h1 class="font-heading text-[28px] leading-normal font-bold text-white text-shadow-md md:text-4-5xl">
				<?php esc_html_e( 'Ministries', 'saop' ); ?>
			</h1>
		</div>
	</header>

	<div class="py-15 lg:py-15">
		<?php if ( $saop_ministry_sections ) : ?>
			<nav class="relative mx-auto h-16.5 w-[calc(100%-1rem)] max-w-mid rounded-2lg border-2 border-accent-2 md:h-24.5 md:w-[calc(100%-2.5rem)] lg:h-29 lg:w-[calc(100%-3rem)] lg:border-3"
				aria-label="<?php esc_attr_e( 'Ministry groups', 'saop' ); ?>">
				<span class="absolute right-2 bottom-5 left-2 h-px bg-secondary-4 md:right-3 md:bottom-7.5 md:left-3 lg:right-16 lg:bottom-9 lg:left-16" aria-hidden="true"></span>

				<div class="saop-ministry-group-slider h-full" data-ministry-group-slider>
					<?php foreach ( $saop_ministry_sections as $saop_ministry_section_index => $saop_ministry_section ) : ?>
						<div class="saop-ministry-group-slide h-full">
							<a class="saop-ministry-group-link relative z-10 flex h-full items-start justify-center px-2 pt-2.75 text-center font-heading text-xl leading-normal font-bold text-primary-1 uppercase transition-colors after:absolute after:right-2 after:bottom-4.5 after:left-2 after:h-1 after:rounded-full after:bg-transparent after:content-[''] hover:text-accent-2 hover:after:bg-accent-2 focus:text-accent-2 focus:after:bg-accent-2 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary-2 md:px-3 md:pt-4.25 md:text-[22px] md:after:right-0 md:after:bottom-7 md:after:left-0 md:after:h-1.5 lg:pt-5 lg:after:bottom-8.5"
								href="#<?php echo esc_attr( $saop_ministry_section['id'] ); ?>"
								data-ministry-group-link
								<?php echo 0 === $saop_ministry_section_index ? ' aria-current="location"' : ''; ?>>
								<?php echo esc_html( $saop_ministry_section['label'] ); ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>

				<button class="saop-ministry-slider-arrow absolute bottom-0 left-2 z-20 size-10 translate-y-1/2 cursor-pointer rounded-full border-3 border-white bg-accent-1 transition-opacity md:left-5.5"
					type="button" data-ministry-group-prev aria-label="<?php esc_attr_e( 'Previous ministry groups', 'saop' ); ?>" hidden>
					<img class="mx-auto size-6 rotate-180" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/ministry-slider-arrow.svg' ) ); ?>" alt="" aria-hidden="true">
				</button>

				<button class="saop-ministry-slider-arrow absolute right-2 bottom-0 z-20 size-10 translate-y-1/2 cursor-pointer rounded-full border-3 border-white bg-accent-1 transition-opacity md:right-5.5"
					type="button" data-ministry-group-next aria-label="<?php esc_attr_e( 'Next ministry groups', 'saop' ); ?>" hidden>
					<img class="mx-auto size-6" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/ministry-slider-arrow.svg' ) ); ?>" alt="" aria-hidden="true">
				</button>
			</nav>
		<?php endif; ?>

		<?php if ( $saop_ministry_sections ) : ?>
			<div class="mx-auto mt-19 w-[calc(100%-1rem)] max-w-wide space-y-22.5 md:mt-22.5 md:w-[calc(100%-2.5rem)] lg:mt-15 lg:w-[calc(100%-3rem)]">
				<?php foreach ( $saop_ministry_sections as $saop_ministry_section ) : ?>
					<section id="<?php echo esc_attr( $saop_ministry_section['id'] ); ?>"
						class="scroll-mt-36"
						aria-labelledby="<?php echo esc_attr( $saop_ministry_section['id'] . '-heading' ); ?>">
						<h2 id="<?php echo esc_attr( $saop_ministry_section['id'] . '-heading' ); ?>"
							class="px-2 font-heading text-[28px] leading-normal font-bold text-primary-1 md:px-0 md:text-4-5xl">
							<?php echo esc_html( $saop_ministry_section['label'] ); ?>
						</h2>

						<?php if ( $saop_ministry_section['ministries'] ) : ?>
							<div class="mt-5 grid gap-1.5 md:grid-cols-2 md:gap-x-6.25 md:gap-y-7.25 md:px-3 lg:grid-cols-3 lg:gap-8 lg:px-1">
								<?php foreach ( $saop_ministry_section['ministries'] as $saop_ministry ) : ?>
									<?php
									get_template_part(
										'template-parts/content/content',
										'ministry-card',
										array( 'ministry_id' => $saop_ministry->ID )
									);
									?>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p class="mt-5 text-lg text-primary-1">
								<?php esc_html_e( 'No ministries are available in this group yet.', 'saop' ); ?>
							</p>
						<?php endif; ?>
					</section>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="mx-auto w-[calc(100%-3rem)] max-w-wide text-lg text-primary-1">
				<?php esc_html_e( 'No ministries are available yet.', 'saop' ); ?>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
