<h2 class="mb-9 pl-2.5 text-3xl text-primary-1 md:text-4xl  lg:pl-16 lg:text-5xl">
    <?php if (get_field("subheading")) : ?>

        <?php echo get_field("subheading"); ?>

    <?php else : ?>

        Please add a subheading to the mission block.

    <?php endif; ?>
</h2>