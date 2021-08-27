let cookie = {
    run() {
        let cookieNotify = getCookie('cookie_notify');

        if(!cookieNotify || cookieNotify != 'showed') {
            setTimeout(function() {
                $("#cookie-alert").removeClass("hidden");
            }, 1000);
        }

        $("#cookie-alert-close").click(function(){
            $("#cookie-alert").addClass("fadeOutDown");
            setTimeout(function() {
                $("#cookie-alert").addClass("hidden");
            }, 1000);
            setCookie('cookie_notify', 'showed', {'expires':86400 * 30, 'path':'/'});
        });


        // возвращает cookie с именем name, если есть, если нет, то undefined
        function getCookie(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }

        // устанавливает cookie с именем name и значением value
        // options - объект с свойствами cookie (expires, path, domain, secure)
        function setCookie(name, value, options) {
            options = options || {};

            let expires = options.expires;

            if (typeof expires == "number" && expires) {
                let d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            let updatedCookie = name + "=" + value;

            for (let propName in options) {
                updatedCookie += "; " + propName;
                let propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }

            document.cookie = updatedCookie;
        }

        // удаляет cookie с именем name
        function deleteCookie(name) {
            setCookie(name, "", {
                expires: -1
            })
        }

    }
};

module.exports = cookie;



