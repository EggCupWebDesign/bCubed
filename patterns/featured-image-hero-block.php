<?php
/**
 * Title: Featured Image Hero Block
 * Slug: b3/featured-image-hero-block
 * Categories: text
 */

?>
<!-- wp:cover {"dimRatio":30,"overlayColor":"foreground","isUserOverlayColor":true,"minHeight":35,"minHeightUnit":"rem","align":"full","className":"is-style-cover-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull is-style-cover-hero" style="min-height:35rem">
    <span aria-hidden="true" class="wp-block-cover__background has-foreground-background-color has-background-dim-30 has-background-dim"></span>
    <div class="wp-block-cover__inner-container">
        <!-- wp:group {"tagName":"header","metadata":{"name":"Page Header"},"align":"wide","layout":{"type":"constrained"}} -->
        <header class="wp-block-group alignwide">
            <!-- wp:heading {"textAlign":"center","level":1,"align":"wide","textColor":"background"} -->
            <h1 class="wp-block-heading alignwide has-text-align-center has-background-color has-text-color">Page Title</h1>
            <!-- /wp:heading -->
        </header>
        <!-- /wp:group -->
    </div>
</div>
<!-- /wp:cover -->