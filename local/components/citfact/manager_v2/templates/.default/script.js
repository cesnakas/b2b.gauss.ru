function reloadPlanFactData(e) {
    var select = $(e);
    var name = select.attr('name');
    switch (name) {
        case 'director':
            $('select[name="managers"]').val('');
            $('select[name="managers-tm"]').val('');
            break;
        case 'managers':
            $('select[name="managers-tm"]').val('');
            break;
    }
    $('#form_plan_fact').submit();
}

function setCheckboxChild(checkbox, value) {
    let type = checkbox.getAttribute('data-type');
    if (type === 'excludeManager') {
        let checkboxs = $(checkbox).closest('.account-table__wrap').find('.account-table__items .b-checkbox__input');
        if (value) {
            checkboxs.prop('checked', true);
            checkboxs.attr('checked', 'checked');
        } else {
            checkboxs.prop('checked', false);
            checkboxs.removeAttr('checked');
        }
        checkboxs.each(function () {
            setInputExcludeInForm(this);
        });
    }
    let checkboxsParents = $(checkbox).parents('.account-table__wrap');
    checkboxsParents.each(function () {
        let managerInput = $(this).find('> .account-table__row.manager .b-checkbox__input');
        if (value) {
            managerInput.prop('checked', true);
            managerInput.attr('checked', 'checked');
        } else {
            let inputSub = $(this).find('> .account-table__items .b-checkbox__input');
            let isAllTurnOffInputSub = true;
            inputSub.each(function () {
                if ($(this).prop('checked')) {
                    isAllTurnOffInputSub = false;
                }
            });
            if (isAllTurnOffInputSub) {
                managerInput.prop('checked', false);
                managerInput.removeAttr('checked');
            }
        }
        if (managerInput.length > 0) {
            setInputExcludeInForm(managerInput[0]);
        }
    });
}

function setInputExcludeInForm(checkbox) {
    var type = checkbox.getAttribute('data-type'),
        idElement = checkbox.getAttribute('data-id'),
        form = $('#form_plan_fact');

    if (checkbox.checked) {
        var selector = '[data-' + type + '="' + idElement + '"]';
        $(selector).remove();
    } else {
        var paste = document.createElement("input");
        paste.setAttribute('value', idElement);
        paste.setAttribute('name', type + '[]');
        paste.setAttribute('data-' + type, idElement);
        paste.style.display = "none";

        form.append(paste);
    }
}

$('.b-checkbox__input').on('click', function () {
    setInputExcludeInForm(this);
    if (this.checked) {
        setCheckboxChild(this, true);
    } else {
        setCheckboxChild(this, false);
    }
    var form = $('#form_plan_fact');

    BX.ajax({
        url: window.location.origin + window.location.pathname,
        method: 'POST',
        dataType: 'json',
        data: form.serialize(),
        onsuccess: BX.delegate(function (result) {
            BX.ajax.processScripts(
                BX.processHTML(result.JS).SCRIPT,
                false,
                BX.delegate(function () {
                    showAction(result);
                    setChart(result);
                }, this)
            );
        }, this)
    });
});

function showAction(result) {
    $.each(result.managers, function (id, manager) {
        let row = $('.account-table__row.manager[data-id-manager="' + id + '"]');
        row.find('[data-mplan-' + id + ']').text(number_format(manager.plan, 0, '.', ' '));
        row.find('[data-mfact-' + id + ']').text(number_format(manager.fact, 0, '.', ' '));
        row.find('[data-mpercent-' + id + ']').text(number_format(manager.percent, 0, '.', ' ') + '%');
        $('[data-saldo-' + id + ']').text(number_format(manager.saldo, 0, '.', ' '));
        if (manager.saldo === 0) {
            $('[data-saldo-wrap-id-' + id + ']').css('display', 'none');
        } else {
            $('[data-saldo-wrap-id-' + id + ']').css('display', 'flex');
        }
        var width = manager.percent;
        if (width > 100) {
            width = 100;
        }
        row.find('[data-mpercent-progress-' + id + ']').css('width', number_format(width, 0, '.', ' ') + '%');
    });
    $.each(result.kontragent, function (id, kontragent) {
        let row = $('.account-table__row.kontragent[data-id-kontragent="' + id + '"]');
        row.find('[data-mplan-' + id + ']').text(number_format(kontragent.plan, 0, '.', ' '));
        row.find('[data-mfact-' + id + ']').text(number_format(kontragent.fact, 0, '.', ' '));
        row.find('[data-mpercent-' + id + ']').text(number_format(kontragent.percent, 0, '.', ' ') + '%');
        var width = kontragent.percent;
        if (width > 100) {
            width = 100;
        }
        row.find('[data-mpercent-progress-' + id + ']').css('width', number_format(width, 0, '.', ' ') + '%');
    });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
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
    getAttrValueGraphRed(result.pdzChart, result.dzChart);
    $('[data-pdz]').text(number_format(result.pdzChart, 0, '.', ' ') + ' / ');
    $('[data-dz]').text(number_format(result.dzChart, 0, '.', ' '));

    getAttrValueGraphGreen(result.factChart, result.planChart);
    $('[data-fact]').text(number_format(result.factChart, 0, '.', ' ') + ' / ');
    $('[data-plan]').text(number_format(result.planChart, 0, '.', ' '));
}

$('[data-toggle-btn]').on('click.toggle', function (e) {
    var idManager = this.getAttribute('data-manager'),
        openManager = localStorage.getItem('open_manager');
    if (typeof openManager != 'undefined' &&  openManager && openManager.length > 0) {
        var openManagerArray = openManager.split(';'),
            index = openManagerArray.indexOf(idManager);
    }

    if ($(this).hasClass('active') && typeof openManager != 'undefined' && openManager && openManager.length > 0) {
        if (index > -1) {
            openManagerArray.splice(index, 1);
            openManager = openManagerArray.join(';');
        }
    } else {
        if (openManager && openManager.length > 0) {
            if (index == -1) {
                openManager += ';' + idManager;
            }
        } else {
            openManager = idManager;
        }
    }
    if ($('.is_search_string').length <= 0) {
        localStorage.setItem('open_manager', openManager);
    }
});


function setWidthFormSearch() {
    if ($(window).width() < 767) {
        return;
    }
    var formSearch = $('.b-form.account-search:first');
    if (formSearch.length > 0) {
        var column = $('.kontragent > .account-table__check:first');
        if (column.length > 0) {
            var marginWidht = formSearch.outerWidth(true) - formSearch.width();
            var accountTable = $('.account-table:first');
            var widthInputSearch = column.offset().left - accountTable.offset().left - marginWidht;
            if (widthInputSearch > 0) {
                formSearch.width(widthInputSearch);
            }
        }
    }
}

setWidthFormSearch();
$(window).resize(function () {
    setWidthFormSearch();
});

document.addEventListener('App.Ready', function (e) {
     var openManager = localStorage.getItem('open_manager');
     if ($('.is_search_string').length > 0) {
        $('[data-toggle-list]').each(function (i, el){
            var th = $(el);
            if(th.attr('style') !== 'display: block;'){
                var managerBtn = th.parent().find('[data-id-manager]').find('[data-toggle-btn]');
                 if(managerBtn){
                     th.addClass('active');
                }

                th.attr('style','display:block');
                 $('[data-toggle-btn]').addClass('active');
                 $('[data-toggle-wrap]').addClass('active');

            }
        });
    } else if (typeof openManager != 'undefined' && openManager && openManager.length > 0) {
        openManager = openManager.split(';');
        $.each(openManager, function (idManager, managers) {
            if (managers) {
                var togglList = $('[data-toggle-manager-' + managers + ']').parent().siblings('[data-toggle-list]');
                if(togglList.attr('style') !== 'display: block;'){
                    $('[data-toggle-manager-' + managers + ']').click();
                }
            }
        });
     }
});
