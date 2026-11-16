<?php

/**
 * Mission block template.
 *
 * @package St._Anthony_of_Padua
 */

$wrapper_attributes    = get_block_wrapper_attributes(
    array(
        'class' => 'saop-section saop-mission not-prose mt-4 mb-20',
    )
);
?>

<section <?php echo $wrapper_attributes; ?>>

    <div class="hidden md:block lg:hidden">
        <?php get_template_part("blocks/mission/heading"); ?>
        <?php get_template_part("blocks/mission/subheading"); ?>
    </div>

    <div class="flex flex-col gap-11 md:flex-row">
        <div class="flex-5">
            <?php if (get_field("image")) : ?>

                <div
                    class="relative after:absolute after:top-1/3 after:-left-5 after:h-3/4 after:w-3/4 after:rounded-2lg after:border-4 after:border-accent-2 after:content-['']">


                    <img class="relative z-1 aspect-square rounded-2lg object-cover shadow-md"
                        src="<?php echo get_field("image")["url"]; ?>" alt="<?php echo get_field("image")["alt"]; ?>">

                </div>
            <?php else : ?>

                <p>
                    Please add an image to the mission block.
                </p>

            <?php endif; ?>
        </div>

        <div class="flex-8 pt-2">
            <div class="min-h-[95%]">

                <div class="md:hidden lg:block">
                    <?php get_template_part("blocks/mission/heading"); ?>
                    <?php get_template_part("blocks/mission/subheading"); ?>
                </div>

                <?php if (get_field("text")) : ?>
                    <p class="text-lg leading-[normal] lg:text-xl">
                        <?php echo get_field("text"); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (have_rows("buttons")) : ?>
                <div class="mt-10 flex flex-row gap-8 lg:justify-end">
                    <?php while (have_rows("buttons")) : the_row(); ?>
                        <?php echo wp_kses_post(saop_render_button(get_sub_field("link"), "outline")); ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</section>