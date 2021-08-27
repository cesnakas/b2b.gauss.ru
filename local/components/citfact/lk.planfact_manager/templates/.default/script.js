function reloadPlanFactData(select) {
    let year = $(select).val();
    $('#operation').val('reload');

    if ($('#setFilterManagerPlan').length > 0) {
        $('#filter_manager_plan').val($('#setFilterManagerPlan').val());
    }
    $('#form_plan_fact').submit();
}

$('#form_plan_fact').submit(validateInput);
$('[data-bitness]').on("keyup", function () {
    this.value = this.value.replace(/ /g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " ");
});

function validateInput() {
    $('#message').hide();
    let january = $('#january');
    let february = $('#february');
    let march = $('#march');
    let may = $('#may');
    let june = $('#june');
    let july = $('#july');
    let august = $('#august');
    let september = $('#september');
    let october = $('#october');
    let november = $('#november');
    let december = $('#december');


    let isOk = true;

    let moneyRegEx = /^[\d\s]+(\.\d\d)?$/; //регулярка на проверку формата денег

    if (moneyRegEx.test(january.val()) == false) {
        january.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        january.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(february.val()) == false) {
        february.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        february.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(march.val()) == false) {
        march.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        march.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(may.val()) == false) {
        may.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        may.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(june.val()) == false) {
        june.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        june.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(july.val()) == false) {
        july.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        july.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(august.val()) == false) {
        august.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        august.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(september.val()) == false) {
        september.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        september.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(october.val()) == false) {
        october.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        october.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(november.val()) == false) {
        november.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        november.siblings(".b-form__text").hide();
    }

    if (moneyRegEx.test(december.val()) == false) {
        december.siblings(".b-form__text").text("Не верный формат введенных данных.").show();
        isOk = false;
    } else {
        december.siblings(".b-form__text").hide();
    }

    if (!isOk)
        return isOk;
}

$(document).on('click', '[data-tab-btn]', (e) => {
    e.preventDefault();
    e.stopPropagation();
    var tab = $(e.target).attr('data-tab-btn');
    sessionStorage.setItem('tab', tab);
});
document.addEventListener('App.Ready', function (e) {
    $('[data-tab-btn="' + sessionStorage.getItem('tab') + '"]')[0].click();
});
