
<!--==============================Header================================-->
<?php
	include_once("includes/header.inc");
	$content_type="";
	if(isset($node)){
		$content_type = node_type_get_name($node);
	}

?>

<!--==============================content================================-->
<section id="content">
    <section class="section page-heading animate-onscroll">
        <div class="row">
<!--         	<?php
        	 $block = module_invoke('views', 'block_view', 'name-block_1');	
				print render($block['content']);
        	?> -->
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
        <?php if(isset($variables['node'])){
            if(!empty($variables['node']->field_layout_mode['und'])){
                $layout_mode = $variables['node']->field_layout_mode['und'][0]['value'];
            }
        };?>

		<?php if ( !$page['sidebar_left'] && $page['sidebar_right']) : ?>
			<div class="section full-width-bg gray-bg">
				<?php if ($page['before_content']) : ?>
					<?php print render($page['before_content']); ?>
				<?php endif; ?>
				<div class="panel-pane pane-block pane-block-3 banners-inline-wrapper pane-block banner-wrapper">
					 <div class="pane-content">
					 	<div class="banners-inline"><div class="banner-wrapper">
					 		<a class="banner animate-onscroll" href="/candidate/event-created"><i class="icons icon-calendar icons-fadeout"></i><i class="icons icon-calendar icons-fadeout"></i><i class="icons icon-calendar icons-fadeout"></i>
					 			<i class="icons icon-calendar"></i>
					 			<h4>Find Events</h4>
					 			<p>Lorem ipsum dolor sit amet</p>
					 		</a>
					 	</div><div class="banner-wrapper">
					 		<a class="banner animate-onscroll" href="user"><i class="icons icon-check icons-fadeout"></i><i class="icons icon-check icons-fadeout"></i><i class="icons icon-check icons-fadeout"></i>
					 			<i class="icons icon-check"></i>
					 			<h4>Register to vote</h4>
					 			<p>Nemo enim ipsam</p>
					 		</a>
					 	</div><div class="banner-wrapper">
					 		<a class="banner animate-onscroll" href="https://themeforest.net/user/arrowhitech/portfolio"><i class="icons icon-user icons-fadeout"></i><i class="icons icon-user icons-fadeout"></i><i class="icons icon-user icons-fadeout"></i>
					 			<i class="icons icon-user"></i>
					 			<h4>Volunteer</h4>
					 			<p>Pellentesque sed dolor</p>
					 		</a>
					 	</div><div class="banner-wrapper">
					 		<div class="banner donate-banner animate-onscroll">
					 			<h5>Make a <strong>quick donation</strong> here</h5>
					 			<form method="post" action="https://www.paypal.com/uk/cgi-bin/webscr" id="sd_paypalform" name="_xclick"><input type="radio" name="sd_radio" id="donate-amount-1" class="sd_object sd_usermod sd_radio" value="5">
					 				<input id="donate-amount-1" type="radio" name="donate-amount">
					 				<label for="donate-amount-1">$5</label>
					 				<input id="donate-amount-2" type="radio" name="donate-amount">
					 				<label for="donate-amount-2">$25</label>
					 				<input id="donate-amount-3" type="radio" name="donate-amount">
					 				<label for="donate-amount-3">$100</label>
					 				<input type="submit" value="Donate">
					 			</form>
					 		</div>
					 	</div></div>
					 </div>
					</div>
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-8">
							<?php if ($tabs = render($tabs)): ?>
								<div class="tabs-link">
									<div class="clearfix tabs_conrainer">
										<?php print render($tabs); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($messages) { print $messages; } ?>
							<?php if ($action_links): ?>
								<div class="tabs-link">
									<div class="clearfix tabs_conrainer">
										<?php print render($action_links); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($page['content']) : ?>
								<?php print render($page['content']); ?>
							<?php endif; ?>
							<?php if ($page['after_content']) : ?>
								<?php print render($page['after_content']); ?>
							<?php endif; ?>
						</div>
					<div class="col-lg-3 col-md-3 col-sm-4 sidebar">
						<?php print render($page['sidebar_right']); ?>
					</div>
				</div>
			</div>
		<?php elseif ($page['sidebar_left'] && !$page['sidebar_right'] ) : ?>
            <div class="section full-width-bg gray-bg">
			    <div class="row">
				<div class="col-lg-9 col-md-9 col-sm-8 col-lg-push-3 col-md-push-3 col-sm-push-4">
						<?php if ($tabs = render($tabs)): ?>
							<div class="tabs-link">
								<div class="clearfix tabs_conrainer">
									<?php print render($tabs); ?>
								</div>
							</div>
						<?php endif; ?>
                        <?php if ($messages) { print $messages; } ?>
						<?php if ($action_links): ?>
							<div class="tabs-link">
								<div class="clearfix tabs_conrainer">
									<?php print render($action_links); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if ($page['content']) : ?>
							<?php print render($page['content']); ?>
						<?php endif; ?>
						<?php if ($page['after_content']) : ?>
							<?php print render($page['after_content']); ?>
						<?php endif; ?>
					</div>

                <div class="col-lg-3 col-md-3 col-sm-4 col-lg-pull-9 col-md-pull-9 col-sm-pull-8 sidebar">
                    <?php print render($page['sidebar_left']); ?>
                </div>
                </div>
			</div>
		<?php elseif ($page['sidebar_left'] && $page['sidebar_right'] ) : ?>
			<div class="section full-width-bg gray-bg">	
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12">
						<?php print render($page['sidebar_left']); ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<div class="section full-width-bg gray-bg">
							<?php if ($tabs = render($tabs)): ?>
								<div class="tabs-link">
									<div class="clearfix tabs_conrainer">
										<?php print render($tabs); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ($messages) { print $messages; } ?>
							<?php if ($action_links): ?>
								<div class="tabs-link">
									<div class="clearfix tabs_conrainer">
										<?php print render($action_links); ?>
									</div>
								</div>
							<?php endif; ?>
							<div class="row">
							<?php if ($page['content']) : ?>
								<?php print render($page['content']); ?>
							<?php endif; ?>
							</div>
							<?php if ($page['after_content']) : ?>
								<?php print render($page['after_content']); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12">
						<?php print render($page['sidebar_right']); ?>
					</div>
				</div>
			</div>
		<?php else :?>
				<div class="section full-width-bg gray-bg <?php if($content_type =='Event Calendar'): print 'p-tb'; endif;?>">
                    <?php if ($tabs = render($tabs)): ?>
                    <div class="tabs-link">
                        <div class="clearfix tabs_conrainer">
                            <?php print render($tabs); ?>
                        </div>
                    </div>
					<?php endif; ?>
                    <?php if ($messages) { print $messages; } ?>
					<?php if ($action_links): ?>
						<div class="tabs-link">
							<div class="clearfix tabs_conrainer">
								<?php print render($action_links); ?>
							</div>
						</div>
					<?php endif; ?>

	                <div class="row">
	                    <div class="col-lg-12 col-md-12 col-sm-12">
	                        <?php if ($page['content']) : ?>
	                            <?php print render($page['content']); ?>
	                        <?php endif; ?>
	                    </div>

	                </div>

					<?php if ($page['after_content']) : ?>
						<?php print render($page['after_content']); ?>
					<?php endif; ?>
				</div>
		<?php endif; ?>
	</div>
</section>

<!--==============================Footer================================-->			
<?php
	include_once("includes/footer.inc");
?>