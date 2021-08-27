let follower = {
    run: () => {
        if($(window).width() > 1023) {
            let $follower = $("#follower");
            let $img = $('[data-img]');

            if($follower.length) {
                let mouseX = 0,
                    mouseY = 0;

                let xp = 0,
                    yp = 0;

                let loop = setInterval(function(){
                    xp += (mouseX - xp) / 10 ;
                    yp += (mouseY - yp) / 10 ;
                    $follower.css({left:xp, top:yp});
                }, 1);

                $img.mouseenter(function() {

                    $follower.css('backgroundImage', `url(${$(this).data('img')})`).css('display','block');
                    $follower.css('z-index',10);
                    $(document).on('mousemove.follower', function(e){

                        let offset = $('.c-t').offset();

                        mouseX = e.pageX - offset.left - 150;
                        mouseY = e.pageY - offset.top -100;
                        if (mouseX < 0) mouseX = 0;
                        if (mouseY < 0) mouseY = 0;
                    });
                });
                $img.mouseleave(function() {
                    $follower.css('display','none');
                    $(document).off('mousemove.follower');
                })
            }
        }
    }
};

module.exports = follower;
