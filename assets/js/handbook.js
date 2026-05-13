jQuery(function ($) {

    function initTeamGallery() {
        var $gallery = $('.handbook .team-list.gallery');

        if (window.innerWidth < 768) {
            if (!$gallery.hasClass('slick-initialized')) {
                $gallery.slick({
                    slidesToShow: 1,
                    arrows: false,
                    dots: false
                });
            }
        } else {
            if ($gallery.hasClass('slick-initialized')) {
                $gallery.slick('unslick');
            }
        }
    }

    if (typeof $.fn.slick === 'function') {
        $('.handbook .images-block .slider').slick({
            slidesToShow: 1,
            arrows: true,
            dots: true 
        });

         initTeamGallery();
        $(window).on('resize', function() {
            initTeamGallery();
        });
    } else {
        console.warn('Slick slider is not loaded');
    }
	
	 $('.handbook .text-block table:not(.compact):not(.background)').each(function() {
		var $table = $(this);
		var headers = [];

		 $table.find('tr').first().find('th').each(function(i){
			headers[i] = $(this).text().trim();
		});

		 $table.find('tr').not(':first').each(function(){
			$(this).find('td').each(function(i){
				$(this).attr('data-label', headers[i]);
			});
		});
	});
	$('.hamburger').on('click', function(){
        $(this).toggleClass('active');  
        $('.handbook-sidebar').toggleClass('active'); 
        $('body').toggleClass('openmenu'); 
    });
	
	$('.handbook-sidebar .info').each(function() {
		if (window.innerWidth < 768) {
			var $container = $(this);
			var $children = $container.children();
			var columns = 2;

 			var colHTML = [];
			for (var i = 0; i < columns; i++) {
				colHTML.push($('<div class="column"></div>'));
			}

			var colIndex = 0;

			$children.each(function() {
				var $el = $(this);

				if ($el.is('h5') && colIndex < columns) {
					colIndex++;
				}

 				colHTML[Math.max(colIndex - 1, 0)].append($el.clone());
			});

			$container.empty().append(colHTML);
		}
	});
});
jQuery(document).on('click', '.handbook-sidebar .top li > a', function(e) {
    var $a = jQuery(this);
    var $li = $a.parent();
    var isSubItem = $li.parent().hasClass('sub-nav');

    if (!isSubItem && $li.hasClass('has-subnav')) {
        $li.toggleClass('open');
    }

    if (jQuery(window).width() < 768) {
        var target = $a.attr('href');
        if (target && target.startsWith('#')) {
            e.preventDefault();
            var $target = jQuery(target);
            if ($target.length) {
                jQuery('html, body').animate({
                    scrollTop: $target.offset().top
                }, 600);
            }
        }
        if (isSubItem || !$li.hasClass('has-subnav')) {
            jQuery('.hamburger').toggleClass('active');
            jQuery('.handbook-sidebar').toggleClass('active');
            jQuery('body').toggleClass('openmenu');
        }
    }
});
