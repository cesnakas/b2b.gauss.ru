let ajaxLoad = {
    run() {
        this.autoloadBlocks();
    },

    load(params, noLoader) {
        if (noLoader !== true) {
            BX.showWait();
        }
        params.async = (typeof params.async !== 'undefined') ? params.async : true;
        if (!params.container) {
            console.error('Empty container parameter for AjaxLoad.load().');
            return;
        }
        if (!params.type) {
            params.type = 'POST';
        }
        if (!params.url) {
            params.url = window.location.pathname;
        }
        if (!params.data) {
            params.data = new FormData;
        }
        if (!params.dataType) {
            params.dataType = 'html';
        }
        if (params.data instanceof FormData) {
            params.data.append('AJAX_LOAD', 'Y');
            params.processData = false;
            params.contentType = false;
        } else {
            params.data['AJAX_LOAD'] = 'Y';
        }
        if (!params.noContainer) {
          params.noContainer = false;
        }

        let callback = params.success;
        let noContainerCallback = params.noContainer;
        params.success = function (data) {
            let isJson = false;
            try
            {
                let json = JSON.parse(data);
                isJson = true;

            } catch(e) {
                isJson = false;
            }

            if (isJson) {

            } else {
                if (typeof params.container === 'string') {
                    params.container = [params.container];
                }

                if (params.additionalContainers) {
                    if (typeof params.additionalContainers === 'string') {
                        params.additionalContainers = [params.additionalContainers];
                    }
                    params.container = params.container.concat(params.additionalContainers);
                }

                $(params.container).each((i, container) => {
                    let html = $(data).find(container).addBack(container).html();
                    if (!html) {
                        if (typeof noContainerCallback === 'function') {
                            noContainerCallback();
                        } else {
                            console.error('Container ' + container + ' not found in current page.');
                        }
                    }
                    $(container).html(html);
                });
            }

            BX.closeWait();

            if (typeof callback === 'function') {
                callback(data);
            }
        };

        $.ajax(params);
    },

    autoloadBlocks() {
        let self = this;
        $('[data-ajax-load]').each(function () {
            let $this = $(this);
            let url = $this.attr('data-ajax-load');
            self.load({
                url: url,
                container: '[data-ajax-load="' + url + '"]'
            }, true);
        });
    }
};

module.exports = ajaxLoad;
