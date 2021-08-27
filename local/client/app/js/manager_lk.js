import $ from "jquery";
import selectric from "selectric";

function initSelect(e) {
    var n = e.is(".select--white") && e.is(".select--gray"),
      r = void 0,
      i = void 0,
      o = void 0,
      s = void 0,
      a = void 0;
    e.selectric({
      customClass: {
        prefix: "select",
        camelCase: !1
      },
      onInit: function () {
        var e = $(this);
        r = e.parents(".select-wrapper").find("ul"),
        o = e.parents(".select-wrapper").find(".select"),
        s = r.find("li"),
        n && o.addClass("select--white"),
        e.addClass("hidden"),
        r.addClass("select-ul");
        var i = e.parents(".select-wrapper").find(".select-scroll");
        i.length > 0 && (a = new PerfectScrollbar(i[0], {
          wheelSpeed: .5,
          wheelPropagation: !1,
          minScrollbarLength: 20,
          suppressScrollX: !0
        }))
      },
      onOpen: function () {
        var n = $(this);
        if (n.parents(".b-form__item").find(".b-form__label").length && n.parents(".b-form__item").find(".b-form__label").addClass("active"), a.update(), e.parents("[data-select-wrap]")) {
          var r = e.parents("[data-select-wrap]").find("[data-select-sort]");
          r.focus(), r.on("input", (function () {
            r.val() ? e.parents(".select-wrapper").addClass("select-sorted") : e.parents(".select-wrapper").removeClass("select-sorted")
          }))
        }
      },
      onClose: function () {
        var n = $(this);
        if (n.parents(".b-form__item").find(".b-form__label").length && "" === n.val() && n.parents(".b-form__item").find(".b-form__label").removeClass("active"), e.parents("[data-select-wrap]")) {
          var r = e.parents("[data-select-wrap]").find("[data-select-sort]");
          r.blur(), r.val() ? e.parents(".select-wrapper").addClass("select-sorted") : e.parents(".select-wrapper").removeClass("select-sorted")
        }
      },
      onChange: function () {
        $(this).trigger("change")
      },
      arrowButtonMarkup: "",
      maxHeight: "auto",
      disableOnMobile: !1,
      nativeOnMobile: !1
    })
    
}

//FOR AJAX GRAPH
window.getAttrValueGraphRed = function(currentVal, maxVal) {
  document.querySelector('#myChartRed').dataset.current = currentVal;
  document.querySelector('#myChartRed').dataset.max = maxVal;
  var percentVal = 0;
  if (maxVal > 0) {
    percentVal = Math.round((currentVal / maxVal) * 100);
  }
  var maxPercent = 100 - percentVal;
  
  document.querySelector('#myChartRed').nextElementSibling.innerText=`${percentVal}%`;
  if(maxPercent < 0) {
    maxPercent = 0;
  }

  chartR.data.datasets = [{
    borderWidth: 0,
    backgroundColor: ['red', '#f0f2f3'],
    data: [percentVal, maxPercent],
  }];
  chartR.update();
};

window.getAttrValueGraphGreen = function(currentVal, maxVal) {
  document.querySelector('#myChartGreen').dataset.current = currentVal;
  document.querySelector('#myChartGreen').dataset.max = maxVal;
  var percentVal = 0;
  if (maxVal > 0) {
    percentVal = Math.round((currentVal / maxVal) * 100);
  }
  var maxPercent = 100 - percentVal;
  
  document.querySelector('#myChartGreen').nextElementSibling.innerText=`${percentVal}%`;
  if(maxPercent < 0) {
    maxPercent = 0;
  }

  chartG.data.datasets = [{
    borderWidth: 0,
    backgroundColor: ['#40d24a', '#f0f2f3'],
    data: [percentVal, maxPercent],
  }];
  chartG.update();
};



// DateRangePicker
$('.js-date-picker').daterangepicker({
  showDropdowns: true,
  "locale": {
    "format": "DD/MM/YYYY",
    "separator": " - ",
    "applyLabel": "Сохранить",
    "cancelLabel": "Назад",
    "daysOfWeek": [
        "Вс",
        "Пн",
        "Вт",
        "Ср",
        "Чт",
        "Пт",
        "Сб"
    ],
    "monthNames": [
        "Январь",
        "Февраль",
        "Март",
        "Апрель",
        "Май",
        "Июнь",
        "Июль",
        "Август",
        "Сентябрь",
        "Октябрь",
        "Ноябрь",
        "Декабрь"
    ],
  }
});
$('.js-date-picker').on('apply.daterangepicker', function(ev, picker) {

  var start         = picker.startDate.format('DD.MM.YYYY'),
      end           = picker.endDate.format('DD.MM.YYYY');

  $(this).text(`Период: ${start} - ${end}`);
  var params = getParams();
  if (params) {
    if (params['period']) {
      params['period'] = "from_" + start + "_to_" + end;
      var url = window.location.origin + window.location.pathname + "?";
      var keys = Object.keys(params);
      keys.forEach(function (item, indx) {
        if (indx != 0) {
          url = url + "&"
        }
        url = url + item + "=" + params[item];
      });
      document.location.href = url;
    } else {
      document.location.href = window.location.href + "&period=from_" + start + "_to_" + end;
    }
  } else {
    document.location.href = window.location.href + "?period=from_" + start + "_to_" + end;
  }

  $('input[name="daterange"]').attr('name', `${start}_${end}`);
  $('.js-account-tab').removeClass('is-active');
});
// TAB
var tab = function() { 
  var tabNav = document.querySelectorAll('.js-account-tab');
  for(let i = 0; i < tabNav.length; i++) {
    tabNav[i].addEventListener('click', selectTabNav);
  } 
  var tabName;

  function selectTabNav() {
    for(let i = 0; i < tabNav.length; i++) {
      tabNav[i].classList.remove('is-active');
    }
    this.classList.add('is-active');
    tabName = this.getAttribute('data-tab-name');
    selectTabContent(tabName);
  }

  function selectTabContent(tabName) {
    var tabContent = document.querySelectorAll('.js-tab-content');
    for(let i = 0; i < tabContent.length; i++) {
      if(tabContent[i].classList.contains(tabName)) {
        tabContent[i].style.display="block";
      } else {
        tabContent[i].style.display="none";
      }
    }
  }

};
tab();

$('li.js-tab-options').on('click', function(){

  var selectVal = document.querySelector('#tab-year');
  var tabOptions = document.querySelectorAll('.js-year option.js-tab-options');
  var tabLi = document.querySelectorAll('.js-year li.js-tab-options');
  for(let i = 0; i < tabLi.length; i++) {
    if(tabLi[i].classList.contains('selected')) {
      var selected = i;
    }
    for(let k = 0; k < tabOptions.length; k++) {
      if(k == selected) {
        tabOptions[k].setAttribute('selected', '');
        selectVal.val = tabOptions[k].textContent;
      } else {
        tabOptions[k].removeAttribute('selected');
      }
    }
  }
  
  $('#tab-year').trigger('change');
});

$('li.js-mobile-options').on('click', function() {
  var selectVal = document.querySelector('#mobile-filter');
  var tabOptions = document.querySelectorAll('.js-mobile-filter option.js-mobile-options');
  var tabLi = document.querySelectorAll('.js-mobile-filter li.js-mobile-options');
  for(let i = 0; i < tabLi.length; i++) {
    if(tabLi[i].classList.contains('selected')) {
      var selected = i;
    }
    for(let k = 0; k < tabOptions.length; k++) {
      if(k == selected) {
        tabOptions[k].setAttribute('selected', '');
        selectVal.val = tabOptions[k].textContent;
      } else {
        tabOptions[k].removeAttribute('selected');
      }
    }
  }
  $('#mobile-filter').trigger('change');
});

//FOR Red graph
if(!!document.getElementById('myChartRed')) {
  var chartRed    = document.getElementById('myChartRed').getContext('2d');
  var dataCurrent = document.querySelector('#myChartRed').dataset.current;
  var dataMax     = document.querySelector('#myChartRed').dataset.max;
  var percentVal  = ((dataCurrent / dataMax) * 100).toFixed(1);
  var maxPercent  = 100 - percentVal;

  if ( dataMax == 0 ) {

    document.querySelector('#myChartRed').nextElementSibling.innerText='Нет данных';
  } else {

    document.querySelector('#myChartRed').nextElementSibling.innerText=`${percentVal}%`;
  }

  if(maxPercent < 0) {
    maxPercent = 0;
  }

  var chartR = new Chart(chartRed, {
      type: 'doughnut',
      data: {
          labels: false,
          datasets: [{
              borderWidth: 0,
              backgroundColor: ['red', '#f0f2f3'],
              data: [percentVal, maxPercent],
          }]
      },

      options: {
        layout: {
          padding: {
            top: 10,
            left: 17,
            bottom: 17,
            right: 17,
          }
        },
        cutoutPercentage: 115
      }
  });
}

//FOR Green graph
if(!!document.getElementById('myChartGreen')) {
  var chartGreen  = document.getElementById('myChartGreen').getContext('2d');
  var dataCurrent = document.querySelector('#myChartGreen').dataset.current;
  var dataMax     = document.querySelector('#myChartGreen').dataset.max;
  var percentVal  = ((dataCurrent / dataMax) * 100).toFixed(1);
  var maxPercent  = 100 - percentVal;

  if ( dataMax == 0 ) {

    document.querySelector('#myChartGreen').nextElementSibling.innerText='Нет данных';
  } else {

    document.querySelector('#myChartGreen').nextElementSibling.innerText=`${percentVal}%`;
  }

  if(maxPercent < 0) {
    maxPercent = 0;
  }

  var chartG = new Chart(chartGreen, {
      type: 'doughnut',
      data: {
          labels: false,
          datasets: [{
              borderWidth: 0,
              backgroundColor: ['#40d24a', '#f0f2f3'],
              data: [percentVal, maxPercent],
          }]
      },

      options: {
        layout: {
          padding: {
            top: 10,
            left: 17,
            bottom: 17,
            right: 17,
          }
        },
        cutoutPercentage: 115
      }
  });
}

$(document).ready(function() {
  // выставление сортировки таблицы
  $('.sort_table').click(function() {
      var params = getParams();

      if (params) {
        if (params['sort']) {
          params['sort'] = (this).dataset.sort;
          params['dir'] = (this).dataset.direction;
          var url = window.location.origin + window.location.pathname + "?";
          var keys = Object.keys(params);
          keys.forEach(function (item, indx) {
            if (indx != 0) {
              url = url + "&"
            }
            url = url + item + "=" + params[item];
          });
          document.location.href = url;
        } else {
          document.location.href = window.location.href + "&sort=" + (this).dataset.sort + "&dir=" + (this).dataset.direction;
        }
      } else {
        document.location.href = window.location.href + "?sort=" + (this).dataset.sort + "&dir=" + (this).dataset.direction;
      }
  });

  $("#tab-year").change(function(){
    var params = getParams();
    if (params) {
      if (params['period']) {
        params['period'] = "year_" + $(this).val();
        var url = window.location.origin + window.location.pathname + "?";
        var keys = Object.keys(params);
        keys.forEach(function (item, indx) {
          if (indx != 0) {
            url = url + "&"
          }
          url = url + item + "=" + params[item];
        });
        document.location.href = url;
      } else {
        document.location.href = window.location.href + "&period=year_" + $(this).val();
      }
    } else {
      document.location.href = window.location.href + "?period=year_" + $(this).val();
    }
  });

  $("#mobile-filter").on('change', function() {
    console.log('wadawd');
    var params = getParams();
    var sort = $(this).val().split('_')[0];
    var dir = $(this).val().split('_')[1];
    if (params) {
      if (params['sort']) {
        params['sort'] = sort;
        params['dir'] = dir;
        var url = window.location.origin + window.location.pathname + "?";
        var keys = Object.keys(params);
        keys.forEach(function (item, indx) {
          if (indx != 0) {
            url = url + "&"
          }
          url = url + item + "=" + params[item];
        });
        document.location.href = url;
      } else {
        document.location.href = window.location.href + "&sort=" + sort + "&dir=" + dir;
      }
    } else {
      document.location.href = window.location.href + "?sort=" + sort + "&dir=" + dir;
    }
  });
});



function getParams() {
  if (window.location.search) {
    var params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            var a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );
  };
  
  return params;
}
