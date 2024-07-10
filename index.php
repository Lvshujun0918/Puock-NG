<?php get_header() ?>

<?php if (pk_get_option('index_mode') != 'company'): ?>

    <div id="content" class="mt30 container">
        <?php get_template_part('templates/box', 'global-top') ?>
        <div class="pk-scroll-wrap pk-index-wrap row row-cols-1">
            <div class="<?php echo pk_get_class_name('left-content','pk-index-posts-wrap', 'col-lg-'. pk_hide_sidebar_out('12', '8', null, false), 'col-md-12', pk_open_box_animated('animated fadeInLeft', false)) ?> ">
                <?php if (isset($paged) && $paged <= 1): ?>
                    <div class="pk-index-posts-banner <?php pk_open_box_animated('animated fadeInLeft') ?>">
                        <?php get_template_part('templates/module', 'banners') ?>
                    </div>
                <?php endif; ?>
                <div class="<?php echo pk_get_class_name('pk-index-posts-list', pk_open_box_animated('animated fadeInLeft', false), pk_post_style_list() ? '' : 'pr-0 pl-0') ?>">
                    <div>
                        <?php get_template_part('templates/module', 'posts') ?>
                    </div>
                </div>
            </div>
            <?php get_sidebar() ?>
        </div>

        <?php get_template_part('templates/module', 'cms') ?>

        <?php get_template_part('templates/module', 'links') ?>

        <?php get_template_part('templates/box', 'global-bottom') ?>

        <?php dynamic_sidebar('index_bottom') ?>

    </div>

<?php else: get_template_part('templates/page', 'company'); endif; ?>



<?php get_footer() ?>
