let editorTableMobile = {
    run() {
        if($(window).innerWidth() >= 768)
            return;
    
        $('table').each(function () {
            let $currentTable = $(this);
            let $currentHeader = $currentTable.find('tr:first-of-type > td:not(:first-of-type)');
            let $currentHeaderLength = $currentHeader.length;
            let $tableCells = $currentTable.find('tr:not(:first-of-type) > td:not(:first-of-type)');

            $tableCells.each(function (index) {
                $(this).prepend('<span>'+$currentHeader[(index)%$currentHeaderLength].innerText+'</span>');
            })
        });
    }
};

module.exports = editorTableMobile;