let newsText = {
    run () {
        if (window.innerWidth < 768) {
            this.init()
        }
    },
    init() {
        const maxSymbols1 = 60;
        let $item = $('[data-news-text]');
        for (let i = 0; i < $item.length; i++) {
            if ($($($item)[i]).html().length > maxSymbols1) {
                $($($item)[i]).css('font-size', '8px');
            }
        }
    }
};
module.exports = newsText;