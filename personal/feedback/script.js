document.addEventListener('App.Ready', function (e) {
    Am.dropDragInputFile.run();
});
document.addEventListener('keydown', function(event) {
    if (event.code == 'KeyV' && (event.ctrlKey || event.metaKey))
    {
        Am.dropDragInputFile.PastImageBuffer();
    }
});