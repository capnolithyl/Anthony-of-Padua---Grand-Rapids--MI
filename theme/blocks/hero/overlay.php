<?php
$overlay_opacity = get_field('overlay_opacity');
$overlay_opacity = is_numeric($overlay_opacity) ? (float) $overlay_opacity : 50;
$overlay_opacity = min(100, max(0, $overlay_opacity));
?>
<div class="absolute inset-0 bg-black"
    style="opacity: <?php echo esc_attr($overlay_opacity); ?>%">
</div>
