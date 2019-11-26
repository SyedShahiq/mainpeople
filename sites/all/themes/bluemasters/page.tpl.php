<div id="page">

<!--header-top-->
<div id="header-top">
    <div id="header-top-inside" class="clearfix">
        <div id="header-top-inside-left"><div id="header-top-inside-left-content"><?php print render($page['header']); ?> </div></div>
        <div id="header-top-inside-left-feed">
            <div id="topSocial">
                <ul>
                    <li><a class="twitter" href="http://twitter.com/mainepeople" title="Follow Us on Twitter!"></a></li>
                    <li><a class="facebook" href="http://www.facebook.com/mainepeople" title="Join Us on Facebook!"></a></li>
                    <li><a class="rss" title="RSS" href="../rss.xml" title="Subcribe to Our RSS Feed"></a></li>
                </ul>
            </div>
        </div>
        <div id="header-top-inside-right"><?php print render($page['search_area']);?> </div>
    </div>
</div>
<!--/header-top-->

<div id="wrapper">

	<div id="header" class="clearfix">

	    <div id="logo-floater">
		<h1><a href="<?php print $front_page ?>">
        <?php if ($logo): ?>
        	<img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" title="<?php print $site_name_and_slogan ?>" id="logo" /><br/>
        <?php endif; ?>
        <?php print $site_name_and_slogan ?>
        </a></h1>

	    </div> <!--logo-->

	     <div id="navigation">
			<?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('class' => array('links primary-links', 'inline', 'clearfix')))); ?>
	    </div><!--navigation-->

	</div><!--header-->

<div id="main-area" class="clearfix">

<div id="main-area-inside" class="clearfix">

    <div id="main"  class="inside clearfix">
		<?php print $messages;?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="title" id="page-title">
          <?php print $title; ?>
        </h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
        <?php if ($tabs): ?><?php print render($tabs); ?><?php endif; ?>
        <?php print render($page['content']); ?>
        <?php print render($page['help']); ?>
    </div><!--main-->

    <div id="right" class="clearfix">

    	<?php print render($page['sidebar_first']); ?>
    </div><!--right-->

</div>

</div><!--main-area-->
</div><!-- /#wrapper-->

<div id="footer">
    <div id="footer-inside" class="clearfix">
    	<div id="footer-left">
    		<div id="footer-left-1">
    			<?php print render($page['footer_left_1']);?>
    		</div>
    		<div id="footer-left-2">
    			<?php print render($page['footer_left_2']);?>
    		</div>
        </div>
        <div id="footer-center">
        	<?php print render($page['footer_center']);?>
        </div>
        <div id="footer-right">
        	<?php print render($page['footer_right']);?>
        </div>
    </div>
</div>

<div id="footer-bottom">
    <div id="footer-bottom-inside" class="clearfix">
    	<div style="float:left">
    		Footer Message
    	</div>
    	<div style="float:right">
	        <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'inline', 'clearfix')))); ?>
    	</div>

</div>

<?php print render($page['page-bottom']);?>

</div><!-- /page-->

