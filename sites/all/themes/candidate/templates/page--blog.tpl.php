
<!--==============================Header================================-->
<?php
	include_once("includes/header.inc");
?>

<!--==============================Breadcrumb================================-->


<!--==============================content================================-->
<section id="content">
    <section class="section page-heading animate-onscroll">
        <div class="row">
            <div class="<?php if($page['cart']): print 'col-lg-9 col-md-9 col-sm-9'; else:  print 'col-lg-12 col-md-12 col-sm-12'; endif;?>">
                <?php if($title):?>
                    <h1><?php echo $title; ?></h1>
                <?php endif;?>
                <?php if (theme_get_setting('breadcrumbs') == 1): ?>
                    <?php if ($breadcrumb): ?>
                        <div class="breadcrumb">
                            <div class="container">
                                <div class="row">
                                    <?php print $breadcrumb; ?>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if($page['cart']):?>
                <div class="col-lg-3 col-md-3 col-sm-3 align-right">
                    <!-- Shopping Cart -->
                    <div class="shopping-cart">
                        <div class="cart-button">
                            <i class="icons icon-basket"></i>
                        </div>
                        <div class="shopping-cart-dropdown">
                            <div class="shopping-cart-content">

                                <?php print render($page['cart']);?>

                            </div>

                        </div>

                    </div>
                    <!-- /Shopping Cart -->
                </div>
            <?php endif;?>
        </div>
    </section>
    <div>

		<?php if ($messages) { print $messages; } ?>
        <div class="section full-width-bg gray-bg">
				<?php if ($tabs = render($tabs)): ?>
					<div class="tabs-link">
						<div class="clearfix tabs_conrainer">
							<?php print render($tabs); ?>
						</div>
					</div>
				<?php endif; ?>
            <?php if(isset($variables['node'])){
                if(!empty($variables['node']->field_layout_mode['und'])){
                    $layout_mode = $variables['node']->field_layout_mode['und'][0]['value'];
                }
            };?>
				<?php if ($action_links): ?>
					<div class="tabs-link">
						<div class="clearfix tabs_conrainer">
							<?php print render($action_links); ?>
						</div>
					</div>
				<?php endif; ?>
                <div class="row">
                   <?php if($layout_mode == 'fullwidth'):?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <?php if ($page['content']) : ?>
                            <?php print render($page['content']); ?>
                        <?php endif; ?>
                    </div>
                    <?php else:?>
                    <div class="col-lg-9 col-md-9 col-sm-8">
                        <?php if ($page['content']) : ?>
                            <?php print render($page['content']); ?>
                        <?php endif; ?>
                    </div>
                   <div class="col-lg-3 col-md-3 col-sm-4 sidebar">
                       <?php if ($page['sidebar_right']) : ?>
                           <?php print render($page['sidebar_right']); ?>
                       <?php endif; ?>
                   </div>
                    <?php endif;?>
                </div>
				<?php if ($page['after_content']) : ?>
					<?php print render($page['after_content']); ?>
				<?php endif; ?>
			</div>
	</div>
</section>

<!--==============================Footer================================-->
<?php
include_once("includes/footer.inc");
?>