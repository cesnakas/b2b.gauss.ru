let map = {
    run() {
        ymaps.ready(function () {
            let cMap = new ymaps.Map("cMap", {
                center: [55.737569, 37.636729],
                zoom: 16,
                controls: []
            });

            cMap.geoObjects.add(new ymaps.Placemark([55.737569, 37.636729], {
                    balloonContent: 'text'
                },
                {
                    preset: 'islands#icon',
                    iconColor: '#ff3333',
                    hideIconOnBalloonOpen: false,
                    balloonOffset: [0, -26]
                })
            );
        });
    },
};
module.exports = map;