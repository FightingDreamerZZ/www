<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

    <!-- Stylesheets
    ============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/dark.css" type="text/css" />
    <link rel="stylesheet" href="css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/animate.css" type="text/css" />
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />

    <link rel="stylesheet" href="css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <!-- External JavaScripts
    ============================================= -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/plugins.js"></script>

    <!-- Document Title
    ============================================= -->
	<title>Gotex</title>

</head>

<body class="stretched">

    <!-- Document Wrapper
    ============================================= -->
    <div id="wrapper" class="clearfix">

        <div id="home" class="page-section" style="position:absolute;top:0;left:0;width:100%;height:200px;z-index:-2;"></div>

        <section id="slider" class="slider-parallax full-screen with-header swiper_wrapper clearfix">

            <div class="swiper-container swiper-parent">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background-image: url('images/slider-trade-logistics.jpg');">
                        <div class="container clearfix">
                            <div class="slider-caption slider-caption-center">
                                <h2 data-caption-animate="fadeInUp">Experts in trade logistics</h2>
                                <p data-caption-animate="fadeInUp" data-caption-delay="200">We possess the global strength to both sell and purchase a wide variety of import and export products by having affiliated offices positioned throughout the world. The main office is located in Burlington, Ontario, Canada. Many of our traders are native to the countries with which we have business relationships, allowing them to have a better knowledge of the client’s specific country and needs. They communicate fluently in over 6 languages.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url('images/slider-extensive-analysis.jpg');">
                        <div class="container clearfix">
                            <div class="slider-caption slider-caption-center">
                                <h2 data-caption-animate="fadeInUp">Extensive analysis on electric vehicle market</h2>
                                <p data-caption-animate="fadeInUp" data-caption-delay="200">Gotex International Trade helps optimize our client’s supply chain mainly on parts and vehicles, and trace up most advanced suppliers in the global market if the current demanding from our client is not met. By taking all the measures listed below for our customer, Gotex offers a comprehensive industrial analysis on electric vehicle market and connects with superior suppliers and technology teams in advance.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="slider-arrow-left"><i class="icon-angle-left"></i></div>
                <div id="slider-arrow-right"><i class="icon-angle-right"></i></div>
                <div id="slide-number"><div id="slide-number-current"></div><span>/</span><div id="slide-number-total"></div></div>
            </div>

            <script>
                jQuery(document).ready(function($){
                    var swiperSlider = new Swiper('.swiper-parent',{
                        paginationClickable: false,
                        slidesPerView: 1,
                        grabCursor: true,
                        onSwiperCreated: function(swiper){
                            $('[data-caption-animate]').each(function(){
                                var $toAnimateElement = $(this);
                                var toAnimateDelay = $(this).attr('data-caption-delay');
                                var toAnimateDelayTime = 0;
                                if( toAnimateDelay ) { toAnimateDelayTime = Number( toAnimateDelay ) + 750; } else { toAnimateDelayTime = 750; }
                                if( !$toAnimateElement.hasClass('animated') ) {
                                    $toAnimateElement.addClass('not-animated');
                                    var elementAnimation = $toAnimateElement.attr('data-caption-animate');
                                    setTimeout(function() {
                                        $toAnimateElement.removeClass('not-animated').addClass( elementAnimation + ' animated');
                                    }, toAnimateDelayTime);
                                }
                            });
                        },
                        onSlideChangeStart: function(swiper){
                            $('#slide-number-current').html(swiper.activeIndex + 1);
                            $('[data-caption-animate]').each(function(){
                                var $toAnimateElement = $(this);
                                var elementAnimation = $toAnimateElement.attr('data-caption-animate');
                                $toAnimateElement.removeClass('animated').removeClass(elementAnimation).addClass('not-animated');
                            });
                        },
                        onSlideChangeEnd: function(swiper){
                            $('#slider .swiper-slide').each(function(){
                                if($(this).find('video').length > 0) { $(this).find('video').get(0).pause(); }
                            });
                            $('#slider .swiper-slide:not(".swiper-slide-active")').each(function(){
                                if($(this).find('video').length > 0) {
                                    if($(this).find('video').get(0).currentTime != 0 ) $(this).find('video').get(0).currentTime = 0;
                                }
                            });
                            if( $('#slider .swiper-slide.swiper-slide-active').find('video').length > 0 ) { $('#slider .swiper-slide.swiper-slide-active').find('video').get(0).play(); }

                            $('#slider .swiper-slide.swiper-slide-active [data-caption-animate]').each(function(){
                                var $toAnimateElement = $(this);
                                var toAnimateDelay = $(this).attr('data-caption-delay');
                                var toAnimateDelayTime = 0;
                                if( toAnimateDelay ) { toAnimateDelayTime = Number( toAnimateDelay ) + 300; } else { toAnimateDelayTime = 300; }
                                if( !$toAnimateElement.hasClass('animated') ) {
                                    $toAnimateElement.addClass('not-animated');
                                    var elementAnimation = $toAnimateElement.attr('data-caption-animate');
                                    setTimeout(function() {
                                        $toAnimateElement.removeClass('not-animated').addClass( elementAnimation + ' animated');
                                    }, toAnimateDelayTime);
                                }
                            });
                        }
                    });

                    $('#slider-arrow-left').on('click', function(e){
                        e.preventDefault();
                        swiperSlider.swipePrev();
                    });

                    $('#slider-arrow-right').on('click', function(e){
                        e.preventDefault();
                        swiperSlider.swipeNext();
                    });

                    $('#slide-number-current').html(swiperSlider.activeIndex + 1);
                    $('#slide-number-total').html(swiperSlider.slides.length);
                });
            </script>

        </section>

        <!-- Header
        ============================================= -->
        <header id="header" class="full-header">

            <div id="header-wrap">

                <div class="container clearfix">

                    <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

                    <!-- Logo
                    ============================================= -->
                    <div id="logo">
                        <a href="index.html" class="standard-logo" data-dark-logo="images/logo-gotex.png"><img src="images/logo-gotex.png" alt="Gotex Logo"></a>
                        <a href="index.html" class="retina-logo" data-dark-logo="images/logogotex-retina.png"><img src="images/logo-gotex-retina.png" alt="Gotex Logo"></a>
                    </div><!-- #logo end -->

                    <!-- Primary Navigation
                    ============================================= -->
                    <nav id="primary-menu">

                        <ul class="one-page-menu">
                            <li><a href="#" data-href="#home"><div>Home</div></a></li>
                            <li><a href="#" data-href="#section-about"><div>About</div></a></li>
                            <li><a href="#" data-href="#section-partners"><div>Partners</div></a></li>
                            <li><a href="#" data-href="#section-career"><div>Career</div></a></li>
                            <li><a href="#" data-href="#section-contact"><div>Contact</div></a></li>
                        </ul>

                    </nav><!-- #primary-menu end -->

                </div>

            </div>

        </header><!-- #header end -->

        <!-- Content
        ============================================= -->
        <section id="content">

            <div class="content-wrap">

                <section id="section-about" class="page-section">

                    <div class="container clearfix">

                        <div class="heading-block center">
                            <h2>Welcome to <span>Gotex</span></h2>
                            <span>Gotex International Trade Ltd., founded in 2005, is a leading international trading company head quartered in Burlington Ontario. We are always eager to develop new trading partners, while continuing to maintain the valuable relationships we have already established in international trade markets.</span>
                        </div>

                        <div class="col_one_third nobottommargin">
                            <div class="feature-box media-box">
                                <div class="fbox-media">
                                    <img src="images/insert-mission-statement.jpg" alt="Our Mission Statement">
                                </div>
                                <div class="fbox-desc">
                                    <h3>Mission Statement <span class="subtitle">Because we Care</span></h3>
                                    <p style="color:#555;">To fully utilize our knowledge, creativity and collaboration to build relationships that create sustainable growth and profit for our company, and our partners, in the ever changing global marketplace.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col_one_third nobottommargin">
                            <div class="feature-box media-box">
                                <div class="fbox-media">
                                    <img src="images/insert-our-core-value.gif" alt="Our Core Values">
                                </div>
                                <div class="fbox-desc">
                                    <h3>Our Core Values<span class="subtitle">Because we Understand</span></h3>
                                    <p>
                                        <ul>
                                            <li>Entrepreneurial growth oriented</li>
                                            <li>Creative thought</li>
                                            <li>Effective risk free management</li>
                                            <li>Collaborations within and beyond the company</li>
                                            <li>Relationship builders</li>
                                            <li>Stimulating and rewarding work environment</li>
                                            <li>Trustworthy and high level of integrity</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col_one_third col_last nobottommargin">
                            <div class="feature-box media-box">
                                <div class="fbox-media">
                                    <img src="images/insert-our-achievement.png" alt="Our Achievements">
                                </div>
                                <div class="fbox-desc">
                                    <h3>Our Achievements<span class="subtitle">Because we Excel</span></h3>
                                    <p>
                                        <ul>
                                            <li>Dominating the Low Speed Vehicle parts trade in East Asia market for over eight years</li>
                                            <li>Setting up relationship with Suzhou Eagle, the biggest and most successful electric vehicle manufacturer in Asia for over ten years</li>
                                            <li>Leading the international trade event over 5 continents, 30 countries and districts</li>
                                            <li>Competing the busiest and most challenging worldwide logistic operation</li>
                                            <li>The 10,000+ organizations that are linked to Gotex through their partnership in a Gotex partner association represent a broad cross-section of the international trade and business community</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>

                    </div>
                </section>
                
                <section id="section-partners" class="section dark topmargin-lg">

                    <div class="heading-block center bottommargin-lg">
                        <h2>Partners</h2>
                        <span>List of our affiliated partners</span>
                    </div>

                    <div class="container clearfix">

                        <div class="col_one_third nobottommargin">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://agtecars.com/" target="_blank"><img src="images/logo-agt.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>AGT Electric Car</h3>
                                <p>The leading manufacturer of electric vehicles specializing in Low Speed Vehicles, Neighborhood Electric Vehicles, and more...</p>
                            </div>
                        </div>

                        <div class="col_one_third nobottommargin col_last">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="200">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.chinaelectricvehicle.com/" target="_blank"><img src="images/logo-suzhou-eagle.jpg" style="max-height:100%;"/></a>
                                </div>
                                <h3>Suzhou Eagle</h3>
                                <p>Focuses on great quality, and provides the very best services.</p>
                            </div>
                        </div>
                        
                        
                        <div class="col_one_third">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="400">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://usbattery.com/" target="_blank"><img src="images/logo-us-battery.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>US Battery</h3>
                                <p>The World's best made deep-cycle batteries.</p>
                            </div>
                        </div>
                        
                        <div class="clear" style="height:40px;"></div>

                        <div class="col_one_third">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="600">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.trojanbattery.com/" target="_blank"><img src="images/logo-trojan-battery.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>Trojan Battery</h3>
                                <p>The World's leading manufacturer of deep-cycle batteries.</p>
                            </div>
                        </div>

                        <div class="col_one_third col_last">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="800">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.zapiinc.com/" target="_blank"><img src="images/logo-zapi.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>Zapi Controller</h3>
                                <p>World class motor controllers, electric motors, and high frequency battery chargers.</p>
                            </div>
                        </div>

                        <div class="col_one_third">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="1000">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://curtisinstruments.com/" target="_blank"><img src="images/logo-curtis.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>Curtis Controller</h3>
                                <p>You will feel the Curtis difference when you drive it.</p>
                            </div>
                        </div>
                        
                        <div class="clear"></div>

                        <div class="col_one_third">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="1200">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.delta-q.com/" target="_blank"><img src="images/logo-delta-q.png" style="max-height:100%; max-width:70%;"/></a>
                                </div>
                                <h3>Delta-Q Charger</h3>
                                <p>The power of human innovation.</p>
                            </div>
                        </div>

                        <div class="col_one_third col_last">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="1400">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.pyloncn.com/" target="_blank"><img src="images/logo-pylon.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>Pylon Charger</h3>
                                <p>Innovation in Green Power applications</p>
                            </div>
                        </div>
                        
                        <div class="col_one_third nobottommargin">
                            <div class="feature-box fbox-center fbox-effect nobottomborder" data-animate="fadeIn" data-delay="1600">
                                <div class="logo" style="margin:0 auto 30px; height:96px;">
                                    <a href="http://www.schabmueller.de/3539-company.html" target="_blank"><img src="images/logo-schabmuller.png" style="max-height:100%;"/></a>
                                </div>
                                <h3>Schabmüller</h3>
                                <p>Drive solutions made in Germany</p>
                            </div>
                        </div>

                        <div class="clear"></div>

                    </div>

                    <div class="divider divider-short divider-center topmargin-lg"><i class="icon-star3"></i></div>

                </section>
                
                <section id="section-career" class="page-selection">
                
                	<div class="heading-block center bottommargin-lg">
                        <h2>Careers</h2>
                        <span>Start your future career, today!</span>
                    </div>

                    <div class="container clearfix">
                    	<div class="col_full">
                        	<p>We always welcome talented individuals in the areas of engineering, business, management or finance to join our winning team. If you are interested in an exciting and secure career, we encourage you to email us your resume.</p>
                        	<h3>Position(s) Needed: </h3>
                            <p>Coming Soon!</p>
                            <p>Please email your resume and cover letter to <a href="mailto:info@gotex.ca">info@gotex.ca</a></p>
                        </div>
                    </div>
                
                </section>


                <section id="section-contact" class="page-section">

                    <div class="heading-block title-center">
                        <h2>Get in Touch with us</h2>
                        <span>Still have Questions? Contact us via phone or email.</span>
                    </div>

                    <div class="container clearfix">

                        <!-- Google Map
                        ============================================= -->
                        <div class="col_full">

                            <section id="google-map" class="gmap" style="height: 410px;"></section>

                            <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                            <script type="text/javascript" src="js/jquery.gmap.js"></script>

                            <script type="text/javascript">

                                jQuery('#google-map').gMap({

                                    address: '111 Granton Drive,#301, Richmond Hill, Ontario',
                                    maptype: 'ROADMAP',
                                    zoom: 14,
                                    markers: [
                                        {
                                            address: "111 Granton Drive,#301, Richmond Hill, Ontario.",
                                            icon: {
                                                image: "images/icons/map-icon-red.png",
                                                iconsize: [32, 39],
                                                iconanchor: [32,39]
                                            }
                                        }
                                    ],
                                    doubleclickzoom: false,
                                    controls: {
                                        panControl: true,
                                        zoomControl: true,
                                        mapTypeControl: true,
                                        scaleControl: false,
                                        streetViewControl: false,
                                        overviewMapControl: false
                                    }

                                });

                            </script>

                        </div><!-- Google Map End -->

                        <!-- Contact Info
                        ============================================= -->
                        <div class="col_full nobottommargin clearfix">

                            <div class="col_one_third">
                                <div class="feature-box fbox-center fbox-bg fbox-plain">
                                    <div class="fbox-icon">
                                        <a href="https://goo.gl/maps/v2drA" target="_blank"><i class="icon-map-marker2"></i></a>
                                    </div>
                                    <h3>Our Headquarters<span class="subtitle">Unit 301, 111 Granton Drive, Richmond Hill, Ontario, L4B 1L5</span></h3>
                                </div>
                            </div>
                            
                            <div class="col_one_third">
                                <div class="feature-box fbox-center fbox-bg fbox-plain">
                                    <div class="fbox-icon">
                                        <a href="mailto:info@gotex.ca"><i class="icon-email2"></i></a>
                                    </div>
                                    <h3>Contact Us<a href="mailto:info@gotex.ca"><span class="subtitle">info@gotex.ca</span></a></h3>
                                </div>
                            </div>

                            <div class="col_one_third col_last">
                                <div class="feature-box fbox-center fbox-bg fbox-plain">
                                    <div class="fbox-icon">
                                        <a href="tel:9053310491"><i class="icon-phone3"></i></a>
                                    </div>
                                    <h3>Speak to Us<a href="tel:905976227"><span class="subtitle">(905) 597 6227</span></a></h3>
                                </div>
                            </div>

                        </div><!-- Contact Info End -->

                    </div>

                </section>

            </div>

        </section><!-- #content end -->

        <!-- Footer
        ============================================= -->
        <footer id="footer" class="dark">
        
            <!-- Copyrights
            ============================================= -->
            <div id="copyrights">

                <div class="container clearfix">

                    <div class="col_half">
                        Copyrights &copy; <?php echo date('Y');?> All Rights Reserved by <a href="./" title="Gotex">Gotex.Ca</a><br>
                    </div>

                    <div class="col_half col_last tright">
                        <div class="clear"></div>

                        <i class="icon-envelope2"></i> <a href="mailto:info@gotex.ca">info@gotex.ca</a> <span class="middot">&middot;</span> <i class="icon-headphones"></i> <a href="tel:9055976227">905.597.6227</a>
                    </div>

                </div>

            </div><!-- #copyrights end -->

        </footer><!-- #footer end -->

    </div><!-- #wrapper end -->

    <!-- Go To Top
    ============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- Footer Scripts
    ============================================= -->
    <script type="text/javascript" src="js/functions.js"></script>

</body>
</html>