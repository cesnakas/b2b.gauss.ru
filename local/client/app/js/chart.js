import Chart from 'chart.js';

let chart = {
    run() {
        
        if(!$('[data-chart]').length)
            return;
    
        this.data = [];
        this.chart = null;
        this.windowPosition = null;
        
        this.set(window.dataChart);
    
        $(document).on('click', '[data-chart-link]:not(.active)' , (e) => {
            this.windowPosition = $(window).scrollTop();
            e.preventDefault();
        
            $('[data-chart-link].active').removeClass('active');
        
            $(e.target).addClass('active');
        
            this.set();
            /*this.chart.update();*/

            $(window).scrollTop(this.windowPosition);
        });
    },
    set(data) {
        if(this.chart)
            this.chart.destroy();
        
        if (data)
            this.data = data;
        else
            data = this.data;

        let $activeLink = $('[data-chart-link].active');
        let ctx = document.querySelector('[data-chart]').getContext('2d');
        
        let currentData = $activeLink.data('chart-link');
        let isYearChartDesctop = currentData === 2 && window.innerWidth >= 768;

        let planData;
        if(data[currentData] && data[currentData][0] && data[currentData][0].length) {
            $('.lk-chart__legend--plan').show();
            planData = {
                label: 'План',
                data: data[currentData][0],
                backgroundColor: '#5BCEFF'
            };
        } else {
            $('.lk-chart__legend--plan').hide();
        }

        let factData;
        if(data[currentData] && data[currentData][1] && data[currentData][1].length) {
            $('.lk-chart__legend--fact').show();
            factData = {
                label: 'Факт',
                data: data[currentData][1],
                backgroundColor: '#F7971D'
            };
        } else {
            $('.lk-chart__legend--fact').hide();
        }


        let bgc;
    
        let chartOptions = {
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            },

            tooltips: {
                mode: 'point',
                caretSize: 0,
                xPadding: 10,
                yPadding: 10,
                displayColors: false,
                position: 'nearest',
                cornerRadius: 15,
                bodyFontSize: 14,

                callbacks: {
                    title: function () {
                        return false
                    },
                    label: function(t, d) {
                        // bgc = d.datasets[t.datasetIndex].backgroundColor;

                        const val = isYearChartDesctop ? t.xLabel : t.yLabel;

                        return val.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' ₽';
                    },
                }
            },
            title: {
                display: false,
            },
            legend: {
                display: false
            }
        };
        
        if(isYearChartDesctop)
            chartOptions.scales = {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                yAxes: [{
                    barPercentage: 0.8,
                    maxBarThickness: 60,
                    categoryPercentage: .5,
                    gridLines: {
                        display:false
                    }
                }]
            };
        else
            chartOptions.scales = {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    barPercentage: 0.6,
                    maxBarThickness: 60,
                    categoryPercentage: .5,
                    gridLines: {
                        display:false
                    }
                }]
            };

        let planetData;
        if (data[currentData] && (data[currentData][1] || data[currentData][2]) && (planData || factData)) {
            if (factData) {
                planetData = {
                    labels: (data[currentData][2]) ?  data[currentData][2] : [],
                    datasets: planData && planData.data && planData.data.length ? [planData, factData] : [factData]
                };
            } else {
                planetData = {
                    labels: (data[currentData][2]) ?  data[currentData][2] : [],
                    datasets: factData && factData.data && factData.data.length ? [planData, factData] : [planData]
                };
            }
        }

        if (planetData) {
            this.chart = new Chart(ctx, {
                type: isYearChartDesctop ? 'horizontalBar' : 'bar',
                data: planetData,
                options: chartOptions
            });
        }
    }
};

module.exports = chart;