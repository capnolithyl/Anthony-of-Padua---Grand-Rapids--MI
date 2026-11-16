<div class="mb-2 font-accent text-3xl text-accent-2 md:text-4xl lg:mb-6 lg:text-5xl">
    <?php if (get_field("heading")) : ?>

        <?php echo get_field("heading"); ?>

    <?php else : ?>
        Please add a heading to the mission block.
    <?php endif; ?>

</div>