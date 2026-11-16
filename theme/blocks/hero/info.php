<?php
$heading = $args['heading'] ?? '';
$subheadings = $args['subheadings'] ?? array();
$buttons = $args['buttons'] ?? array();

$subheadings = is_array($subheadings)
    ? array_values(
        array_filter(
            $subheadings,
            static function ($subheading) {
                return is_array($subheading)
                    && ! empty(trim((string) ($subheading['subheading'] ?? '')));
            }
        )
    )
    : array();

$button_links = array();

if (is_array($buttons)) {
    foreach ($buttons as $button) {
        if (! is_array($button)) {
            continue;
        }

        $button_link = ! empty($button['url'])
            ? $button
            : ($button['link'] ?? $button['button'] ?? null);

        if (is_array($button_link) && ! empty($button_link['url']) && ! empty($button_link['title'])) {
            $button_links[] = $button_link;
        }
    }
}
?>

<div class="absolute bottom-12 left-[5%] flex flex-col ">
    <?php if (! empty($heading)) : ?>
    <p class="mt-0 mb-4 text-2xl text-primary-2 text-shadow-md"><?php echo esc_html($heading); ?></p>
    <?php endif; ?>

    <?php if (! empty($subheadings)) : ?>
    <div class="mb-8 grid overflow-hidden" data-hero-subheadings>
        <?php foreach ($subheadings as $index => $subheading) : ?>
        <h2 class="<?php echo 0 === $index ? 'translate-y-0 opacity-100' : 'invisible translate-y-full opacity-0'; ?> col-start-1 row-start-1 mt-0 mb-0 transform text-6xl lg:text-8xl font-bold text-white transition-all duration-500 ease-in-out motion-reduce:transition-none"
            data-hero-subheading aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
            <?php echo esc_html($subheading['subheading']); ?>
        </h2>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="h-0.75 w-41 bg-white shadow-md"></div>

    <?php if (! empty($button_links)) : ?>
    <div class="mt-7.5 flex flex-row gap-3">
        <?php foreach ($button_links as $button_link) : ?>
        <?php echo wp_kses_post(saop_render_button($button_link, 'primary')); ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>