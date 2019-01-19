var wooFile = true;
//Functions WOO

(function($) {
    "use strict";
    
    var woodmartTheme = {
        popupEffect: 'mfp-move-horizontal',
        supports_html5_storage: false,
        shopLoadMoreBtn: '.woodmart-products-load-more.load-on-scroll',
        ajaxLinks: '.woodmart-product-categories a, .widget_product_categories a, .widget_layered_nav_filters a, .woocommerce-widget-layered-nav a, .filters-area:not(.custom-content) a, body.post-type-archive-product:not(.woocommerce-account) .woocommerce-pagination a, body.tax-product_cat:not(.woocommerce-account) .woocommerce-pagination a, .woodmart-shop-tools a, .woodmart-woocommerce-layered-nav a, .woodmart-price-filter a, .woodmart-clear-filters-wrapp a, .woodmart-woocommerce-sort-by a',
        mainCarouselArg : {
            rtl: $('body').hasClass('rtl'),
            items: woodmart_settings.product_gallery.thumbs_slider.position == 'centered' ? 2 : 1,
            autoplay: woodmart_settings.product_slider_autoplay,
            autoplayTimeout: 3000,
            loop: woodmart_settings.product_slider_autoplay,
            center: woodmart_settings.product_gallery.thumbs_slider.position == 'centered',
            startPosition: woodmart_settings.product_gallery.thumbs_slider.position == 'centered' ? woodmart_settings.centered_gallery_start : 0,
            dots: false,
            nav: true,
            autoHeight: woodmart_settings.product_slider_auto_height == 'yes',
            navText: false,
            onRefreshed: function () {
                $(window).resize();
            }   
        }
    };

    /* Storage Handling */
    try {
        woodmartTheme.supports_html5_storage = ('sessionStorage' in window && window.sessionStorage !== null);

        window.sessionStorage.setItem('woodmart', 'test');
        window.sessionStorage.removeItem('woodmart');
    } catch (err) {
        woodmartTheme.supports_html5_storage = false;
    }

    /**
    *-------------------------------------------------------------------------------------------------------------------------------------------
    * Product filters
    *-------------------------------------------------------------------------------------------------------------------------------------------
    */

    woodmartThemeModule.productFilters = function () {
        //Select checkboxes value
        var removeValue = function ($mainInput, currentVal) {
            if ($mainInput.length == 0) return;
            var mainInputVal = $mainInput.val();
            if (mainInputVal.indexOf(',') > 0) {
                $mainInput.val(mainInputVal.replace(',' + currentVal, '').replace(currentVal + ',', ''));
            } else {
                $mainInput.val(mainInputVal.replace(currentVal, ''));
            }
        }

        $('.woodmart-pf-checkboxes li > .pf-value').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var $li = $this.parent();
            var $widget = $this.parents('.woodmart-pf-checkboxes');
            var $mainInput = $widget.find('.result-input');
            var $results = $widget.find('.woodmart-pf-results');

            var multiSelect = $widget.hasClass('multi_select');
            var mainInputVal = $mainInput.val();
            var currentText = $this.data('title');
            var currentVal = $this.data('val');

            if (multiSelect) {
                if (!$li.hasClass('pf-active')) {
                    if (mainInputVal == '') {
                        $mainInput.val(currentVal);
                    } else {
                        $mainInput.val(mainInputVal + ',' + currentVal);
                    }
                    $results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
                    $li.addClass('pf-active');
                } else {
                    removeValue($mainInput, currentVal);
                    $results.find('li[data-title="' + currentVal + '"]').remove();
                    $li.removeClass('pf-active');
                }
            } else {
                if (!$li.hasClass('pf-active')) {
                    $mainInput.val(currentVal);
                    $results.find('.selected-value').remove();
                    $results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
                    $li.parents('.woodmart-scroll-content').find('.pf-active').removeClass('pf-active');
                    $li.addClass('pf-active');
                } else {
                    $mainInput.val('');
                    $results.find('.selected-value').remove();
                    $li.removeClass('pf-active');
                }
            }
        });

        //Label clear
        $('.woodmart-pf-checkboxes').on('click', '.selected-value', function () {
            var $this = $(this);
            var $widget = $this.parents('.woodmart-pf-checkboxes');
            var $mainInput = $widget.find('.result-input');
            var currentVal = $this.data('title');

            //Price filter clear
            if (currentVal == 'price-filter') {
                var min = $this.data('min');
                var max = $this.data('max');
                var $slider = $widget.find('.price_slider_widget');
                $slider.slider('values', 0, min);
                $slider.slider('values', 1, max);
                $widget.find('.min_price').val('');
                $widget.find('.max_price').val('');
                $(document.body).trigger('filter_price_slider_slide', [min, max, min, max, $slider]);
                return;
            }

            removeValue($mainInput, currentVal);
            $widget.find('.pf-value[data-val="' + currentVal + '"]').parent().removeClass('pf-active');
            $this.remove();
        });

        //Checkboxes value dropdown
        $('.woodmart-pf-checkboxes').each(function () {
            var $this = $(this);
            var $btn = $this.find('.woodmart-pf-title');
            var $list = $btn.siblings('.woodmart-pf-dropdown');
            var multiSelect = $this.hasClass('multi_select');

            $btn.click(function (e) {
                var target = e.target;
                if ($(target).is($btn.find('.selected-value'))) return;

                if (!$this.hasClass('opened')) {
                    $this.addClass('opened');
                    $list.slideDown(100);
                    setTimeout(function () {
                        woodmartThemeModule.nanoScroller();
                    }, 300);
                } else {
                    close();
                }
            });

            $(document).click(function (e) {
                var target = e.target;
                if ($this.hasClass('opened') && (multiSelect && !$(target).is($this) && !$(target).parents().is($this)) || (!multiSelect && !$(target).is($btn) && !$(target).parents().is($btn)) ) {
                    close();
                }
            });

            var close = function () {
                $this.removeClass('opened');
                $list.slideUp(100);
            }
        });

        var removeEmptyValues = function ($selector) {
            $selector.find('.woodmart-pf-checkboxes').each(function () {
                if (!$(this).find('input[type="hidden"]').val()) {
                    $(this).find('input[type="hidden"]').remove();
                }
            });
        }

        var changeFormAction = function ($form) {
            var activeCat = $form.find('.woodmart-pf-categories .pf-active .pf-value');
            if (activeCat.length > 0) {
                $form.attr('action', activeCat.attr('href'));
            }
        }

        //Price slider init
        $(document.body).bind('filter_price_slider_create filter_price_slider_slide', function (event, min, max, minPrice, maxPrice, $slider) {
            var minHtml = accounting.formatMoney(min, {
                symbol: woocommerce_price_slider_params.currency_format_symbol,
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            });

            var maxHtml = accounting.formatMoney(max, {
                symbol: woocommerce_price_slider_params.currency_format_symbol,
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            });

            $slider.siblings('.filter_price_slider_amount').find('span.from').html(minHtml);
            $slider.siblings('.filter_price_slider_amount').find('span.to').html(maxHtml);

            var $results = $slider.parents('.woodmart-pf-checkboxes').find('.woodmart-pf-results');
            var value = $results.find('.selected-value');
            if (min == minPrice && max == maxPrice) {
                value.remove();
            } else {
                if (value.length == 0) {
                    $results.prepend('<li class="selected-value" data-title="price-filter" data-min="' + minPrice + '" data-max="' + maxPrice + '">' + minHtml + ' - ' + maxHtml + '</li>');
                } else {
                    value.html(minHtml + ' - ' + maxHtml);
                }
            }

            $(document.body).trigger('price_slider_updated', [min, max]);
        });

        $('.woodmart-pf-price-range .price_slider_widget').each(function () {
            var $this = $(this);
            var $minInput = $this.siblings('.filter_price_slider_amount').find('.min_price');
            var $maxInput = $this.siblings('.filter_price_slider_amount').find('.max_price');
            var minPrice = parseInt($minInput.data('min'));
            var maxPrice = parseInt($maxInput.data('max'));
            var currentMinPrice = parseInt($minInput.val());
            var currentMaxPrice = parseInt($maxInput.val());

            $('.price_slider_widget, .price_label').show();

            $this.slider({
                range: true,
                animate: true,
                min: minPrice,
                max: maxPrice,
                values: [currentMinPrice, currentMaxPrice],
                create: function () {
                    if (currentMinPrice == minPrice && currentMaxPrice == maxPrice) {
                        $minInput.val('');
                        $maxInput.val('');
                    }
                    $(document.body).trigger('filter_price_slider_create', [currentMinPrice, currentMaxPrice, minPrice, maxPrice, $this]);
                },
                slide: function (event, ui) {
                    if (ui.values[0] == minPrice && ui.values[1] == maxPrice) {
                        $minInput.val('');
                        $maxInput.val('');
                    } else {
                        $minInput.val(ui.values[0]);
                        $maxInput.val(ui.values[1]);
                    }
                    $(document.body).trigger('filter_price_slider_slide', [ui.values[0], ui.values[1], minPrice, maxPrice, $this]);
                },
                change: function (event, ui) {
                    $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                }
            });
        });

        //Submit filter form
        $('.woodmart-product-filters').one('click', '.woodmart-pf-btn button', function (e) {
            var $form = $(this).parents('.woodmart-product-filters');
            removeEmptyValues($form);
            changeFormAction($form);

            if (typeof ($.fn.pjax) == 'undefined') return;
            $.pjax({
                container: '.main-page-wrapper',
                timeout: woodmart_settings.pjax_timeout,
                url: $form.attr('action'),
                data: $form.serialize(),
                scrollTo: false
            });
            $(this).prop('disabled', true);
        });

        //Create labels after ajax
        $('.woodmart-pf-checkboxes .pf-active > .pf-value').each(function () {
            var resultsWrapper = $(this).parents('.woodmart-pf-checkboxes').find('.woodmart-pf-results');
            resultsWrapper.prepend('<li class="selected-value" data-title="' + $(this).data('val') + '">' + $(this).data('title') + '</li>');
        });

    };

    /**
    *-------------------------------------------------------------------------------------------------------------------------------------------
    * Sticky sidebar button
    *-------------------------------------------------------------------------------------------------------------------------------------------
    */

    woodmartThemeModule.stickySidebarBtn = function () {
        var $trigger = $('.woodmart-show-sidebar-btn');
        var $stickyBtn = $('.shop-sidebar-opener');

        if ($stickyBtn.length <= 0 || $trigger.length <= 0 || $(window).width() >= 1024) return;

        var stickySidebarBtnToggle = function () {
            var btnOffset = $trigger.offset().top + $trigger.outerHeight();
            var windowScroll = $(window).scrollTop();

            if (btnOffset < windowScroll) {
                $stickyBtn.addClass('woodmart-sidebar-btn-shown');
            } else {
                $stickyBtn.removeClass('woodmart-sidebar-btn-shown');
            }
        };

        stickySidebarBtnToggle();

        $(window).scroll(stickySidebarBtnToggle);
        $(window).resize(stickySidebarBtnToggle);
    };

    /**
    *-------------------------------------------------------------------------------------------------------------------------------------------
    * Product thumbnail images & photo swipe gallery
    *-------------------------------------------------------------------------------------------------------------------------------------------
    */

    woodmartThemeModule.productImages = function () {
        // Init photoswipe

        var currentImage,
            $productGallery = $('.woocommerce-product-gallery'),
            $mainImages = $('.woocommerce-product-gallery__wrapper'),
            $thumbs = $productGallery.find('.thumbnails'),
            currentClass = 'current-image',
            PhotoSwipeTrigger = '.woodmart-show-product-gallery';

        $thumbs.addClass('thumbnails-ready');

        if ($productGallery.hasClass('image-action-popup')) {
            PhotoSwipeTrigger += ', .woocommerce-product-gallery__image a';
        }

        $productGallery.on('click', '.woocommerce-product-gallery__image a', function (e) {
            e.preventDefault();
        });

        $productGallery.on('click', PhotoSwipeTrigger, function (e) {
            e.preventDefault();

            currentImage = $(this).attr('href');

			// build items array
			var items = getProductItems();

			woodmartThemeModule.callPhotoSwipe(getCurrentGalleryIndex(e), items);

        });

        $thumbs.on('click', '.image-link', function (e) {
            e.preventDefault();

            // if( $thumbs.hasClass('thumbnails-large') ) {
            //     var index = $(e.currentTarget).index() + 1;
            //     var items = getProductItems();
            //     callPhotoSwipe(index, items);
            //     return;
            // }

            // var href = $(this).attr('href'),
            //     src  = $(this).attr('data-single-image'),
            //     width = $(this).attr('data-width'),
            //     height = $(this).attr('data-height'),
            //     title = $(this).attr('title');

            // $thumbs.find('.' + currentClass).removeClass(currentClass);
            // $(this).addClass(currentClass);

            // if( $mainImages.find('img').attr('src') == src ) return;

            // $mainImages.addClass('loading-image').attr('href', href).find('img').attr('src', src).attr('srcset', src).one('load', function() {
            //     $mainImages.removeClass('loading-image').data('width', width).data('height', height).attr('title', title);
            // });

        });

        var getCurrentGalleryIndex = function (e) {
            if ($mainImages.hasClass('owl-carousel'))
                return $mainImages.find('.owl-item.active').index();
            else return $(e.currentTarget).parent().parent().index();
        };

        var getProductItems = function () {
            var items = [];

            $mainImages.find('figure a img').each(function () {
                var src = $(this).attr('data-large_image'),
                    width = $(this).attr('data-large_image_width'),
                    height = $(this).attr('data-large_image_height'),
                    caption = $(this).data('caption');

                items.push({
                    src: src,
                    w: width,
                    h: height,
                    title: (woodmart_settings.product_images_captions == 'yes') ? caption : false
                });

            });

            return items;
        };

        /* Fix zoom for first item firstly */

        if ($productGallery.hasClass('image-action-zoom')) {
            var zoom_target = $('.woocommerce-product-gallery__image');
            var image_to_zoom = zoom_target.find('img');

            // But only zoom if the img is larger than its container.
            if (image_to_zoom.attr('width') > $('.woocommerce-product-gallery').width()) {
                zoom_target.trigger('zoom.destroy');
                zoom_target.zoom({
                    touch: false
                });
            }
        }
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Sticky add to cart
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.stickyAddToCart = function () {
        var $trigger = $('.summary-inner .cart');
        var $stickyBtn = $('.woodmart-sticky-btn');

        if ($stickyBtn.length <= 0 || $trigger.length <= 0 || ($(window).width() <= 768 && $stickyBtn.hasClass('mobile-off'))) return;

        var summaryOffset = $trigger.offset().top + $trigger.outerHeight();
        var $scrollToTop = $('.scrollToTop');

        var stickyAddToCartToggle = function () {
            var windowScroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            var documentHeight = $(document).height();

            if (summaryOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
                $stickyBtn.addClass('woodmart-sticky-btn-shown');
                $scrollToTop.addClass('woodmart-sticky-btn-shown');

            } else if (windowScroll + windowHeight == documentHeight || summaryOffset > windowScroll) {
                $stickyBtn.removeClass('woodmart-sticky-btn-shown');
                $scrollToTop.removeClass('woodmart-sticky-btn-shown');
            }
        };

        stickyAddToCartToggle();

        $(window).scroll(stickyAddToCartToggle);

        $('.woodmart-sticky-add-to-cart').click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.summary-inner').offset().top
            }, 800);
        });

        $('.woodmart-sticky-btn-wishlist').click(function (e) {
            if (!$(this).hasClass('exists')) e.preventDefault();
            $('.summary-inner > .yith-wcwl-add-to-wishlist .add_to_wishlist').trigger('click');
        });

        $('body').on('added_to_wishlist', function () {
            $('.woodmart-sticky-btn-wishlist').addClass('exists');
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Login dropdown
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
     
    woodmartThemeModule.loginDropdown = function() {
        if ($(window).width() <= 1024) return;

        $( '.menu-item-register:not(.login-side-opener)' ).on( 'mouseover', function () {
            $( this ).addClass( 'opened' );
        } ).on( 'mouseout', function ( event ) {
            if ( !$( event.target ).is( 'input' ) ) {
                $( this ).removeClass( 'opened' );
            }
        } ).on( 'mouseleave', function () {
            var $this = $( this );
            setTimeout( function(){
                $this.removeClass( 'opened' );
            }, 300 );
        } );
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Login sidebar
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.loginSidebar = function() {
        var body = $( 'body' );
        var loginFormSide = $('.login-form-side');
        var closeSide = $('.woodmart-close-side');
        
        $( '.login-side-opener' ).on( 'click', function ( e ) {
            e.preventDefault();
            if( isOpened() ) {
                closeWidget();
            } else {
                setTimeout( function() {
                    openWidget();
                }, 10);
            }
        });
        
        body.on( 'click touchstart', '.woodmart-close-side', function() {
            if( isOpened() ) closeWidget();
        });

        body.on( 'click', '.close-side-widget', function( e ) {
            e.preventDefault();
            if( isOpened() ) closeWidget();
        });

        $(document).keyup(function (e) {
            if (e.keyCode === 27 && isOpened()) closeWidget();
        });
        
        var closeWidget = function() {
            loginFormSide.removeClass( 'woodmart-login-side-opened' );
            closeSide.removeClass('woodmart-close-side-opened');
        };

        var openWidget = function() {
            loginFormSide.addClass( 'woodmart-login-side-opened' );
            closeSide.addClass('woodmart-close-side-opened');
        };
        
        var isOpened = function() {
            return loginFormSide.hasClass( 'woodmart-login-side-opened' );
        };
    };
    
    /**
    *-------------------------------------------------------------------------------------------------------------------------------------------
    * Shop loader position
    *-------------------------------------------------------------------------------------------------------------------------------------------
    */
    
    woodmartThemeModule.shopLoader = function() {
        var loaderClass = '.woodmart-shop-loader',
            contentClass = '.products[data-source="main_loop"]',
            sidebarClass = '.area-sidebar-shop',
            sidebarLeftClass = 'sidebar-left',
            hiddenClass = 'hidden-loader',
            hiddenTopClass = 'hidden-from-top',
            hiddenBottomClass = 'hidden-from-bottom';

        var loaderVerticalPosition = function() {
            var $products = $(contentClass),
                $loader = $products.parent().find(loaderClass);

            if ( $products.length < 1 ) return;

            var offset = $(window).height() / 2,
                scrollTop = $(window).scrollTop(),
                holderTop = $products.offset().top - offset,
                holderHeight = $products.height(),
                holderBottom = holderTop + holderHeight - 130;
                
            if (scrollTop < holderTop) {
                $loader.addClass(hiddenClass + ' ' + hiddenTopClass);
            } else if( scrollTop > holderBottom ) {
                $loader.addClass(hiddenClass + ' ' + hiddenBottomClass);
            } else {
                $loader.removeClass(hiddenClass + ' ' + hiddenTopClass + ' ' + hiddenBottomClass);
            }
        };

        var loaderHorizontalPosition = function () {
            var $products = $(contentClass),
                $sidebar = $(sidebarClass),
                $loader = $products.parent().find(loaderClass),
                sidebarWidth = $sidebar.outerWidth();

            if ( $products.length < 1 ) return;

            if( sidebarWidth > 0 && $sidebar.hasClass(sidebarLeftClass) ) {
                if ( $('body').hasClass('rtl') ) {
                    $loader.css({
                        'marginLeft': - sidebarWidth / 2 - 15
                    })
                }else{
                    $loader.css({
                        'marginLeft': sidebarWidth / 2 - 15
                    })
                }
            } else if( sidebarWidth > 0 ) {
                if ( $('body').hasClass('rtl') ) {
                    $loader.css({
                        'marginLeft': sidebarWidth / 2 - 15
                    })
                }else{
                    $loader.css({
                        'marginLeft': - sidebarWidth / 2 - 15
                    })
                }
            }

        };

        $(window).off('scroll.loaderVerticalPosition');
        $(window).off('loaderHorizontalPosition');

        $(window).on('scroll.loaderVerticalPosition', loaderVerticalPosition);
        $(window).on('resize.loaderHorizontalPosition', loaderHorizontalPosition);

        loaderVerticalPosition();
        loaderHorizontalPosition();
    };
    
	/**
	*-------------------------------------------------------------------------------------------------------------------------------------------
	* "Sort by" widget reinit
	*-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	woodmartThemeModule.sortByWidget = function() {
		if (typeof ($.fn.pjax) == 'undefined') return;
		
		$('.woocommerce-ordering').on('change', 'select.orderby', function () {
			var $form = $(this).closest('form');

			$form.find('[name="_pjax"]').remove();

			$.pjax({
				container: '.main-page-wrapper',
				timeout: woodmart_settings.pjax_timeout,
				url: '?' + $form.serialize(),
				scrollTo: false
			});
		});

		$('.woocommerce-ordering').submit(function (e) {
			e.preventDefault(e);
		});
	};

	/**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Add filters dropdowns compatibility
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
     
    woodmartThemeModule.filterDropdowns = function() {
        $('.woodmart-woocommerce-layered-nav').on('change', 'select', function() {
            var slug = $( this ).val();

            var href = $(this).data('filter-url').replace('WOODMART_FILTER_VALUE', slug);

            $(this).siblings('.filter-pseudo-link').attr('href', href);

             var event;
             var pseudoLink = $(this).siblings('.filter-pseudo-link');

             //This is true only for IE,firefox
             if(document.createEvent){
             // To create a mouse event , first we need to create an event and then initialize it.
                event = document.createEvent("MouseEvent");
                event.initMouseEvent("click",true,true,window,0,0,0,0,0,false,false,false,false,0,null);
             }
             else{
                event = new MouseEvent('click', {
                    'view': window,
                    'bubbles': true,
                    'cancelable': true
                });
             }

             pseudoLink[0].dispatchEvent(event);
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Sticky details block for special product type
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.stickyDetails = function() {
        if( 
            ! $('body').hasClass('woodmart-product-sticky-on') 
            || $( window ).width() <= 1024
        ) return;

        var details = $('.entry-summary');


        details.each(function() {
            var $column = $(this),
                offset = 40,
                $inner = $column.find('.summary-inner'),
                $images = $column.parent().find('.product-images-inner');

            if( $('body').hasClass('enable-sticky-header') || $('.whb-sticky-row').length > 0 || $('.whb-sticky-header').length > 0 ) {
                offset = 150;
            }

            $images.imagesLoaded(function() {
                var diff = $inner.outerHeight() - $images.outerHeight();

                if( diff < -100 ) {
                    $inner.stick_in_parent({
                        offset_top: offset
                    });
                } else if( diff > 100 ) {
                    $images.stick_in_parent({
                        offset_top: offset
                    });
                }

                $( window ).resize(function() {
                    
                    if ( $( window ).width() <= 1024 ) {
                        $inner.trigger('sticky_kit:detach');
                        $images.trigger('sticky_kit:detach');
                    }else if( $inner.outerHeight() < $images.outerHeight() ) {
                        $inner.stick_in_parent({
                            offset_top: offset
                        });
                    }else{
                        $images.stick_in_parent({
                            offset_top: offset
                        });
                    }

                }); 
            });

        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product accordion
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.productAccordion = function() {
        var $accordion = $('.wc-tabs-wrapper');

        var time = 300;

        var hash  = window.location.hash;
        var url   = window.location.href;
    
        if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' ) {
            $accordion.find('.tab-title-reviews').addClass('active');
        } else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
            $accordion.find('.tab-title-reviews').addClass('active');
        } else {
            $accordion.find('.woodmart-accordion-title').first().addClass('active');
        }

        $accordion.on('click', '.woodmart-accordion-title', function( e ) {
            e.preventDefault();
                
            var $this = $(this),
                $panel = $this.siblings('.woocommerce-Tabs-panel');
                
            var curentIndex = $this.parent().index();
            var oldIndex = $this.parent().siblings().find('.active').parent('.woodmart-tab-wrapper').index();
            
            if( $this.hasClass('active') ) {
                oldIndex = curentIndex;
                $this.removeClass('active');
                $panel.stop().slideUp(time);
            } else {
                $accordion.find('.woodmart-accordion-title').removeClass('active');
                $accordion.find('.woocommerce-Tabs-panel').slideUp();
                $this.addClass('active');
                $panel.stop().slideDown(time);
            }
            
            if ( oldIndex == -1 ) oldIndex = curentIndex;

            $(window).resize();
            
            setTimeout( function() {
                $(window).resize();
                if ( $( window ).width() < 1024 && curentIndex > oldIndex ) {
                    $('html, body').animate({
                        scrollTop: $this.offset().top - $this.outerHeight() - $('.sticky-header').outerHeight() - 50
                    }, 500);
                }   
            }, time);

        } );
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product thumbnail images & photo swipe gallery
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.productImagesGallery = function() {
        var $mainImages = $('.woocommerce-product-gallery__image:eq(0) img'),
            $thumbs = $('.images .thumbnails'), // magnific photo-swipe
            $mainOwl = $('.woocommerce-product-gallery__wrapper'),
            thumbs_position = woodmart_settings.product_gallery.thumbs_slider.position;

        // if (woodmart_settings.product_gallery.thumbs_slider.position == 'centered' && $mainOwl.find('.product-image-wrap').length > 1) {
        //     var moveblock = $mainOwl.find('.product-image-wrap:eq(0)');
        //     moveblock.remove();
        //     $mainOwl.find('.product-image-wrap:eq(0)').after(moveblock);
        // }

        if( woodmart_settings.product_gallery.images_slider ) {
            if ( woodmart_settings.product_slider_auto_height == 'yes' ) {
                $('.product-images').imagesLoaded(function() {
                    initMainGallery();
                });
            }else{
                initMainGallery();
            }
        }else if ( jQuery(window).width() <= 1024 && ( thumbs_position == 'bottom_combined' || thumbs_position == 'bottom_column' || thumbs_position == 'bottom_grid' ) ) {
            initMainGallery();
        }

        if( woodmart_settings.product_gallery.thumbs_slider.enabled && woodmart_settings.product_gallery.images_slider ) {
            initThumbnailsMarkup();
            if( woodmart_settings.product_gallery.thumbs_slider.position == 'left' && jQuery(window).width() > 1024 && typeof( $.fn.slick ) != 'undefined' ) {
                initThumbnailsVertical();
            } else {
                initThumbnailsHorizontal();
            }
		}
		
        function initMainGallery() {
			$('.woocommerce-product-gallery__wrapper').trigger('destroy.owl.carousel');
			$('.woocommerce-product-gallery__wrapper').addClass('owl-carousel').owlCarousel(woodmartTheme.mainCarouselArg);
			$(document).trigger('wood-images-loaded');
        };

        function initThumbnailsMarkup() {
            var markup = '';

            $('.woocommerce-product-gallery__image').each(function() {
                var image = $( this ).data( 'thumb' ),
					alt = $(this).find('a > img').attr('alt'),
					title = $(this).find('a > img').attr('title');

                markup += '<div class="product-image-thumbnail"><img alt="' + alt + '" title="' + title + '" src="' + image + '" /></div>';
			});

			if ($thumbs.hasClass('slick-slider')) {
				$thumbs.slick('unslick');
			} else if ($thumbs.hasClass('owl-carousel')) {
				$thumbs.trigger('destroy.owl.carousel');
			}
			
			$thumbs.empty();
			$thumbs.append(markup);
		};
		
        function initThumbnailsVertical() {
            $thumbs.slick({
                slidesToShow: woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
                slidesToScroll: woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
                vertical: true,
                verticalSwiping: true,
                infinite: false,
            });

            $thumbs.on('click', '.product-image-thumbnail', function(e) {
                var i = $(this).index();
                $mainOwl.trigger('to.owl.carousel', i);
            });

            $mainOwl.on('changed.owl.carousel', function(e) {
                var i = e.item.index;
                $thumbs.slick('slickGoTo', i);
                $thumbs.find('.active-thumb').removeClass('active-thumb');
                $thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
            });
            
            $thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
        };

        function initThumbnailsHorizontal() {
            $thumbs.addClass('owl-carousel').owlCarousel({
                rtl: $('body').hasClass('rtl'),
                items: woodmart_settings.product_gallery.thumbs_slider.items.desktop,
                responsive: {
                    1025: {
                        items: woodmart_settings.product_gallery.thumbs_slider.items.desktop
                    },
                    769: {
                        items: woodmart_settings.product_gallery.thumbs_slider.items.tablet_landscape
                    },
                    577: {
                        items: woodmart_settings.product_gallery.thumbs_slider.items.tablet
                    },
                    0: {
                        items: woodmart_settings.product_gallery.thumbs_slider.items.mobile
                    }
                },
                dots:false,
                nav: true,
                // mouseDrag: false,
                navText: false,
            });

            var $thumbsOwl = $thumbs.owlCarousel();

            $thumbs.on('click', '.owl-item', function(e) {
                var i = $(this).index();
                $thumbsOwl.trigger('to.owl.carousel', i);
                $mainOwl.trigger('to.owl.carousel', i);
            });

            $mainOwl.on('changed.owl.carousel', function(e) {
                var i = e.item.index;
                $thumbsOwl.trigger('to.owl.carousel', i);
                $thumbs.find('.active-thumb').removeClass('active-thumb');
                $thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
            });

            $thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
        };

        // Update first thumbnail on variation change
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * WooCommerce adding to cart
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.addToCart = function() {
        var that = this,
            timeoutNumber = 0;

        that.addToCartAllTypes();

        $('body').bind('added_to_cart', function(event, fragments, cart_hash) {

            if( woodmart_settings.add_to_cart_action == 'popup' ) {

                var html = [
                    '<div class="added-to-cart">',
                        '<h4>' + woodmart_settings.added_to_cart + '</h4>',
                        '<a href="#" class="btn btn-style-link btn-color-default close-popup">' + woodmart_settings.continue_shopping + '</a>',
                        '<a href="' + woodmart_settings.cart_url + '" class="btn btn-color-primary view-cart">' + woodmart_settings.view_cart + '</a>',
                    '</div>',
                ].join("");

                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation
                    tClose: woodmart_settings.close,
                    tLoading: woodmart_settings.loading,
                    callbacks: {
                        beforeOpen: function() {
                            this.st.mainClass = woodmartTheme.popupEffect + '  cart-popup-wrapper';
                        },
                    },
                    items: {
                        src: '<div class="mfp-with-anim white-popup popup-added_to_cart">' + html + '</div>',
                        type: 'inline'
                    }
                });

                $('.white-popup').on('click', '.close-popup', function(e) {
                    e.preventDefault();
                    $.magnificPopup.close();
                });

            } else if( woodmart_settings.add_to_cart_action == 'widget' ) {
                
                clearTimeout(timeoutNumber);

                if( $('.act-scroll .woodmart-shopping-cart, .whb-sticked .woodmart-shopping-cart').length > 0 ) {
                    $('.act-scroll .woodmart-shopping-cart, .whb-sticked .woodmart-shopping-cart').addClass('display-widget');
                } else {
                    $('.whb-header .woodmart-shopping-cart').addClass('display-widget');
                }

                if( $('.cart-widget-opener').length > 0 ) {
                    $('.cart-widget-opener').trigger('click');
                }
                
                timeoutNumber = setTimeout(function() {
                     $('.display-widget').removeClass('display-widget');
                }, 3500 );
            }

            that.btnsToolTips();

        });
    },
    
    woodmartThemeModule.addToCartAllTypes = function() {
        if( woodmart_settings.ajax_add_to_cart == false ) return;
        // AJAX add to cart for all types of products

        $('body').on('submit', 'form.cart', function(e) {
            var $productWrapper = $(this).parents('.single-product-page');
            if ($productWrapper.hasClass('product-type-external') || $productWrapper.hasClass('product-type-zakeke')) return;

            e.preventDefault();

            var $form = $(this),
                $thisbutton = $form.find('.button'),
                data = $form.serialize();

            data += '&action=woodmart_ajax_add_to_cart';

            if( $thisbutton.val() ) {
                data += '&add-to-cart=' + $thisbutton.val();
            }

            $thisbutton.removeClass( 'added not-added' );
            $thisbutton.addClass( 'loading' );

            // Trigger event
            $( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

            $.ajax({
                url: woodmart_settings.ajaxurl,
                data: data,
                method: 'POST',
                success: function(response) {
                    if ( ! response ) {
                        return;
                    }

                    var this_page = window.location.toString();

                    this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

                    if ( response.error && response.product_url ) {
                        window.location = response.product_url;
                        return;
                    }

                    // Redirect to cart option
                    if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {

                        window.location = wc_add_to_cart_params.cart_url;
                        return;

                    } else {

                        $thisbutton.removeClass( 'loading' );

                        var fragments = response.fragments;
                        var cart_hash = response.cart_hash;


                        // Block fragments class
                        if ( fragments ) {
                            $.each( fragments, function( key ) {
                                $( key ).addClass( 'updating' );
                            });
                        }

                        // Replace fragments
                        if ( fragments ) {
                            $.each( fragments, function( key, value ) {
                                $( key ).replaceWith( value );
                            });
                        }

                        // Show notices
                        if( response.notices.indexOf( 'error' ) > 0 ) {
                            $('body').append(response.notices);
                            $thisbutton.addClass( 'not-added' );
                        } else {
                            if( woodmart_settings.add_to_cart_action == 'widget' )
                                $.magnificPopup.close();

                            // Changes button classes
                            $thisbutton.addClass( 'added' );
                            // Trigger event so themes can refresh other areas
                            $( document.body ).trigger( 'added_to_cart', [ fragments, cart_hash, $thisbutton ] );
                        }

                    }
                },
                error: function() {
                    console.log('ajax adding to cart error');
                },
                complete: function() {},
            });

        });
    };
    
    woodmartThemeModule.updateWishListNumberInit = function() {
        if (woodmart_settings.wishlist == 'no' || $('.wishlist-count').length <= 0 ) return;

        var that = this;

        if ( woodmartTheme.supports_html5_storage ) {

            try {
                var wishlistNumber = sessionStorage.getItem( 'woodmart_wishlist_number' ),
                    cookie_hash = Cookies.get( 'woodmart_wishlist_hash');


                if ( wishlistNumber === null || wishlistNumber === undefined || wishlistNumber === '' ) {
                    wishlistNumber = 0;
                }

                if ( cookie_hash === null || cookie_hash === undefined || cookie_hash === '' ) {
                    cookie_hash = 0;
                }

                if ( wishlistNumber == cookie_hash ) {
                    this.setWishListNumber(wishlistNumber);
                } else {
                    throw 'No wishlist number';
                }

            } catch( err ) {
                this.updateWishListNumber();
            }

        } else {
            this.updateWishListNumber();
        }

        $('body').bind('added_to_cart added_to_wishlist removed_from_wishlist', function() {
            that.updateWishListNumber();
            that.btnsToolTips();
            that.woocommerceWrappTable();
        });
    };
    
    woodmartThemeModule.updateCartWidgetFromLocalStorage = function() {
        var that = this;

        if ( woodmartTheme.supports_html5_storage ) {

            try {
                var wc_fragments = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );

                if ( wc_fragments && wc_fragments['div.widget_shopping_cart_content'] ) {

                    $.each( wc_fragments, function( key, value ) {
                        $( key ).replaceWith(value);
                    });

                    $( document.body ).trigger( 'wc_fragments_loaded' );
                } else {
                    throw 'No fragment';
                }

            } catch( err ) {
                console.log('cant update cart widget');
            }
        }
    };
    
    woodmartThemeModule.updateWishListNumber = function() {
        var that = this;
        $.ajax({
            url: woodmart_settings.ajaxurl,
            data: {
                action: 'woodmart_wishlist_number'
            },
            method: 'get',
            success: function(data) {
                that.setWishListNumber(data);
                if ( woodmartTheme.supports_html5_storage ) {
                    sessionStorage.setItem( 'woodmart_wishlist_number', data );
                }
            }
        });
    };
    
    woodmartThemeModule.setWishListNumber = function( num ) {
        num = ($.isNumeric(num)) ? num : 0;
        $('.woodmart-wishlist-info-widget .wishlist-count').text(num);
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Quick Shop
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.quickShop = function() {
        var btnSelector = '.quick-shop-on.product-type-variable .add_to_cart_button';

        $(document).on('click', btnSelector, function( e ) {
            e.preventDefault();

            var $this = $(this),
                $product = $this.parents('.product').first(),
                $content = $product.find('.quick-shop-form'),
                id = $product.data('id'),
                loadingClass = 'btn-loading';

            if( $this.hasClass(loadingClass) ) return;


            // Simply show quick shop form if it is already loaded with AJAX previously
            if( $product.hasClass('quick-shop-loaded') ) {
                $product.addClass('quick-shop-shown');
                return;
            }

            $this.addClass(loadingClass);
            $product.addClass('loading-quick-shop');

            $.ajax({
                url: woodmart_settings.ajaxurl,
                data: {
                    action: 'woodmart_quick_shop',
                    id: id
                },
                method: 'get',
                success: function(data) {

                    // insert variations form
                    $content.append(data);

                    initVariationForm($product);
                    $('body').trigger('woodmart-quick-view-displayed');
                    woodmartThemeModule.swatchesVariations();
                    woodmartThemeModule.btnsToolTips();

                },
                complete: function() {
					setTimeout(function () {
						$this.removeClass(loadingClass);
						$product.removeClass('loading-quick-shop');
						$product.addClass('quick-shop-shown quick-shop-loaded');
					}, 100);
                },
                error: function() {
                },
            });

        })

        .on('click', '.quick-shop-close', function() {
            var $this = $(this),
                $product = $this.parents('.product');

            $product.removeClass('quick-shop-shown');
        });

        $( document.body ).on( 'added_to_cart', function() {
            $( '.product' ).removeClass( 'quick-shop-shown' );
        });

        function initVariationForm( $product ) {
            $product.find( '.variations_form' ).wc_variation_form().find('.variations select:eq(0)').change();
            $product.find('.variations_form').trigger('wc_variation_form');
        }
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Quick View
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.quickViewInit = function() {
        // Open popup with product info when click on Quick View button
        $(document).on('click', '.open-quick-view', function(e) {

            e.preventDefault();

            var productId = $(this).data('id'),
                loopName = $(this).data('loop-name'),
                closeText = woodmart_settings.close,
                loadingText = woodmart_settings.loading,
                loop = $(this).data('loop'),
                prev = '',
                next = '',
                loopBtns = $('.quick-view').find('[data-loop-name="' + loopName + '"]'),
                btn = $(this);

            btn.addClass('loading');

            if (typeof loopBtns[loop - 1] != 'undefined') {
                prev = loopBtns.eq(loop - 1).addClass('quick-view-prev');
                prev = $('<div>').append(prev.clone()).html();
            }

            if (typeof loopBtns[loop + 1] != 'undefined') {
                next = loopBtns.eq(loop + 1).addClass('quick-view-next');
                next = $('<div>').append(next.clone()).html();
            }

            woodmartThemeModule.quickViewLoad(productId, btn, prev, next);

        });
    };

    woodmartThemeModule.quickViewLoad = function (id, btn, prev, next) {
        var data = {
            id: id,
            action: "woodmart_quick_view"
		};
		
		var initPopup = function (data) {
			$.magnificPopup.open({
				items: {
					src: '<div class="mfp-with-anim popup-quick-view">' + data + '</div>', // can be a HTML string, jQuery object, or CSS selector
					type: 'inline'
				},
				tClose: woodmart_settings.close,
				tLoading: woodmart_settings.loading,
				removalDelay: 500, //delay removal by X to allow out-animation
				callbacks: {
					beforeOpen: function () {
						this.st.mainClass = woodmartTheme.popupEffect + ' quick-view-wrapper';
					},
					open: function () {
						$('.variations_form').each(function () {
							$(this).wc_variation_form().find('.variations select:eq(0)').change();
						});
						$('.variations_form').trigger('wc_variation_form');
						$('body').trigger('woodmart-quick-view-displayed');
						woodmartThemeModule.swatchesVariations();

						woodmartThemeModule.btnsToolTips();
						setTimeout(function () {
							woodmartThemeModule.nanoScroller();
						}, 300);
					}
				},
			});
		}

        $.ajax({
            url: woodmart_settings.ajaxurl,
            data: data,
            method: 'get',
            success: function (data) {
				if (woodmart_settings.quickview_in_popup_fix) {
					$.magnificPopup.close();
					setTimeout(function () {
						initPopup(data);
					}, 500);
				} else {
					initPopup(data);
				}
            },
            complete: function () {
                btn.removeClass('loading');
            },
            error: function () {
            },
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Swatches variations
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

	woodmartThemeModule.swatchesVariations = function() {
        var $variation_forms = $('.variations_form');

        $variation_forms.each(function() {
            var $variation_form = $(this);

            if( $variation_form.data('swatches') ) return;
            $variation_form.data('swatches', true);

            // If AJX
            if( ! $variation_form.data('product_variations') ) {
                $variation_form.find('.swatches-select').find('> div').addClass('swatch-enabled');
            }

            $('.woodmart-swatch[selected="selected"]').addClass('active-swatch').attr('selected', false);

            if ( $( '.swatches-select > div' ).hasClass( 'active-swatch' ) ) {
                $variation_form.addClass( 'variation-swatch-selected' );
            }

            $variation_form.on('click', '.swatches-select > div', function() {
                var value = $(this).data('value');
                var id = $(this).parent().data('id');

                $variation_form.trigger( 'check_variations', [ 'attribute_' + id, true ] );
                resetSwatches($variation_form);

                //$variation_form.find('select#' + id).val('').trigger('change');
                //$variation_form.trigger('check_variations');

                if ($(this).hasClass('active-swatch')) {
                    // Removed since 2.9 version as not necessary
                    // $variation_form.find( '.variations select' ).val( '' ).change();
                    // $variation_form.trigger( 'reset_data' );
                    // $(this).removeClass('active-swatch')
                    return;
                }

                if ($(this).hasClass('swatch-disabled')) return;
                $variation_form.find('select#' + id).val(value).trigger('change');
                $(this).parent().find('.active-swatch').removeClass('active-swatch');
                $(this).addClass('active-swatch');
                resetSwatches($variation_form);
            })


            // On clicking the reset variation button
            .on( 'click', '.reset_variations', function( event ) {
				$variation_form.find('.active-swatch').removeClass('active-swatch');

				replaceMainGallery('default', $variation_form);
			} )

			.on('reset_data', function() {

				if (!$variation_form.find('.variations .value').hasClass('with-swatches')) {
					replaceMainGallery('default', $variation_form);
				}

                var all_attributes_chosen  = true;
                var some_attributes_chosen = false;

                $variation_form.find( '.variations select' ).each( function() {
                    var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
                    var value          = $( this ).val() || '';

                    if ( value.length === 0 ) {
                        all_attributes_chosen = false;
                    } else {
                        some_attributes_chosen = true;
                    }

                });

                if( all_attributes_chosen ) {
                    $(this).parent().find('.active-swatch').removeClass('active-swatch');
                }

                $variation_form.removeClass('variation-swatch-selected');

                var $mainOwl = (isQuickView()) ? $('.product-quick-view .woocommerce-product-gallery__wrapper') : $('.woocommerce-product-gallery__wrapper');
                
                resetSwatches($variation_form);

                if (!isQuickShop($variation_form)) {
                    scrollToTop();
                }

                if( ! $mainOwl.hasClass('owl-carousel') ) return;

                if ( woodmart_settings.product_slider_auto_height == 'yes' ) {
					$mainOwl.trigger('destroy.owl.carousel');
                    $('.product-images').imagesLoaded(function() {
                        $mainOwl = $mainOwl.owlCarousel(woodmartTheme.mainCarouselArg);
                        $mainOwl.trigger('refresh.owl.carousel');
                    });
                }else{
                    $mainOwl = $mainOwl.owlCarousel(woodmartTheme.mainCarouselArg);
                    $mainOwl.trigger('refresh.owl.carousel');
                }

                var slide_go_to = woodmart_settings.product_gallery.thumbs_slider.position == 'centered' ? woodmart_settings.centered_gallery_start : 0;
                if (isQuickView()) slide_go_to = 0;
                $mainOwl.trigger('to.owl.carousel', slide_go_to);

            })


            // Update first tumbnail
            .on('reset_image', function() {
                var $thumb = $( '.thumbnails .product-image-thumbnail img' ).first();
                if (!isQuickView() && !isQuickShop($variation_form)) {
                    $thumb.wc_reset_variation_attr( 'src' );
                }
            })
            .on( 'show_variation', function( e, variation, purchasable) {

                var $thumb = $('.thumbnails .product-image-thumbnail img').first();

                $variation_form.addClass('variation-swatch-selected');

				var image_src = variation.image.src;
                
                if ( !image_src ) return;

				if (!isQuickView() && !isQuickShop($variation_form) && !replaceMainGallery(variation.variation_id, $variation_form)) {
					$thumb.wc_set_variation_attr('src', image_src);
					woodmartThemeModule.initZoom();
                }

                if (!isQuickShop($variation_form)) {
                    scrollToTop();
                }

                var $mainOwl = (isQuickView()) ? $('.product-quick-view .woocommerce-product-gallery__wrapper') : $('.woocommerce-product-gallery__wrapper');

                if( ! $mainOwl.hasClass('owl-carousel') ) return;

                if ( woodmart_settings.product_slider_auto_height == 'yes' ) {
					$mainOwl.trigger('destroy.owl.carousel');
                    $('.product-images').imagesLoaded(function() {
                        $mainOwl = $mainOwl.owlCarousel(woodmartTheme.mainCarouselArg);
                        $mainOwl.trigger('refresh.owl.carousel');
                    });
                } else {
                    $mainOwl = $mainOwl.owlCarousel(woodmartTheme.mainCarouselArg);
                    $mainOwl.trigger('refresh.owl.carousel');
                }
                
                var $thumbs = $('.images .thumbnails');

                $mainOwl.trigger('to.owl.carousel', 0);

                if( $thumbs.hasClass('owl-carousel') ) {
                    $thumbs.owlCarousel().trigger('to.owl.carousel', 0);
                    $thumbs.find('.active-thumb').removeClass('active-thumb');
                    $thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
                } else if ($thumbs.hasClass('slick-slider')){
                    $thumbs.slick('slickGoTo', 0);
                    if ( !$thumbs.find('.product-image-thumbnail').eq(0).hasClass('active-thumb') ) {
                        $thumbs.find('.active-thumb').removeClass('active-thumb');
                        $thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');  
                    }
                }

            } );
        })

        var resetSwatches = function($variation_form) {

            // If using AJAX
            if( ! $variation_form.data('product_variations') ) return;

            $variation_form.find('.variations select').each(function() {

                var select = $(this);
                var swatch = select.parent().find('.swatches-select');
                var options = select.html();
                // var options = select.data('attribute_html');
                options = $(options);

                swatch.find('> div').removeClass('swatch-enabled').addClass('swatch-disabled');

                options.each(function(el) {
                    var value = $(this).val();

                    if( $(this).hasClass('enabled') ) {
                    // if( ! el.disabled ) {
                        swatch.find('div[data-value="' + value + '"]').removeClass('swatch-disabled').addClass('swatch-enabled');
                    } else {
                        swatch.find('div[data-value="' + value + '"]').addClass('swatch-disabled').removeClass('swatch-enabled');
                    }

                });

            });
        };

        var isQuickView = function() {
            return $( '.single-product-content' ).hasClass( 'product-quick-view' );
        };

        var isQuickShop = function ($form) {
            return $form.parent().hasClass('quick-shop-form');
        };

        var scrollToTop = function () {
            if (!$('body').hasClass('woodmart-product-sticky-on') || !$('.summary-inner').hasClass('is_stuck') || !woodmart_settings.woodmart_sticky_desc_scroll || $(window).width() <= 1024) return;

            $('.woodmart-swatch').tooltip('hide');
            $('html, body').animate({
                scrollTop: $('.product-image-summary').offset().top - 150
            }, 800);
		}
		
		var replaceMainGallery = function (key, $variationForm) {
			if (typeof woodmart_variation_gallery_data == 'undefined' || !woodmart_variation_gallery_data || !woodmart_variation_gallery_data[key] || isQuickShop($variationForm) && isQuickView()) {
				return false;
			}

			var imagesData = woodmart_variation_gallery_data[key];
			var $mainGallery = $('.woocommerce-product-gallery__wrapper');
			$mainGallery.empty();

			for (var index = 0; index < imagesData.length; index++) {
				$mainGallery.append(
					'<div class="product-image-wrap">\
						<figure data-thumb="' + imagesData[index].data_thumb + '" class="woocommerce-product-gallery__image">\
							<a href="' + imagesData[index].href + '">\
								' + imagesData[index].image + '\
							</a>\
						</figure>\
					</div>'
				);
			}

			woodmartThemeModule.productImagesGallery();
			$('.woocommerce-product-gallery__image').trigger('zoom.destroy');
			if (!isQuickView()) {
				woodmartThemeModule.initZoom();
			}

			return true;
		}
    };
    
    woodmartThemeModule.swatchesOnGrid = function() {
        $('body').on('click', '.swatch-on-grid', function() {

            var src, srcset, image_sizes;

            var imageSrc = $(this).data('image-src'),
                imageSrcset = $(this).data('image-srcset'),
                imageSizes = $(this).data('image-sizes');

            if( typeof imageSrc == 'undefined' ) return;

            var product = $(this).parents('.product-grid-item'),
                image = product.find('.product-image-link > img'),
                srcOrig = image.data('original-src'),
                srcsetOrig = image.data('original-srcset'),
                sizesOrig = image.data('original-sizes');

            if( typeof srcOrig == 'undefined' ) {
                image.data('original-src', image.attr('src'));
            }

            if( typeof srcsetOrig == 'undefined' ) {
                image.data('original-srcset', image.attr('srcset'));
            }

            if( typeof sizesOrig == 'undefined' ) {
                image.data('original-sizes', image.attr('sizes'));
            }


            if( $(this).hasClass('current-swatch') ) {
                src = srcOrig;
                srcset = srcsetOrig;
                image_sizes = sizesOrig;
                $(this).removeClass('current-swatch');
                product.removeClass('product-swatched');
            } else {
                $(this).parent().find('.current-swatch').removeClass('current-swatch');
                $(this).addClass('current-swatch');
                product.addClass('product-swatched');
                src = imageSrc;
                srcset = imageSrcset;
                image_sizes = imageSizes;
            }

            if( image.attr('src') == src ) return;

            product.addClass('loading-image');

            image.attr('src', src).attr('srcset', srcset).attr('image_sizes', image_sizes).one('load', function() {
                product.removeClass('loading-image');
            });

        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Products tabs element AJAX loading
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.productsTabs = function() {
        var process = false;

        $('.woodmart-products-tabs').each(function() {
            var $this = $(this),
                $inner = $this.find('.woodmart-tab-content'),
                cache = [];

            if( $inner.find('.owl-carousel').length < 1 ) {
                cache[0] = {
                    html: $inner.html()
                };
            }

            $this.find('.products-tabs-title li').on('click', function(e) {
                e.preventDefault();

                var $this = $(this),
                    atts = $this.data('atts'),
                    index = $this.index();

                if( process || $this.hasClass('active-tab-title') ) return; process = true;

                loadTab(atts, index, $inner, $this, cache,  function(data) {
                    if( data.html ) {
                        $inner.html(data.html);

                        $(document).trigger('wood-images-loaded');

                        woodmartThemeModule.productHover();
                        woodmartThemeModule.btnsToolTips();
                        woodmartThemeModule.shopMasonry();
                        woodmartThemeModule.productsLoadMore();
						woodmartThemeModule.countDownTimer();
						woodmartThemeModule.productLoaderPosition();
                    }
                });

            });

            var $nav = $this.find('.tabs-navigation-wrapper'),
                $subList = $nav.find('ul'),
                time = 300;

            $nav.on('click', '.open-title-menu', function() {
                var $btn = $(this);

                if( $subList.hasClass('list-shown') ) {
                    $btn.removeClass('toggle-active');
                    $subList.stop().slideUp(time).removeClass('list-shown');
                } else {
                    $btn.addClass('toggle-active');
                    $subList.addClass('list-shown');
                    setTimeout(function() {
                        $('body').one('click', function(e) {
                            var target = e.target;
                            if ( ! $(target).is('.tabs-navigation-wrapper') && ! $(target).parents().is('.tabs-navigation-wrapper')) {
                                $btn.removeClass('toggle-active');
                                $subList.removeClass('list-shown');
                                return false;
                            }
                        });
                    },10);
                }

            })
            .on('click', 'li', function() {
                var $btn = $nav.find('.open-title-menu'),
                    text = $(this).text();

                if( $subList.hasClass('list-shown') ) {
                    $btn.removeClass('toggle-active').text(text);
                    $subList.removeClass('list-shown');
                }
            });

        });

        var loadTab = function(atts, index, holder, btn, cache, callback) {

            btn.parent().find('.active-tab-title').removeClass('active-tab-title');
            btn.addClass('active-tab-title')

            if( cache[index] ) {
                holder.addClass('loading');
                setTimeout(function() {
                    callback(cache[index]);
                    holder.removeClass('loading');
                    process = false;
                }, 300);
                return;
            }

            holder.addClass('loading').parent().addClass('element-loading');

            btn.addClass('loading');

            $.ajax({
                url: woodmart_settings.ajaxurl,
                data: {
                    atts: atts,
                    action: 'woodmart_get_products_tab_shortcode'
                },
                dataType: 'json',
                method: 'POST',
                success: function(data) {
                    cache[index] = data;
                    callback( data );
                },
                error: function(data) {
                    console.log('ajax error');
                },
                complete: function() {
                    holder.removeClass('loading').parent().removeClass('element-loading');
                    btn.removeClass('loading');
                    process = false;
                    woodmartThemeModule.compare();
                },
            });
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Load more button for products shortcode
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.productsLoadMore = function() {
        var process = false,
            intervalID;

        $('.woodmart-products-element').each(function() {
            var $this = $(this),
                cache = [],
                inner = $this.find('.woodmart-products-holder');

            if( ! inner.hasClass('pagination-arrows') ) return;

            cache[1] = {
                items: inner.html(),
                status: 'have-posts'
            };

            $this.on('recalc', function() {
                calc();
            });

            $(window).resize(function() {
                calc();
            });

            var calc = function() {
                var height = inner.outerHeight();
                $this.stop().css({minHeight: height});
            };


            // sticky buttons

            var body = $('body'),
                btnWrap = $this.find('.products-footer'),
                btnLeft = btnWrap.find('.woodmart-products-load-prev'),
                btnRight = btnWrap.find('.woodmart-products-load-next'),
                loadWrapp = $this.find('.woodmart-products-loader'),
                scrollTop,
                holderTop,
                btnLeftOffset,
                btnRightOffset,
                holderBottom,
                holderHeight,
                holderWidth,
                btnsHeight,
                offsetArrow = 50,
                offset,
                windowWidth;

            if( body.hasClass('rtl') ) {
                btnLeft = btnRight;
                btnRight = btnWrap.find('.woodmart-products-load-prev');
            }

            $(window).scroll(function() {
                buttonsPos();
            });

            function buttonsPos() {

                offset = $(window).height() / 2;

                windowWidth = $(window).outerWidth(true);

                holderWidth = $this.outerWidth(true);

                scrollTop = $(window).scrollTop();

                holderTop = $this.offset().top - offset;

                btnLeftOffset = $this.offset().left - offsetArrow;

                btnRightOffset = holderWidth + $this.offset().left + offsetArrow - btnRight.outerWidth();

                btnsHeight = btnLeft.outerHeight();

                holderHeight = $this.height() - btnsHeight;

                holderBottom = holderTop + holderHeight;

                if ($(window).width() <= 1024) {
                    btnLeftOffset = btnLeftOffset + 35;
                    btnRightOffset = btnRightOffset - 35;
                }

                btnLeft.css({
                    'left' : btnLeftOffset + 'px'
                });

                btnRight.css({
                    'left' : btnRightOffset + 'px'
                });

                if (scrollTop < holderTop || scrollTop > holderBottom) {
                    btnWrap.removeClass('show-arrow');
                    loadWrapp.addClass('hidden-loader');
                } else {
                    btnWrap.addClass('show-arrow');
                    loadWrapp.removeClass('hidden-loader');
                }

            };

            $this.find('.woodmart-products-load-prev, .woodmart-products-load-next').off('click').on('click', function(e) {

                e.preventDefault();

                if( process || $(this).hasClass('disabled') ) return; process = true;

                clearInterval(intervalID);

                var $this = $(this),
                    holder = $this.parent().parent().prev(),
                    next = $this.parent().find('.woodmart-products-load-next'),
                    prev = $this.parent().find('.woodmart-products-load-prev'),
                    atts = holder.data('atts'),
                    action = 'woodmart_get_products_shortcode',
                    ajaxurl = woodmart_settings.ajaxurl,
                    dataType = 'json',
                    method = 'POST',
                    paged = holder.attr('data-paged');

                paged++;

                if( $this.hasClass('woodmart-products-load-prev') ) {
                    if( paged < 2 ) return;
                    paged = paged - 2;
                }

                loadProducts( 'arrows', atts, ajaxurl, action, dataType, method, paged, holder, $this, cache, function(data) {
					var isBorderedGrid = holder.hasClass('products-bordered-grid');

					if (!isBorderedGrid) {
						holder.addClass('woodmart-animated-products');
					}

                    if( data.items ) {
                        holder.html(data.items).attr('data-paged', paged);
                        holder.imagesLoaded().progress(function() {
                            holder.parent().trigger('recalc');
                        });

                        $(document).trigger('wood-images-loaded');

                        woodmartThemeModule.productHover();
                        woodmartThemeModule.btnsToolTips();
                    }

                    if( $(window).width() < 768 ) {
                        $('html, body').stop().animate({
                            scrollTop: holder.offset().top - 150
                        }, 400);
                    }

					if (!isBorderedGrid) {
						var iter = 0;
						intervalID = setInterval(function () {
							holder.find('.product-grid-item').eq(iter).addClass('woodmart-animated');
							iter++;
						}, 100);
					}

                    if( paged > 1 ) {
                        prev.removeClass('disabled');
                    } else {
                        prev.addClass('disabled');
                    }

                    if( data.status == 'no-more-posts' ) {
                        next.addClass('disabled');
                    } else {
                        next.removeClass('disabled');
                    }
                });

            });
        });


        woodmartThemeModule.clickOnScrollButton( woodmartTheme.shopLoadMoreBtn , false, woodmart_settings.infinit_scroll_offset );

        $(document).off('click', '.woodmart-products-load-more').on('click', '.woodmart-products-load-more',  function(e) {
            e.preventDefault();

            if( process ) return; process = true;

            var $this = $(this),
                holder = $this.parent().siblings('.woodmart-products-holder'),
                source = holder.data('source'),
                action = 'woodmart_get_products_' + source,
                ajaxurl = woodmart_settings.ajaxurl,
                dataType = 'json',
                method = 'POST',
                atts = holder.data('atts'),
                paged = holder.data('paged');

            paged++;

            if( source == 'main_loop' ) {
                ajaxurl = $(this).attr('href');
                method = 'GET';
            }

            loadProducts( 'load-more', atts, ajaxurl, action, dataType, method, paged, holder, $this, [], function(data) {
                if( data.items ) {
                    if( holder.hasClass('grid-masonry') ) {
                        isotopeAppend(holder, data.items);
                    } else {
                        holder.append(data.items);
                    }

                    holder.imagesLoaded().progress(function() {
                        woodmartThemeModule.clickOnScrollButton( woodmartTheme.shopLoadMoreBtn , true, woodmart_settings.infinit_scroll_offset );
                    });

                    $(document).trigger('wood-images-loaded');

                    holder.data('paged', paged);

                    woodmartThemeModule.productHover();
                    woodmartThemeModule.btnsToolTips();
                }

                if( source == 'main_loop' ) {
                    $this.attr('href', data.nextPage);
                    if( data.status == 'no-more-posts' ) {
                        $this.hide().remove();
                    }
                }

                if( data.status == 'no-more-posts' ) {
                    $this.hide();
                }
            });

        });

        var loadProducts = function( btnType, atts, ajaxurl, action, dataType, method, paged, holder, btn, cache, callback) {
            var data = {
                atts: atts, 
                paged: paged, 
                action: action,
                woo_ajax: 1
            };

            if( cache[paged] ) {
                holder.addClass('loading');
                setTimeout(function() {
                    callback(cache[paged]);
                    holder.removeClass('loading');
                    process = false;
                }, 300);
                return;
            }

            if (btnType == 'arrows') holder.addClass('loading').parent().addClass('element-loading');

            btn.addClass('loading');

            if( action == 'woodmart_get_products_main_loop' ) {
                var loop = holder.find('.product').last().data('loop');
                data = {
                    loop: loop,
                    woo_ajax: 1
                };
            }

            $.ajax({
                url: ajaxurl,
                data: data,
                dataType: dataType,
                method: method,
                success: function(data) {
                    cache[paged] = data;
                    callback( data );
                },
                error: function(data) {
                    console.log('ajax error');
                },
                complete: function() {
                    if (btnType == 'arrows') holder.removeClass('loading').parent().removeClass('element-loading');
                    btn.removeClass('loading');
                    process = false;
                    woodmartThemeModule.compare();
                    woodmartThemeModule.productHover();
                    woodmartThemeModule.countDownTimer();
                },
            });
        };

        var isotopeAppend = function(el, items) {
            // initialize Masonry after all images have loaded
            var items = $(items);
            el.append(items).isotope( 'appended', items );
            el.imagesLoaded().progress(function() {
                el.isotope('layout');
            });
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Compare button
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
     
    woodmartThemeModule.compare = function() {
        var body = $("body"),
            button = $("a.compare");

        body.on("click", "a.compare", function() {
            $(this).addClass("loading");
        });

        body.on("yith_woocompare_open_popup", function() {
            button.removeClass("loading");
            body.addClass("compare-opened");
        });

        body.on('click', '#cboxClose, #cboxOverlay', function() {
            body.removeClass("compare-opened");
        });
	};
	
	/**
	 *-------------------------------------------------------------------------------------------------------------------------------------------
	 * WoodMart compare functions
	 *-------------------------------------------------------------------------------------------------------------------------------------------
	 */

	woodmartThemeModule.woodmartCompare = function () {
		var $body = $("body"),
			$widget = $('.woodmart-compare-info-widget'),
			compareCookie = Cookies.get('woodmart_compare_list');

		if ($widget.length > 0) {
			try {
				var ids = JSON.parse(compareCookie);
				$widget.find('.compare-count').text(ids.length);
			} catch (e) {
				console.log('cant parse cookies json');
			}
		}
		// Add to compare action

		$body.on('click', '.woodmart-compare-btn a', function (e) {
			var $this = $(this),
				id = $this.data('id'),
				addedText = $this.data('added-text');

			if ($this.hasClass('added')) return true;

			e.preventDefault();

			$this.addClass('loading');

			jQuery.ajax({
				url: woodmart_settings.ajaxurl,
				data: {
					action: 'woodmart_add_to_compare',
					id: id
				},
				dataType: 'json',
				method: 'GET',
				success: function (response) {
					if (response.table) {
						updateCompare(response);
					} else {
						console.log('something wrong loading compare data ', response);
					}
				},
				error: function (data) {
					console.log('We cant add to compare. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function () {
					$this.removeClass('loading').addClass('added');

					if ($this.find('span').length > 0) {
						$this.find('span').text(addedText);
					} else {
						$this.text(addedText);
					}
				},
			});

		});

		// Remove from compare action

		$body.on('click', '.woodmart-compare-remove', function (e) {
			var $table = $('.woodmart-compare-table');

			e.preventDefault();
			var $this = $(this),
				id = $this.data('id');

			$table.addClass('loading');

			jQuery.ajax({
				url: woodmart_settings.ajaxurl,
				data: {
					action: 'woodmart_remove_from_compare',
					id: id
				},
				dataType: 'json',
				method: 'GET',
				success: function (response) {
					if (response.table) {
						updateCompare(response);
					} else {
						console.log('something wrong loading compare data ', response);
					}
				},
				error: function (data) {
					console.log('We cant remove product compare. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function () {
					$table.removeClass('loading');
				},
			});

		});

		// Elements update after ajax

		function updateCompare(data) {
			if ($widget.length > 0) {
				$widget.find('.compare-count').text(data.count);
			}

			if ($('.woodmart-compare-table').length > 0) {
				$('.woodmart-compare-table').replaceWith(data.table);
			}

		}

	};
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Add class in wishlist
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.wishList = function() {
        var body = $("body");

        body.on("click", ".add_to_wishlist", function() {

            $(this).parent().addClass("feid-in");

        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product 360 button
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
     
    woodmartThemeModule.product360Button = function() {
        $('.product-360-button a').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            // removalDelay: 500, //delay removal by X to allow out-animation
            // callbacks: {
            //     beforeOpen: function() {
            //         this.st.mainClass = woodmartTheme.popupEffect;
            //     }
            // },
            preloader: false,
            tClose: woodmart_settings.close,
            tLoading: woodmart_settings.loading,
            fixedContentPos: false,
            callbacks: {
                open: function() {
                    $(window).resize()
                },
            },
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product video button
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.productVideo = function() {
        $('.product-video-button a').magnificPopup({
            tClose: woodmart_settings.close,
            tLoading: woodmart_settings.loading,
            type: 'iframe',
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com/', 
                        id: 'v=', 
                        src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
                    }
                }
            }, 
            preloader: false,
            fixedContentPos: false
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Login tabs for my account page
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
     
    woodmartThemeModule.loginTabs = function() {
        var tabs = $('.woodmart-register-tabs'),
            btn = tabs.find('.woodmart-switch-to-register'),
            title = $('.col-register-text h2'),
            login = tabs.find('.col-login'),
            loginText = tabs.find('.login-info'),
            register = tabs.find('.col-register'),
            classOpened = 'active-register',
            loginLabel = btn.data('login'),
            registerLabel = btn.data('register');

        btn.click(function(e) {
            e.preventDefault();

            if( isShown() ) {
                hideRegister();
            } else {
                showRegister();
            }

            var scrollTo = $('.main-page-wrapper').offset().top - 100;

            if( $(window).width() < 768 ) {
                $('html, body').stop().animate({
                    scrollTop: tabs.offset().top - 90
                }, 400);
            }
        });

        var showRegister = function() {
            tabs.addClass(classOpened);
            btn.text(loginLabel);
            if ( loginText.length > 0 ) {
                title.text(registerLabel);
            }
        };

        var hideRegister = function() {
            tabs.removeClass(classOpened);
            btn.text(registerLabel);
            if ( loginText.length > 0 ) {
                title.text(loginLabel);
            }
        };

        var isShown = function() {
            return tabs.hasClass(classOpened);
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Header Categories menu for mobile
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.headerCategoriesMenu = function() {
        if( $(window).width() > 1024 ) return;
        
        var categories = $('.header-categories-nav'),
            catsUl = categories.find('.categories-menu-dropdown'),
            subCategories = categories.find('.menu-item-has-children'),
            button = categories.find('.menu-opener'),
            time = 200,
            iconDropdown = '<span class="drop-category"></span>';

        subCategories.find('> a').before(iconDropdown);

        catsUl.on('click','.drop-category', function(){
            var sublist = $(this).parent().find('> .sub-menu-dropdown, >.sub-sub-menu');
            if(sublist.hasClass('child-open')){
                $(this).removeClass("act-icon");
                sublist.slideUp(time).removeClass('child-open');
            }else {
                $(this).addClass("act-icon");
                sublist.slideDown(time).addClass('child-open');
            }
        }); 

        categories.on('click', '.menu-opener', function(e) {
            e.preventDefault();

            if( isOpened() ) {
                closeCats();
            } else {
                //setTimeout(function() {
                    openCats();
                //}, 50);
            }
        });

        catsUl.on('click', 'a', function(e) {
            closeCats();
            catsUl.stop().attr('style', '');
        });

        var isOpened = function() {
            return catsUl.hasClass('categories-opened');
        };

        var openCats = function() {
            catsUl.addClass('categories-opened').stop().slideDown(time);
            button.addClass('button-open');
            
        };

        var closeCats = function() {
            catsUl.removeClass('categories-opened').stop().slideUp(time);
            button.removeClass('button-open');
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Categories menu for mobile
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.categoriesMenu = function() {
        if( $(window).width() > 1024 ) return;

        var categories = $('.woodmart-product-categories'),
            subCategories = categories.find('li > ul'),
            button = $('.woodmart-show-categories'),
            time = 200;

        //this.categoriesMenuBtns();

        $('body').on('click','.icon-drop-category', function(){
            if($(this).closest('.has-sub').find('> ul').hasClass('child-open')){
                $(this).removeClass("woodmart-act-icon").closest('.has-sub').find('> ul').slideUp(time).removeClass('child-open');
            }else {
                $(this).addClass("woodmart-act-icon").closest('.has-sub').find('> ul').slideDown(time).addClass('child-open');
            }
        }); 

        $('body').on('click', '.woodmart-show-categories', function(e) {
            e.preventDefault();

            if( isOpened() ) {
                closeCats();
            } else {
                //setTimeout(function() {
                    openCats();
                //}, 50);
            }
        });

        $('body').on('click', '.woodmart-product-categories a', function(e) {
            closeCats();
            categories.stop().attr('style', '');
        });

        var isOpened = function() {
            return $('.woodmart-product-categories').hasClass('categories-opened');
        };

        var openCats = function() {
            $('.woodmart-product-categories').addClass('categories-opened').stop().slideDown(time);
            $('.woodmart-show-categories').addClass('button-open');

        };

        var closeCats = function() {
            $('.woodmart-product-categories').removeClass('categories-opened').stop().slideUp(time);
            $('.woodmart-show-categories').removeClass('button-open');
        };
    };
    
    woodmartThemeModule.categoriesMenuBtns = function() {
        if( $(window).width() > 1024 ) return;

        var categories = $('.woodmart-product-categories'),
            subCategories = categories.find('li > ul'),
            iconDropdown = '<span class="icon-drop-category"></span>';

        categories.addClass('responsive-cateogires');
        subCategories.parent().addClass('has-sub').find('> .category-nav-link').prepend(iconDropdown);
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Filters area
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.filtersArea = function() {
        var filters = $('.filters-area'),
            btn = $('.open-filters'),
            time = 200;

        $('body').on('click', '.open-filters', function(e) {
            e.preventDefault();

            if( isOpened() ) {
                closeFilters();
            } else {
                woodmartThemeModule.openFilters(time);
                setTimeout(function () {
                    woodmartThemeModule.shopLoader();
                }, time);
            }

        });

        if (woodmart_settings.shop_filters_close == 'no') {
            $('body').on('click', woodmartTheme.ajaxLinks, function () {
                if (isOpened()) {
                    closeFilters();
                }
            });
        }

        var isOpened = function() {
            filters = $('.filters-area')
            return filters.hasClass('filters-opened');
        };

        var closeFilters = function() {
            filters = $('.filters-area')
            filters.removeClass('filters-opened');
            filters.stop().slideUp(time);
            $('.open-filters').removeClass('btn-opened');
        };
    };

    woodmartThemeModule.openFilters = function (time) {
        var filters = $('.filters-area')
        filters.stop().slideDown(time);
        $('.open-filters').addClass('btn-opened');
        setTimeout(function () {
            filters.addClass('filters-opened');
            $('body').removeClass('body-filters-opened');
            woodmartThemeModule.nanoScroller();
            $(document).trigger('wood-images-loaded');
        }, time);
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Init shop page JS functions
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.shopPageInit = function() {
        woodmartThemeModule.shopMasonry();
        woodmartThemeModule.ajaxSearch();
        woodmartThemeModule.productHover();
        woodmartThemeModule.btnsToolTips();
        woodmartThemeModule.compare();
        woodmartThemeModule.filterDropdowns();
        woodmartThemeModule.sortByWidget();
        woodmartThemeModule.categoriesMenuBtns();
        woodmartThemeModule.categoriesAccordion();
        woodmartThemeModule.woocommercePriceSlider();
        woodmartThemeModule.updateCartWidgetFromLocalStorage(); // refresh cart in sidebar
        woodmartThemeModule.countDownTimer();
        woodmartThemeModule.nanoScroller();
        woodmartThemeModule.shopLoader();
        woodmartThemeModule.stickySidebarBtn();
        woodmartThemeModule.productFilters();
        
        woodmartThemeModule.clickOnScrollButton( woodmartTheme.shopLoadMoreBtn , false, woodmart_settings.infinit_scroll_offset );

        // Bootstrap tooltips reset

        $('body > .tooltip').remove();

        $( document.body ).on( 'updated_wc_div', function() {
            $(document).trigger('wood-images-loaded');
        } );

        $(document).trigger('resize.vcRowBehaviour');
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Ajax filters
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.ajaxFilters = function() {
        if( ! $('body').hasClass('woodmart-ajax-shop-on') || typeof( $.fn.pjax ) == 'undefined' ) return;

        var that = this,
            filtersState = false,
            products = $('.products');

        $('body').on('click', '.post-type-archive-product .products-footer .woocommerce-pagination a', function (e) {
            scrollToTop(true);
        });

        $(document).pjax(woodmartTheme.ajaxLinks, '.main-page-wrapper', {
            timeout: woodmart_settings.pjax_timeout,
            scrollTo: false
        });
        
        if ( woodmart_settings.price_filter_action == 'click' ) {
            $( document ).on('click', '.widget_price_filter form .button', function() {
                var form = $( '.widget_price_filter form');
                $.pjax({
                    container: '.main-page-wrapper',
                    timeout: woodmart_settings.pjax_timeout,
                    url: form.attr('action'),
                    data: form.serialize(),
                    scrollTo: false
                });

                return false;
            });
        }else if ( woodmart_settings.price_filter_action == 'submit' ) {
            $( document ).on('submit', '.widget_price_filter form', function(event) {
                var container = $( '.main-page-wrapper' );
                $.pjax.submit( event, container );
            });
        }

        $(document).on('pjax:error', function(xhr, textStatus, error, options) {
            console.log('pjax error ' + error);
        });

        $(document).on('pjax:start', function(xhr, options) {
            $('.site-content').removeClass('ajax-loaded');
            $('.site-content').addClass('ajax-loading');
            woodmartThemeModule.hideShopSidebar();
        });

        $(document).on('pjax:complete', function(xhr, textStatus, options) {

            that.shopPageInit();
            scrollToTop(false);

            $(document).trigger('wood-images-loaded');
            
            $('.woodmart-sidebar-content').scroll(function () {
                $(document).trigger('wood-images-loaded');
            })

            $('.site-content').removeClass('ajax-loading');
        });

        $(document).on('pjax:beforeReplace', function(contents, options) {
            if ( $( '.filters-area' ).hasClass( 'filters-opened' ) && woodmart_settings.shop_filters_close == 'yes' ) {
                filtersState = true;
                $( 'body' ).addClass( 'body-filters-opened' );
            }
        });

        $(document).on('pjax:end', function (xhr, textStatus, options) {
            if (filtersState) {
                $('.filters-area').css('display', 'block');
                woodmartThemeModule.openFilters(200);
                filtersState = false;
            }
            $('.site-content').removeClass('ajax-loading');
            $('.site-content').addClass('ajax-loaded');
        });

        var scrollToTop = function( type ) {
            if ( woodmart_settings.ajax_scroll == 'no' && type == false ) return false;
            
            var $scrollTo = $(woodmart_settings.ajax_scroll_class),
                scrollTo = $scrollTo.offset().top - woodmart_settings.ajax_scroll_offset;

            $('html, body').stop().animate({
                scrollTop: scrollTo
            }, 400);
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Side shopping cart widget
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.cartWidget = function() {
        var widget = $('.cart-widget-opener'),
            btn = widget.find('a'),
            body = $('body');
        
        var cartWidgetSide = $('.cart-widget-side');
        var closeSide = $('.woodmart-close-side');

        widget.on('click', function(e) {
            if ( ! isCart() && ! isCheckout() ) e.preventDefault();

            if( isOpened() ) {
                closeWidget();
            } else {
                setTimeout( function() {
                    openWidget();
                }, 10);
            }

        });

        body.on("click touchstart", ".woodmart-close-side", function() {
            if( isOpened() ) {
                closeWidget();
            }
        });

        body.on("click", ".close-side-widget", function( e ) {
            e.preventDefault();
            if( isOpened() ) {
                closeWidget();
            }
        });
        
        $( document ).keyup( function( e ) {
            if ( e.keyCode === 27 && isOpened() ) closeWidget();
        });

        var closeWidget = function() {
            cartWidgetSide.removeClass('woodmart-cart-opened');
            closeSide.removeClass('woodmart-close-side-opened');
        };

        var openWidget = function() {
            if ( isCart() || isCheckout() ) return false;
            cartWidgetSide.addClass('woodmart-cart-opened');
            closeSide.addClass('woodmart-close-side-opened');
        };

        var isOpened = function() {
            return cartWidgetSide.hasClass('woodmart-cart-opened');
        };

        var isCart = function() {
            return $('body').hasClass('woocommerce-cart');
        };
        
        var isCheckout = function() {
            return $('body').hasClass('woocommerce-checkout');
        };
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Quantityt +/-
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.woocommerceQuantity = function() {
        if ( ! String.prototype.getDecimals ) {
            String.prototype.getDecimals = function() {
                var num = this,
                    match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                if ( ! match ) {
                    return 0;
                }
                return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
            }
        }
        
        $( document ).on( 'click', '.plus, .minus', function() {
            // Get values
            var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
                currentVal  = parseFloat( $qty.val() ),
                max         = parseFloat( $qty.attr( 'max' ) ),
                min         = parseFloat( $qty.attr( 'min' ) ),
                step        = $qty.attr( 'step' );

            // Format values
            if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
            if ( max === '' || max === 'NaN' ) max = '';
            if ( min === '' || min === 'NaN' ) min = 0;
            if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = '1';

            // Change the value
            if ( $( this ).is( '.plus' ) ) {
                if ( max && ( currentVal >= max ) ) {
                    $qty.val( max );
                } else {
                    $qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            } else {
                if ( min && ( currentVal <= min ) ) {
                    $qty.val( min );
                } else if ( currentVal > 0 ) {
                    $qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            }

            // Trigger change event
            $qty.trigger( 'change' );
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * On remove from cart widget
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.onRemoveFromCart = function() {
        $( document ).on('click', '.widget_shopping_cart .remove', function(e) {
            e.preventDefault();
            $(this).parent().addClass('removing-process');
        });
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Fix comments
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.woocommerceComments = function() {
        var hash  = window.location.hash;
        var url   = window.location.href;

        if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' || url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {

            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 1);

            setTimeout(function() {
                $('html, body').stop().animate({
                    scrollTop: $(hash).offset().top-100
                }, 400);
            }, 10);

        }
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Mobile responsive navigation
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.woocommerceWrappTable = function() {
        var wooTable = $(".shop_table:not(.shop_table_responsive):not(.woocommerce-checkout-review-order-table)").wrap("<div class='responsive-table'></div>");
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product loder position
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.productLoaderPosition = function () {
        var reculc = function () {
            $('.woodmart-products-loader').each(function () {
                var $loader = $(this),
                    $loaderWrap = $loader.parent();

                if ($loader.length == 0) return;

                $loader.css('left', $loaderWrap.offset().left + $loaderWrap.outerWidth() / 2);
            });
        };

        $(window).on('resize', reculc);

        reculc();
    };
    
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Init Zoom
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    
    woodmartThemeModule.initZoom = function() {
        if ( woodmart_settings.zoom_enable != 'yes' ) return false;

        var $zoomTarget = $( '.woocommerce-product-gallery__image' ),
            zoomEnabled = false;

        $( $zoomTarget ).each( function() {
            var image = $(this).find( 'img' );

            if (image.data('large_image_width') > $( '.product-image-wrap' ).width() ) {
                zoomEnabled = true;
                return false;
            }
        } );


        // But only zoom if the img is larger than its container.
        if ( zoomEnabled ) {
            var zoomOptions = {
                touch: false
            };

            if ( 'ontouchstart' in window ) {
                zoomOptions.on = 'click';
            }

            $zoomTarget.trigger( 'zoom.destroy' );
            $zoomTarget.zoom( zoomOptions );
        }
    };

    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Product Hover
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    woodmartThemeModule.productHover = function () {

        $('.woodmart-hover-base').each(function () {

            var $product = $(this);

            $product.imagesLoaded(function () {

                // Read more details button

                var btnHTML = '<a href="#" class="more-details-btn"><span>' + 'more' + '</a></span>',
                    content = $product.find('.hover-content'),
                    inner = content.find('.hover-content-inner'),
                    contentHeight = content.outerHeight(),
                    innerHeight = inner.outerHeight(),
                    delta = innerHeight - contentHeight;

                if (content.hasClass('more-description')) return;

                if (delta > 30) {
                    content.addClass('more-description');
                    content.append(btnHTML);
                } else if (delta > 0) {
                    content.css('height', contentHeight + delta);
                }

                // Bottom block height

                reculc($product);
            });

        });

        $('body').on('click', '.more-details-btn', function (e) {
            e.preventDefault();
            $(this).parent().addClass('show-full-description');
            reculc($(this).parents('.woodmart-hover-base'));
        });

        if ($(window).width() <= 1024) {
            $('.woodmart-hover-base').on('click', function (e) {
                var hoverClass = 'state-hover';
                if (!$(this).hasClass(hoverClass) && woodmart_settings.base_hover_mobile_click == 'no') {
                    e.preventDefault();
                    $('.' + hoverClass).removeClass(hoverClass);
                    $(this).addClass(hoverClass);
                }
            });
            $(document).on('click touchstart', function (e) {
                if ($(e.target).closest('.state-hover').length == 0) {
                    $('.state-hover').removeClass('state-hover');
                }
            });
        }

        var reculc = function ($el) {

            if ($el.hasClass('product-in-carousel')) {
                return;
            }

            var heightHideInfo = $el.find('.fade-in-block').outerHeight();

            $el.find('.content-product-imagin').css({
                marginBottom: -heightHideInfo
            });

            $el.addClass('hover-ready');
        };

        $('.product-grid-item').each(function () {
            var $el = $(this),
                widthHiddenInfo = $el.outerWidth();

            if ($(window).width() <= 1024 && $el.hasClass('woodmart-hover-icons')) return;

            if (widthHiddenInfo < 255 || $(window).width() <= 1024) {
                $el.removeClass('hover-width-big').addClass('hover-width-small');
            } else {
                $el.removeClass('hover-width-small').addClass('hover-width-big');
            }
        })

    };
    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Enable masonry grid for shop isotope type
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    woodmartThemeModule.shopMasonry = function () {
    
        if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') return;
        var $container = $('.elements-grid.grid-masonry');
        // initialize Masonry after all images have loaded
        $container.imagesLoaded(function () {
            $container.isotope({
                isOriginLeft: !$('body').hasClass('rtl'),
                itemSelector: '.category-grid-item, .product-grid-item',
            });
        });

        // Categories masonry
        $(window).resize(function () {
            var $catsContainer = $('.categories-masonry');
            var colWidth = ($catsContainer.hasClass('categories-style-masonry')) ? '.category-grid-item' : '.col-lg-3.category-grid-item';
            $catsContainer.imagesLoaded(function () {
                $catsContainer.packery({
                    resizable: false,
                    isOriginLeft: !$('body').hasClass('rtl'),
                    // layoutMode: 'packery',
                    packery: {
                        gutter: 0,
                        columnWidth: colWidth
                    },
                    itemSelector: '.category-grid-item',
                    // masonry: {
                    // gutter: 0
                    // }
                });
            });
        });

    };

    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * Categories toggle accordion
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */
    woodmartThemeModule.categoriesAccordion = function () {

        if (woodmart_settings.categories_toggle == 'no') return;

        var $widget = $('.widget_product_categories'),
            $list = $widget.find('.product-categories'),
            time = 300;

        $list.find('.cat-parent').each(function () {
            if ($(this).find(' > .woodmart-cats-toggle').length > 0) return;
            if ($(this).find(' > .children').length == 0) return;
            $(this).append('<div class="woodmart-cats-toggle"></div>');
        });

        $list.on('click', '.woodmart-cats-toggle', function () {
            var $btn = $(this),
                $subList = $btn.prev();

            if ($subList.hasClass('list-shown')) {
                $btn.removeClass('toggle-active');
                $subList.stop().slideUp(time).removeClass('list-shown');
            } else {
                $subList.parent().parent().find('> li > .list-shown').slideUp().removeClass('list-shown');
                $subList.parent().parent().find('> li > .toggle-active').removeClass('toggle-active');
                $btn.addClass('toggle-active');
                $subList.stop().slideDown(time).addClass('list-shown');
            }
        });

        if ($list.find('li.current-cat.cat-parent, li.current-cat-parent').length > 0) {
            $list.find('li.current-cat.cat-parent, li.current-cat-parent').find('> .woodmart-cats-toggle').click();
        }

    };

    /**
     *-------------------------------------------------------------------------------------------------------------------------------------------
     * WooCommerce price filter slider with ajax
     *-------------------------------------------------------------------------------------------------------------------------------------------
     */

    woodmartThemeModule.woocommercePriceSlider = function () {

        // woocommerce_price_slider_params is required to continue, ensure the object exists
        if ( typeof woocommerce_price_slider_params === 'undefined' || $( '.price_slider_amount #min_price' ).length < 1 || ! $.fn.slider ) {
            return false;
        }
        
        var $slider = $( '.price_slider' );
        
        if ( $slider.slider('instance') !== undefined ) return;

        // Get markup ready for slider
        $( 'input#min_price, input#max_price' ).hide();
        $( '.price_slider, .price_label' ).show();

        // Price slider uses jquery ui
        var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
            max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
            current_min_price = parseInt( min_price, 10 ),
            current_max_price = parseInt( max_price, 10 );

        if ( $('.products').attr('data-min_price') && $('.products').attr('data-min_price').length > 0 ) {
            current_min_price = parseInt( $('.products').attr('data-min_price'), 10 );
        }
        if ( $('.products').attr('data-max_price') && $('.products').attr('data-max_price').length > 0 ) {
            current_max_price = parseInt( $('.products').attr('data-max_price'), 10 );
        }
        
        $slider.slider({
            range: true,
            animate: true,
            min: min_price,
            max: max_price,
            values: [ current_min_price, current_max_price ],
            create: function() {

                $( '.price_slider_amount #min_price' ).val( current_min_price );
                $( '.price_slider_amount #max_price' ).val( current_max_price );

                $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
            },
            slide: function( event, ui ) {

                $( 'input#min_price' ).val( ui.values[0] );
                $( 'input#max_price' ).val( ui.values[1] );

                $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
            },
            change: function( event, ui ) {

                $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
            }
        });

        setTimeout(function() {
            $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
            if( $slider.find('.ui-slider-range').length > 1 ) $slider.find('.ui-slider-range').first().remove();
        }, 10);
    };

})(jQuery);

jQuery(document).ready(function () {
    woodmartThemeModule.init();
}); 