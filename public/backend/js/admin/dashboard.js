(function ($) {
    "use strict";

    var ctx = document.getElementById("earnSource");
    var myPieChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["الموقع الإلكتروني", "الواتس اب"],
            datasets: [{
                data: $('#order_pie').data("dt"),
                label: "مصدر الطلبات",
                backgroundColor: ['#33C4FF', '#2AD3C3'],
                hoverBackgroundColor: ['#1C7193', '#1C8279'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });

    var ctxS = document.getElementById("salesRatio");
    var myPieChartS = new Chart(ctxS, {
        type: 'doughnut',
        data: {
            labels: ["Delivered", "Returned"],
            datasets: [{
                data: $('#sales_ratio').data("dt"),
                backgroundColor: ['#30DCA3', '#D94932'],
                hoverBackgroundColor: ['#218A67', '#903122'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
})(jQuery);
