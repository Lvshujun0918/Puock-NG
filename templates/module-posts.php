<!--文章列表-->
<div class="pk-posts-list" id="posts">
    <div class="pk-post-wrap <?php if(!pk_post_style_list()){echo 'row';} ?> mr-0 ml-0">
        <?php while(have_posts()) : the_post(); ?>
            <?php get_template_part('templates/module','post') ?>
        <?php endwhile; ?>
    </div>
    <?php if(!(pk_get_option('index_mode','')=='cms' && is_home())): ?>
    <?php pk_paging(); ?>
    <?php endif; ?>
</div>
