<?php

$saop_blocks_dir = get_theme_file_path('/blocks');
$saop_shared_assets_file = $saop_blocks_dir . '/shared/assets.php';

if (is_readable($saop_shared_assets_file)) {
    require_once $saop_shared_assets_file;
}

$saop_block_directories = glob($saop_blocks_dir . '/*', GLOB_ONLYDIR);

if ($saop_block_directories) {
    sort($saop_block_directories);

    foreach ($saop_block_directories as $saop_block_directory) {
        $saop_load_assets_file = "$saop_block_directory/load-assets.php";

        if (is_readable($saop_load_assets_file)) {
            require_once $saop_load_assets_file;
        }
    }
}
