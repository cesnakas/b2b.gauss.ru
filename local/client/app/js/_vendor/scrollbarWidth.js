let scrollbarWidth = {
    run: function() {
        let $doc = $('html');
        let $status = $doc.css('overflow-y');
        let w1 = $doc.innerWidth();
        $doc.css('overflow-y', 'hidden');
        let w2 = $doc.innerWidth();
        $doc.css('overflow-y', $status);
        return (w2 - w1);
    }
};

module.exports = scrollbarWidth;