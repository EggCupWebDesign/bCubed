<?php
/**
 * Title: Featured Image Hero Block
 * Slug: b3/featured-image-hero-block
 * Categories: text
 */

?>
<!-- wp:group {"tagName":"header","align":"full","className":"is-style-header","layout":{"type":"default"}} -->
<header class="wp-block-group alignfull is-style-header">
    <!-- wp:cover {"useFeaturedImage":true,"dimRatio":40,"focalPoint":{"x":0.55,"y":0.44},"minHeight":300,"layout":{"type":"default"}} -->
    <div class="wp-block-cover" style="min-height:300px">
        <span aria-hidden="true" class="wp-block-cover__background has-background-dim-40 has-background-dim"></span>
        <div class="wp-block-cover__inner-container">
            <!-- wp:heading {"textAlign":"center","level":1,"align":"wide","textColor":"background"} -->
            <h1 class="wp-block-heading alignwide has-text-align-center has-background-color has-text-color">Page Title</h1>
            <!-- /wp:heading -->
        </div>
    </div>
    <!-- /wp:cover -->
</header>
<!-- /wp:group -->