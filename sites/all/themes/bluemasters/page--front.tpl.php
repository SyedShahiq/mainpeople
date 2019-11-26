<script src="misc/jquery.js" type="text/javascript"></script>
<script src="<?php print drupal_get_path('theme', 'bluemasters') . '/js/bluemasters.js'?>" type="text/javascript"></script>

<body>
<div id="page">

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

    <div id="wrapper">

        <div id="header" class="clearfix">

            <div id="logo-floater">
                <h1><a href="<?php print $front_page ?>">
                <?php if ($logo): ?>
                <img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" title="<?php print $site_name_and_slogan ?>" id="logo" /><br/>
                <?php endif; ?>
                <?php print $site_name_and_slogan ?>
                </a></h1>
            </div> <!--EOF:logo-floater-->


            <div id="navigation">
                <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('class' => array('links primary-links', 'inline', 'clearfix')))); ?>
            </div><!--EOF:navigation-->


        </div><!--EOF:header-->

<div id="front-right-container" class="front-right-container">
<div id="front-right" class="front-right">
        <?php print render($page['front_right']);?>
      </div>

<div id="front-right-2" class="front-right-2">
        <?php print render($page['front_right_2']);?>
      </div>
</div>

  <div id="banner" class="clearfix">
    <?php //print render($page['banner']);?>


<div class="main_view">
      <div class="window">
        <div class="image_reel">
          <a href="fairwagemaine"><img src="sites/all/themes/bluemasters/images/fwm.jpg"></a>
          <a href="fairshare"><img src="sites/all/themes/bluemasters/images/fairshare.jpg"></a>
          <a href="environment"><img src="sites/all/themes/bluemasters/images/environment.jpg"></a>
          <a href="healthcare"><img src="sites/all/themes/bluemasters/images/healthcare.jpg"></a>
          <a href="immigration"><img src="sites/all/themes/bluemasters/images/immigration.jpg"></a>
          <a href="workers"><img src="sites/all/themes/bluemasters/images/economy.jpg"></a>
        </div>
        <div class="descriptions">
          <div class="desc" style="display: none;">Raise the Minimum Wage</div>
          <div class="desc" style="display: none;">A Fair Share Economy</div>
          <div class="desc" style="display: none;">The Environment</div>
          <div class="desc" style="display: none;">Health Care</div>
          <div class="desc" style="display: none;">Immigration Reform</div>
          <div class="desc" style="display: none;">Workers' Rights and The Economy</div>
        </div>
      </div>
      <div class="paging" style="display: block;">
        <a rel="1" href="fairwagemaine" class="">1</a>
        <a rel="2" href="fairshare" class="">3</a>
        <a rel="3" href="environment" class="">4</a>
        <a rel="4" href="healthcare" class="">5</a>
        <a rel="5" href="immigration" class="">6</a>
        <a rel="6" href="economy" class="">7</a>
      </div>
    </div>
    <br>
  </div><!--EOF:banner-->

  <div id="slide-navigation"></div>

  <div id="home-blocks-area" class="clearfix">
    <div id="home-block-1" class="home-block">
        <?php print render($page['home_area_1']);?>
      </div>
      <div id="home-block-2" class="home-block">
        <?php print render($page['home_area_2']);?>
      </div>
      <div id="home-block-3" class="home-block">
        <?php print render($page['home_area_3']);?>
        <div id="home-block-3-b">
          <?php print render($page['home_area_3_b']);?>
        </div>
      </div>
  </div>

</div><!--EOF:wrapper-->

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
        Founded, 1982
      </div>
      <div style="float:right;">
    <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'inline', 'clearfix')))); ?>
      </div>

</div>

<?php print render($page['page-bottom']);?>

</div><!-- /page-->

</body>
