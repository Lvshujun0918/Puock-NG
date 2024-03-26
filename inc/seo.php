<?php if (is_category()) : ?>
    <?php
    $cat_seo_root_obj = get_category($cat);
    $cat_seo_keywords = get_option("seo-cat-keywords-" . $cat);
    if (!empty(trim($cat_seo_keywords))) {
        echo '<meta name="keywords" content="' . $cat_seo_keywords . '"/>';
    } else {
        echo '<meta name="keywords" content="' . $cat_seo_root_obj->name . '"/>';
    }
    $cat_seo_desc = get_option("seo-cat-desc-" . $cat);
    if (!empty(trim($cat_seo_desc))) {
        echo '<meta name="description" content="' . $cat_seo_desc . '"/>';
    } else {
        echo '<meta name="description" content="' . $cat_seo_root_obj->name . '"/>';
    }


    ?>
<?php endif; ?>

