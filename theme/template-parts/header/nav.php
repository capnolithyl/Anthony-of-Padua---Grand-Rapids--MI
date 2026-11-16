<?php
/**
 * Primary navigation and mobile menu chrome.
 *
 * Max Mega Menu continues to own the menu output and interactions for the
 * menu-1 location. The surrounding controls provide the Figma mobile drawer
 * presentation without duplicating the primary menu.
 *
 * @package St._Anthony_of_Padua
 */

if ( has_nav_menu( 'menu-1' ) ) :
	$saop_nav_menu_locations       = get_nav_menu_locations();
	$saop_mobile_quick_links_menu = isset( $saop_nav_menu_locations['menu-3'] )
		? absint( $saop_nav_menu_locations['menu-3'] )
		: 0;
	?>
	<nav class="py-7 transition-all"
		id="site-navigation"
		aria-label="<?php esc_attr_e( 'Main Navigation', 'saop' ); ?>"
		data-mobile-menu-shell>
		<div class="saop-mobile-menu-toolbar" aria-hidden="true" data-mobile-menu-toolbar>
			<button class="saop-mobile-menu-toolbar__button"
				type="button"
				aria-label="<?php esc_attr_e( 'Close menu', 'saop' ); ?>"
				data-mobile-menu-close>
				<i class="fas fa-chevron-left" aria-hidden="true"></i>
			</button>

			<button class="saop-mobile-menu-toolbar__button"
				type="button"
				aria-label="<?php esc_attr_e( 'Open site search', 'saop' ); ?>"
				aria-controls="site-search-dialog"
				aria-expanded="false"
				aria-haspopup="dialog"
				data-site-search-open>
				<i class="fas fa-search" aria-hidden="true"></i>
			</button>
		</div>

		<?php
		wp_nav_menu(
			array(
				'theme_location'  => 'menu-1',
				'menu_id'         => 'primary-menu',
				'container'       => 'div',
				'container_class' => '',
			)
		);
		?>
		<span class="saop-mobile-menu-divider"
			aria-hidden="true"
			data-mobile-menu-divider></span>

		<?php if ( $saop_mobile_quick_links_menu ) : ?>
			<div class="saop-mobile-menu-quick-links"
				aria-hidden="true"
				data-mobile-menu-quick-links>
				<?php
				wp_nav_menu(
					array(
						// Render by menu ID so Max Mega Menu does not create a
						// second, nested instance of the menu-3 location.
						'menu'           => $saop_mobile_quick_links_menu,
						'theme_location' => '',
						'menu_id'        => 'mobile-quick-links-menu',
						'menu_class'     => 'saop-mobile-menu-quick-links__list',
						'depth'          => 1,
						'container'      => false,
					)
				);
				?>
			</div>
		<?php endif; ?>
	</nav>
<?php endif; ?>
