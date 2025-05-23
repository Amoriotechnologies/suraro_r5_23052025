(function () {
    "use strict";
    
    /* pie chart */
    const data2 = {
        labels: [
            'Red',
            'Blue',
            'Yellow'
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100],
            backgroundColor: [
                'rgb(132, 90, 223)',
                'rgb(35, 183, 229)',
                'rgb(245, 184, 73)'
            ],
            hoverOffset: 4
        }]
    };
    const config3 = {
        type: 'pie',
        data: data2,
    };
    const myChart2 = new Chart(
        document.getElementById('chartjs-pie'),
        config3
    );

    /* doughnut chart */
    const data3 = {
        labels: [
            'Red',
            'Blue',
            'Yellow'
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100],
            backgroundColor: [
                'rgb(132, 90, 223)',
                'rgb(35, 183, 229)',
                'rgb(245, 184, 73)'
            ],
            hoverOffset: 4
        }]
    };
    const config4 = {
        type: 'doughnut',
        data: data3,
    };
    const myChart3 = new Chart(
        document.getElementById('chartjs-doughnut'),
        config4
    );

    /* mixed chart */
    const data4 = {
        labels: [
            'January',
            'February',
            'March',
            'April'
        ],
        datasets: [{
            type: 'bar',
            label: 'Bar Dataset',
            data: [10, 20, 30, 40],
            borderColor: 'rgb(132, 90, 223)',
            backgroundColor: 'rgba(132, 90, 223, 0.2)'
        }, {
            type: 'line',
            label: 'Line Dataset',
            data: [50, 50, 50, 50],
            fill: false,
            borderColor: 'rgb(35, 183, 229)'
        }]
    };
    const config5 = {
        type: 'scatter',
        data: data4,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    const myChart4 = new Chart(
        document.getElementById('chartjs-mixed'),
        config5
    );

    /* polar area chart */
    const data5 = {
        labels: [
            'Red',
            'Green',
            'Yellow',
            'Grey',
            'Blue'
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [11, 16, 7, 3, 14],
            backgroundColor: [
                'rgb(132, 90, 223)',
                'rgb(75, 192, 192)',
                'rgb(245, 184, 73)',
                'rgb(201, 203, 207)',
                'rgb(35, 183, 229)'
            ]
        }]
    };
    const config6 = {
        type: 'polarArea',
        data: data5,
        options: {}
    };
    const myChart5 = new Chart(
        document.getElementById('chartjs-polar'),
        config6
    );

    /* radial chart */
    const data6 = {
        labels: [
            'Eating',
            'Drinking',
            'Sleeping',
            'Designing',
            'Coding',
            'Cycling',
            'Running'
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [65, 59, 90, 81, 56, 55, 40],
            fill: true,
            backgroundColor: 'rgba(132, 90, 223, 0.2)',
            borderColor: 'rgb(132, 90, 223)',
            pointBackgroundColor: 'rgb(132, 90, 223)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(132, 90, 223)'
        }, {
            label: 'My Second Dataset',
            data: [28, 48, 40, 19, 96, 27, 100],
            fill: true,
            backgroundColor: 'rgba(35, 183, 229, 0.2)',
            borderColor: 'rgb(35, 183, 229)',
            pointBackgroundColor: 'rgb(35, 183, 229)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(35, 183, 229)'
        }]
    };
    const config7 = {
        type: 'radar',
        data: data6,
        options: {
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        },
    };
    const myChart6 = new Chart(
        document.getElementById('chartjs-radar'),
        config7
    );

    /* scatter chart */
    const data7 = {
        datasets: [{
            label: 'Scatter Dataset',
            data: [{
                x: -10,
                y: 0
            }, {
                x: 0,
                y: 10
            }, {
                x: 10,
                y: 5
            }, {
                x: 0.5,
                y: 5.5
            }],
            backgroundColor: 'rgb(132, 90, 223)'
        }],
    };
    const config8 = {
        type: 'scatter',
        data: data7,
        options: {
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom'
                }
            }
        }
    };
    const myChart7 = new Chart(
        document.getElementById('chartjs-scatter'),
        config8
    );

    /* bubble chart */
    const data8 = {
        datasets: [{
            label: 'First Dataset',
            data: [{
                x: 20,
                y: 30,
                r: 15
            }, {
                x: 40,
                y: 10,
                r: 10
            }],
            backgroundColor: 'rgb(132, 90, 223)'
        }]
    };
    const config9 = {
        type: 'bubble',
        data: data8,
        options: {}
    };
    const myChart8 = new Chart(
        document.getElementById('chartjs-bubble'),
        config9
    );

})();