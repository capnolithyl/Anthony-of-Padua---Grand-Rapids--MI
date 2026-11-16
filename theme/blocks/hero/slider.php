<?php $slides = get_field('slides'); ?>
<?php if (empty($slides)) : ?>

<p><?php esc_html_e('Please add at least one slide to this hero block.', 'saop'); ?></p>

<?php else : ?>
<div data-hero-slider class="relative mb-0! overflow-hidden">
    <?php foreach ($slides as $index => $slide) : ?>
    <article class="relative" data-hero-slide>
        <div class="relative">
            <?php if (empty($slide['image']['ID'])) : ?>
            <p>
                <?php esc_html_e('No image uploaded for this slide.', 'saop'); ?>
            </p>
            <?php else : ?>
            <?php echo wp_get_attachment_image($slide['image']['ID'], 'full', false, array('class' => 'h-hero w-screen object-cover')); ?>
            <?php endif; ?>

            <?php get_template_part("blocks/hero/overlay"); ?>
        </div>


        <?php get_template_part("blocks/hero/info", null, array("heading" => $slide['heading'], "subheadings" => $slide['subheadings'], "buttons" => $slide['buttons'])); ?>
    </article>
    <?php endforeach; ?>


</div>
<?php endif; ?>