window.onload = function() {
    $(document).on('click', '[data-presentation-section]', function() {
        var $this = $(this);
        var files = [];
        var sectionId = $this.data('presentation-section');
        $this.closest('[data-toggle-wrap]').find('[data-presentation-section-file]').each((key, element) => {
            files.push(element.getAttribute('href'));
        });
        if (files) {
            $.ajax({
                url: '/local/include/ajax/makeArchive.php',
                method: 'post',
                data: {files: files, sectionId: sectionId},
            }).done(function (result) {
                if(result){
                    window.open(result);
                }
            });
        } else {
            alert('Нет файлов.')
        }
    });
};