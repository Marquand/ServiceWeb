document.createElement('header');
document.createElement('nav');
document.createElement('aside');
document.createElement('section');
document.createElement('footer');

$(document).ready(function () {
	
    anti_ie7();

    $("#owl-slider").owlCarousel({

        navigation: true,
        pagination: true,
        responsive: true,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        navigationText: ["&lt;", "&gt;"],
        transitionStyle: false, // Effets disponibles : "fade", "backSlider", "goDown", "fadeUp"
        autoPlay: 3000,
        stopOnHover: true
    });
});


$(window).load(function () {
    var ScrollToBottom = $('<div id="scrollToBottom"><a href="#" title="Scroll to bottom">Bas du site</a></div>');
    var ScrollToTop = $('<div id="scrollToTop"><a href="#" title="Scroll to top">Haut du site</a></div>');
    var result = insertScroll(ScrollToTop, ScrollToBottom);
    $('#footer').after(ScrollToBottom);
    $('#footer').after(ScrollToTop);
    ScrollToBottom.click(function () {
        $('html,body').animate({'scrollTop': result.size + result.windowsHeight}, 1000, function () {
            ScrollToBottom.fadeOut("fast");
            ScrollToTop.fadeIn("fast");
        });
    });
    ScrollToTop.click(function () {
        $('html,body').animate({'scrollTop': 0}, 1000, function () {
            ScrollToTop.fadeOut("fast");
            ScrollToBottom.fadeIn("fast");

        });
    });
    $(window).scroll(function () {
        insertScroll(ScrollToTop, ScrollToBottom)
    });

    function insertScroll(ScrollToTop, ScrollToBottom) {
        var windowsHeight = $(window).height();
        var size = $(document).height();
        var scrollTop = $(window).scrollTop();
        if (scrollTop + windowsHeight >= size - 20) {
            ScrollToTop.css({'display': 'block'});
            ScrollToBottom.css({'display': 'none'});
        } else {
            ScrollToBottom.css({'display': 'block'});
            ScrollToTop.css({'display': 'none'});
        }
        return {"size": size, "windowsHeight": windowsHeight};
    }
});

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;


function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: 390*1.5,
        width: 640*1.5,

        playerVars: {
            'autoplay': 1,
            'fs': 0,
            'controls': 0,
            'showinfo': 0,
            'disablekb': 1,
            'modestbranding': 1,
            'rel': 0,
            'hd': 1,
            'autohide': 1,
            'loop': 1

        },
        videoId: 'kqUR3KtWbTk', //F1a3Fn17EXE
        events: {
            'onReady': onPlayerReady,
            // 'onStateChange': onPlayerStateChange
            onStateChange:
                function(e){
                    if (e.data === YT.PlayerState.PLAYING) {
                        onPlayerStarted();
                    }
                    if (e.data === YT.PlayerState.ENDED) {
                        player.playVideo();
                    }
                }
        }
    });
}

function onPlayerStarted(event) {
    //  event.target.setLoop(true);
    $('.transparent').each(function(){
        var s = Math.random();
        $(this).css({'transition-delay':s+'s'});
    });
    $('body').addClass('videoloaded');
}
function onPlayerReady(event) {
    event.target.playVideo();
}

$(document).ready(function(){
    //$('svg').clone().addClass('svg2').appendTo(".svgs");
})
