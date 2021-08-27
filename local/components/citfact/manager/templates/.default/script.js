function reloadPlanFactData() {
    $('#form_plan_fact').submit();
}

$('.b-checkbox__input').on('click', function(){
    var type = this.getAttribute('data-type'),
        idElement = this.getAttribute('data-id'),
        form = $('#form_plan_fact');

    if (this.checked) {
        var selector = '[data-'+type+'="' + idElement + '"]';
        $(selector).remove();
    } else {
        var paste = document.createElement("input");
        paste.setAttribute('value', idElement);
        paste.setAttribute('name', type + '[]');
        paste.setAttribute('data-' + type, idElement);
        paste.style.display = "none";

        form.append(paste);
    }

    BX.ajax({
        url: window.location.origin + window.location.pathname,
        method: 'POST',
        dataType: 'json',
        data: form.serialize(),
        onsuccess: BX.delegate(function (result) {
            if (!result || !result.items)
                return;
            BX.ajax.processScripts(
                BX.processHTML(result.JS).SCRIPT,
                false,
                BX.delegate(function () {
                    showAction(result.items);
                    setChart(result.all);
                }, this)
            );
        }, this)
    });
});

function showAction(result) {
    $.each(result, function( idManager, managers ) {
        setDataManager(managers);
        if(managers.ITEMS) {
            setDataPartner(managers.ITEMS);
        }
        if (typeof managers.SUB != 'undefined') {
            showAction(managers.SUB);
        }
    });
}

function setDataManager(manager) {
    var allPlan = manager.ALL_PLAN,
        percent = 0;
    if (allPlan != 0) {
        percent = manager.ALL_FACT * 100 / allPlan;
        percent = number_format(percent, 0, '.', ' ');
    }
    if (allPlan == 0 && manager.AUTOPLAN) {
        allPlan = manager.ALL_AUTOPLAN;
    }
    allPlan = number_format(allPlan, 0, '.', ' ');
    var fact = number_format(manager.ALL_FACT, 0, '.', ' '),
        pdz = number_format(manager.ALL_PDZ, 0, '.', ' ');

    $('[data-mplan-' + manager.ID + ']').text(allPlan);
    $('[data-mfact-' + manager.ID + ']').text(fact);
    $('[data-mplan-fact-mobile-' + manager.ID + ']').text(allPlan + '/' + fact);
    $('[data-mpercent-progress-' + manager.ID + ']').css('width', percent <=100 ? percent : 100);
    $('[data-mpercent-' + manager.ID + ']').text(percent + '%');
    $('[data-mpdz-' + manager.ID + ']').text(pdz);
}

function setDataPartner($partners) {
    $.each($partners, function( id, partner ) {
        var plan = partner.PLAN;
        if (plan == 0 && partner.AUTOPLAN) {
            plan = partner.AUTOPLAN;
        }
        plan = number_format(plan, 0, '.', ' ');
        var fact = number_format(partner.FACT, 0, '.', ' '),
            pdz = number_format(partner.PDZ, 0, '.', ' ');

        $('[data-plan-' + partner.ID + ']').text(plan);
        $('[data-fact-' + partner.ID + ']').text(fact);
        $('[data-plan-fact-mobile-' + partner.ID + ']').text(plan + '/' + fact);
        $('[data-percent-progress-' + partner.ID + ']').css('width', partner.PERCENT <=100 ? partner.PERCENT : 100);
        $('[data-percent-' + partner.ID + ']').text(partner.PERCENT + '%');
        $('[data-pdz-' + partner.ID + ']').text(pdz);

        if (partner.EXCLUDE) {
            $('#agent-'+partner.ID).prop('checked', false);
            $('#agent-'+partner.ID).removeAttr('checked');
        } else  {
            $('#agent-'+partner.ID).prop('checked', true);
            $('#agent-'+partner.ID).attr('checked', true);
        }
    });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

function setChart(result) {
    getAttrValueGraphRed(result.ALL_PDZ, result.ALL_DZ);
    $('[data-pdz]').text(number_format(result.ALL_PDZ, 0, '.', ' ') + ' / ');
    $('[data-dz]').text(number_format(result.ALL_DZ, 0, '.', ' '));

    getAttrValueGraphGreen(result.ALL_FACT,result.ALL_PLAN);
    $('[data-fact]').text(number_format(result.ALL_FACT, 0, '.', ' ') + ' / ');
    $('[data-plan]').text(number_format(result.ALL_PLAN, 0, '.', ' '));
}

$('[data-toggle-btn]').on('click.toggle', function (e) {
    var idManager = this.getAttribute('data-manager'),
        openManager = getCookie('open_manager');

    if (openManager.length > 0) {
        var openManagerArray = openManager.split(';'),
            index = openManagerArray.indexOf(idManager);
    }

    if ($(this).hasClass('active') && openManager.length > 0) {
        if (index > -1) {
            openManagerArray.splice(index, 1);
            openManager = openManagerArray.join(';');
        }
    } else {
        if (openManager.length > 0) {
            if (index == -1) {
                openManager += ';' + idManager;
            }
        } else {
            openManager = idManager;
        }
    }
    document.cookie = 'open_manager=' +   encodeURIComponent(openManager);
});

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        '(?:^|\s)' + name.replace(/([.$?*+\\\/{}|()\[\]^])/g, '\\$1') + '=(.*?)(?:;|$)'
    ));
    return matches ? decodeURIComponent(matches[1]) : '';
}

document.addEventListener('App.Ready', function (e) {
    var openManager = getCookie('open_manager');
    if (openManager.length > 0) {
        openManager = openManager.split(';');
        $.each(openManager, function( idManager, managers ) {
            if (managers) {
                $('[data-toggle-manager-'+managers+']').click();
            }
        });
    }
});