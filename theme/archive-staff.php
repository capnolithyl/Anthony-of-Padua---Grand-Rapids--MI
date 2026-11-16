<?php
/**
 * Staff directory archive.
 *
 * @package St._Anthony_of_Padua
 */

$staff_groups           = saop_get_staff_groups();
$staff_members_by_group = array();
$ungrouped_staff        = array();

foreach ( $staff_groups as $staff_group ) {
	$staff_members_by_group[ $staff_group->term_id ] = array();
}

global $wp_query;

foreach ( $wp_query->posts as $staff_member ) {
	$member_groups = get_the_terms( $staff_member->ID, 'staff_group' );
	$has_group     = false;

	if ( $member_groups && ! is_wp_error( $member_groups ) ) {
		foreach ( $member_groups as $member_group ) {
			if ( isset( $staff_members_by_group[ $member_group->term_id ] ) ) {
				$staff_members_by_group[ $member_group->term_id ][] = $staff_member;
				$has_group = true;
			}
		}
	}

	if ( ! $has_group ) {
		$ungrouped_staff[] = $staff_member;
	}
}

$staff_sections = array();

foreach ( $staff_groups as $staff_group ) {
	$staff_sections[] = array(
		'id'      => 'staff-group-' . sanitize_title( $staff_group->slug ),
		'label'   => $staff_group->name,
		/* translators: %s: Staff group name. */
		'heading' => sprintf( __( 'Our %s', 'saop' ), $staff_group->name ),
		'members' => $staff_members_by_group[ $staff_group->term_id ],
	);
}

if ( $ungrouped_staff ) {
	$staff_sections[] = array(
		'id'      => 'staff-group-other',
		'label'   => __( 'Other Staff', 'saop' ),
		'heading' => __( 'Other Staff', 'saop' ),
		'members' => $ungrouped_staff,
	);
}

get_header();
?>

<main id="primary" class="not-prose bg-white" data-staff-directory>
	<header class="relative h-60 overflow-hidden bg-primary-1 md:h-[357px] lg:h-[clamp(357px,30vw,455px)]">
		<img class="absolute inset-0 h-full w-full object-cover"
			src="<?php echo esc_url( get_theme_file_uri( '/assets/img/staff-archive-hero.jpg' ) ); ?>"
			alt="" loading="eager" fetchpriority="high">
		<div class="absolute inset-0 bg-linear-to-t from-black/65 via-black/10 to-transparent" aria-hidden="true"></div>
		<div class="relative mx-auto flex h-full w-[calc(100%-2.5rem)] max-w-wide items-end pb-4 md:pb-8 lg:w-[calc(100%-3rem)] lg:pb-10">
			<h1 class="font-heading text-[28px] leading-normal font-bold text-white text-shadow-md md:text-4-5xl">
				<?php esc_html_e( 'Our Faculty & Staff', 'saop' ); ?>
			</h1>
		</div>
	</header>

	<div class="py-15">
		<?php if ( count( $staff_sections ) > 1 ) : ?>
			<nav class="relative mx-auto h-16.5 w-[calc(100%-1rem)] max-w-mid rounded-2lg border-2 border-accent-2 md:h-24.5 md:w-[calc(100%-2.5rem)] lg:h-29 lg:w-[calc(100%-3rem)] lg:border-3"
				aria-label="<?php esc_attr_e( 'Staff groups', 'saop' ); ?>">
				<span class="absolute right-2 bottom-5 left-2 h-px bg-secondary-4 md:right-3 md:bottom-7.5 md:left-3 lg:right-16 lg:bottom-9 lg:left-16" aria-hidden="true"></span>

				<div class="saop-staff-group-slider h-full" data-staff-group-slider>
					<?php foreach ( $staff_sections as $index => $staff_section ) : ?>
						<div class="saop-staff-group-slide h-full">
							<a class="saop-staff-group-link relative z-10 flex h-full items-start justify-center px-2 pt-2.75 text-center font-heading text-xl leading-normal font-bold text-primary-1 uppercase transition-colors after:absolute after:right-2 after:bottom-4.5 after:left-2 after:h-1 after:rounded-full after:bg-transparent after:content-[''] hover:text-accent-2 hover:after:bg-accent-2 focus:text-accent-2 focus:after:bg-accent-2 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary-2 md:px-3 md:pt-4.25 md:text-[22px] md:after:right-0 md:after:bottom-7 md:after:left-0 md:after:h-1.5 lg:pt-5 lg:after:bottom-8.5"
								href="#<?php echo esc_attr( $staff_section['id'] ); ?>"
								data-staff-group-link
								<?php echo 0 === $index ? ' aria-current="location"' : ''; ?>>
								<?php echo esc_html( $staff_section['label'] ); ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>

				<button class="saop-staff-slider-arrow absolute bottom-0 left-2 z-20 size-10 translate-y-1/2 cursor-pointer rounded-full border-3 border-white bg-accent-1 transition-opacity md:left-5.5"
					type="button" data-staff-group-prev aria-label="<?php esc_attr_e( 'Previous staff groups', 'saop' ); ?>" hidden>
					<img class="mx-auto size-6 rotate-180" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/ministry-slider-arrow.svg' ) ); ?>" alt="" aria-hidden="true">
				</button>

				<button class="saop-staff-slider-arrow absolute right-2 bottom-0 z-20 size-10 translate-y-1/2 cursor-pointer rounded-full border-3 border-white bg-accent-1 transition-opacity md:right-5.5"
					type="button" data-staff-group-next aria-label="<?php esc_attr_e( 'Next staff groups', 'saop' ); ?>" hidden>
					<img class="mx-auto size-6" src="<?php echo esc_url( get_theme_file_uri( '/assets/img/ministry-slider-arrow.svg' ) ); ?>" alt="" aria-hidden="true">
				</button>
			</nav>
		<?php endif; ?>

		<?php if ( $staff_sections ) : ?>
			<div class="mx-auto mt-19 w-[calc(100%-2rem)] max-w-mid space-y-20 md:mt-22.5 md:w-[calc(100%-5.5rem)] lg:mt-15 lg:w-[calc(100%-3rem)]">
				<?php foreach ( $staff_sections as $staff_section ) : ?>
					<section id="<?php echo esc_attr( $staff_section['id'] ); ?>"
						class="scroll-mt-36"
						aria-labelledby="<?php echo esc_attr( $staff_section['id'] . '-heading' ); ?>">
						<h2 id="<?php echo esc_attr( $staff_section['id'] . '-heading' ); ?>"
							class="font-heading text-[28px] leading-normal font-bold text-primary-1 md:text-4-5xl">
							<?php echo esc_html( $staff_section['heading'] ); ?>
						</h2>

						<?php if ( $staff_section['members'] ) : ?>
							<div class="mt-5 grid gap-3.75 md:grid-cols-2 md:gap-x-6.25 md:gap-y-7.25 lg:grid-cols-3 lg:gap-8">
								<?php foreach ( $staff_section['members'] as $staff_member ) : ?>
									<?php
									get_template_part(
										'template-parts/content/content',
										'staff-card',
										array(
											'staff_id' => $staff_member->ID,
											'staff_section_id' => $staff_section['id'],
										)
									);
									?>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p class="mt-5 text-lg text-primary-1">
								<?php esc_html_e( 'No staff members are available in this group yet.', 'saop' ); ?>
							</p>
						<?php endif; ?>
					</section>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="mx-auto w-[calc(100%-2rem)] max-w-mid text-lg text-primary-1 md:w-[calc(100%-5.5rem)] lg:w-[calc(100%-3rem)]">
				<?php esc_html_e( 'No staff members are available yet.', 'saop' ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php foreach ( $wp_query->posts as $staff_member ) : ?>
		<?php
		get_template_part(
			'template-parts/content/content',
			'staff-dialog',
			array( 'staff_id' => $staff_member->ID )
		);
		?>
	<?php endforeach; ?>
</main>

<?php
get_footer();
