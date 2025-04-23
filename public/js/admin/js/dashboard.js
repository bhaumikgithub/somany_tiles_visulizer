(function($) {
  'use strict';
  $(function() {

    var backgrounColors = [
      'rgba(255, 99, 132, 0.5)',
      'rgba(54, 162, 235, 0.5)',
      'rgba(255, 206, 86, 0.5)',
      'rgba(75, 192, 192, 0.5)',
      'rgba(153, 102, 255, 0.5)',
      'rgba(255, 159, 64, 0.5)',
      'rgba(100, 200, 86, 0.5)',
    ];

    // Remove pro banner on close
   
    if ($('#circleProgress6').length) {
      var bar = new ProgressBar.Circle(circleProgress6, {
        color: '#001737',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 10,
        trailWidth: 10,
        easing: 'easeInOut',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: {
          color: '#aaa',
          width: 10
        },
        to: {
          color: '#2617c9',
          width: 10
        },
        // Set default step function for all animate calls
        step: function(state, circle) {
          circle.path.setAttribute('stroke', state.color);
          circle.path.setAttribute('stroke-width', state.width);
  
          var value = '<p class="text-center mb-0">Score</p>' + Math.round(circle.value() * 100) + "%";
          if (value === 0) {
            circle.setText('');
          } else {
            circle.setText(value);
          }
  
        }
      });
  
      bar.text.style.fontSize = '1.875rem';
      bar.text.style.fontWeight = '700';
      bar.animate(.75); // Number from 0.0 to 1.0
    }
    if ($('#circleProgress7').length) {
      var bar = new ProgressBar.Circle(circleProgress7, {
        color: '#9c9fa6',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 10,
        trailWidth: 10,
        easing: 'easeInOut',
        trailColor: '#1f2130',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: {
          color: '#aaa',
          width: 10
        },
        to: {
          color: '#2617c9',
          width: 10
        },
        // Set default step function for all animate calls
        step: function(state, circle) {
          circle.path.setAttribute('stroke', state.color);
          circle.path.setAttribute('stroke-width', state.width);
  
          var value = '<p class="text-center mb-0">Score</p>' + Math.round(circle.value() * 100) + "%";
          if (value === 0) {
            circle.setText('');
          } else {
            circle.setText(value);
          }
  
        }
      });
  
      bar.text.style.fontSize = '1.875rem';
      bar.text.style.fontWeight = '700';
      bar.animate(.75); // Number from 0.0 to 1.0
    }

  var eventData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
            label: 'Critical',
            data: [20, 35, 15, 45, 35, 40, 25, 44, 20, 30, 38, 15],
            backgroundColor: [
              'rgba(	255, 131, 0)'
            ],
            borderColor: [
                'rgba(	255, 131, 0)'
            ],
            backgroundColor: [
              'rgba(	255, 131, 0,.1)',
            ],
            borderWidth: 1,
            fill: true,
        },
        {
            label: 'Error',
            data: [30, 45, 25, 55, 45, 30, 35, 54, 30, 20, 48, 25],
            borderColor: [
                'rgba(242, 18, 38)',
            ],
            backgroundColor: [
              'rgba(242, 18, 38,.1)',
            ],
            borderWidth: 1,
            fill: true,
        },
        {
            label: 'Warning',
            data: [40, 55, 35, 65, 55, 40, 45, 64, 40, 30, 58, 35],
            borderColor: [
                'rgba(23, 23, 201)',
            ],
            backgroundColor: [
                'rgba(23, 23, 201,.1)',
            ],
            borderWidth: 1,
            fill: true,
        }
    ],
  };
  var eventOptions = {
      scales: {
          yAxes: [{
            display: false
          }],
          xAxes: [{
            display: false,
              position: 'bottom',
              gridLines: {
                drawBorder: false,
                display: true,
              },
              ticks: {
                display: false,
                beginAtZero: true,
                stepSize: 10
              }
          }],

      },
      legend: {
          display: false,
          labels: {
            boxWidth: 0,
          }
      },
      elements: {
          point: {
              radius: 0
          },
          line: {
            tension: .1,
          },
      },
      tooltips: {
          backgroundColor: 'rgba(2, 171, 254, 1)',
      }
  };
  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
        label: 'Dataset 1',
        data: [12, 19, 3, 5, 2, 3],
        borderColor: [
          '#587ce4'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 2',
        data: [5, 23, 7, 12, 42, 23],
        borderColor: [
          '#ede190'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 3',
        data: [15, 10, 21, 32, 12, 33],
        borderColor: [
          '#f44252'
        ],
        borderWidth: 2,
        fill: false
      }
    ]
  };
  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };
  var usedTilesPieChartData = {
    datasets: [{
      data: [21.76, 26.51, 32.06],
      backgroundColor: getBackgroundColors(3),
      /*borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],*/
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'Wall Tiles',
      'Floor Tiles',
      'Counters'
    ]
  };

  var doughnutPieData = {
    datasets: [{
      data: [21.76, 26.51, 32.06 ,14.18,5.49],
      backgroundColor: getBackgroundColors(6),
      /*borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],*/
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'North Zone',
      'South Zone',
      'East Zone',
      'West Zone',
      'Centeral Zone'
    ]
  };
  var doughnutPieOptions = {
    responsive: true,
    animation: {
      animateScale: true,
      animateRotate: true
    },
    legend: false
  };
  var areaData = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: true, // 3: no fill
    }]
  };

  var areaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    }
  }

  var multiAreaData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
        label: 'Facebook',
        data: [8, 11, 13, 15, 12, 13, 16, 15, 13, 19, 11, 14],
        borderColor: ['rgba(255, 99, 132, 0.5)'],
        backgroundColor: ['rgba(255, 99, 132, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Twitter',
        data: [7, 17, 12, 16, 14, 18, 16, 12, 15, 11, 13, 9],
        borderColor: ['rgba(54, 162, 235, 0.5)'],
        backgroundColor: ['rgba(54, 162, 235, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Linkedin',
        data: [6, 14, 16, 20, 12, 18, 15, 12, 17, 19, 15, 11],
        borderColor: ['rgba(255, 206, 86, 0.5)'],
        backgroundColor: ['rgba(255, 206, 86, 0.5)'],
        borderWidth: 1,
        fill: true
      }
    ]
  };

  var multiAreaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    },
    elements: {
      point: {
        radius: 0
      }
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  var scatterChartData = {
    datasets: [{
        label: 'First Dataset',
        data: [{
            x: -10,
            y: 0
          },
          {
            x: 0,
            y: 3
          },
          {
            x: -25,
            y: 5
          },
          {
            x: 40,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)'
        ],
        borderWidth: 1
      },
      {
        label: 'Second Dataset',
        data: [{
            x: 10,
            y: 5
          },
          {
            x: 20,
            y: -30
          },
          {
            x: -25,
            y: 15
          },
          {
            x: -10,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
        ],
        borderWidth: 1
      }
    ]
  }

  var scatterChartOptions = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom'
      }]
    }
  }
  if ($("#eventChart").length) {
    var lineChartCanvas = $("#eventChart").get(0).getContext("2d");
    var saleschart = new Chart(lineChartCanvas, {
        type: 'line',
        data: eventData,
        options: eventOptions
    });
  }

  var salesanalyticData = {
    labels: ["14 Feb", "15", "16", "17", "18", "19", "20", "21 Feb"],
    datasets: [{
            label: 'Summary Page Session',
            data: [140, 60, 120, 100, 240, 300, 120, 120 ],
            borderColor: [
                '#3022cb'
            ],
            borderWidth: 2,
            fill: false,
        },
        {
            label: 'Downloaded PDF',
            data: [140, 60, 120, 200, 200, 100, 120, 120],
            borderColor: [
                '#ff8300',
            ],
            borderWidth: 3,
            fill: false,
        }
        
    ],
  };
  var salesanalyticOptions = {
      scales: {
          yAxes: [{
              display: true,
              gridLines: {
                drawBorder: false,
                display: true,
            },
              ticks: {
                display: false,
                beginAtZero: false,
                stepSize: 5
              }
          }],
          xAxes: [{
            display: true,
              position: 'bottom',
              gridLines: {
                  drawBorder: false,
                  display: false,
              },
              ticks: {
                display: true,
                beginAtZero: true,
                stepSize: 5
              }
          }],

      },
      legend: {
          display: false,
          labels: {
            boxWidth: 0,
          }
      },
      elements: {
          point: {
              radius: 0
          },
          line: {
            tension: .4,
        },
      },
      tooltips: {
          backgroundColor: 'rgba(2, 171, 254, 1)',
      }
  };
  
  if ($("#salesanalyticChart").length) {
    var lineChartCanvas = $("#salesanalyticChart").get(0).getContext("2d");
    var saleschart = new Chart(lineChartCanvas, {
        type: 'line',
        data: salesanalyticData,
        options: salesanalyticOptions
    });
  }
  var barChartStackedData = {
    labels: ["jan", "feb", "mar", "apr", "may", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: 'Safari',
      data: [10,20,15,30,20,10,20,15,30,20, 10,20,],
      backgroundColor: [
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
      ],
      borderColor: [
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
      ],
      borderWidth: 1,
      fill: false
    },
    {
      label: 'Chrome',
      data: [5,25,10,20,30,5,25,10,20,30,25,10],
      backgroundColor: [
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
      ],
      borderColor: [
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
        '#bfccda',
      ],
      borderWidth: 1,
      fill: false
    }]
  };
  var barChartStackedOptions = {
    scales: {
      xAxes: [{
        display: false,
        stacked: true,
        gridLines: {
          display: false //this will remove only the label
        },
      }],
      yAxes: [{
        stacked: true,
        display: false,
      }]
    },
    legend: {
      display: false,
      position: "bottom"
    },
    legendCallback: function(chart) {
      var text = [];
      text.push('<div class="row">');
      for (var i = 0; i < chart.data.datasets.length; i++) {
        text.push('<div class="col-sm-5 mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0 mt-3"><div class="row align-items-center"><div class="col-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-9"><p class="text-dark m-0">' + chart.data.datasets[i].label + '</p></div></div>');
        text.push('</div>');
      }
      text.push('</div>');
      return text.join("");
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };

  if ($("#barChartStacked").length) {
    var barChartCanvas = $("#barChartStacked").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartStackedData,
      options: barChartStackedOptions
    });
  }

  var barChartStackedDarkData = {
    labels: ["jan", "feb", "mar", "apr", "may", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: 'Safari',
      data: [10,20,15,30,20,10,20,15,30,20, 10,20,],
      backgroundColor: [
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
      ],
      borderColor: [
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
        '#2b80ff',
      ],
      borderWidth: 1,
      fill: false
    },
    {
      label: 'Chrome',
      data: [5,25,10,20,30,5,25,10,20,30,25,10],
      backgroundColor: [
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
      ],
      borderColor: [
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
        '#1f2130',
      ],
      borderWidth: 1,
      fill: false
    }]
  };
  var barChartStackedDarkOptions = {
    scales: {
      xAxes: [{
        display: false,
        stacked: true,
        gridLines: {
          display: false //this will remove only the label
        },
      }],
      yAxes: [{
        stacked: true,
        display: false,
      }]
    },
    legend: {
      display: false,
      position: "bottom"
    },
    legendCallback: function(chart) {
      var text = [];
      text.push('<div class="row">');
      for (var i = 0; i < chart.data.datasets.length; i++) {
        text.push('<div class="col-sm-5 mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0 mt-3"><div class="row align-items-center"><div class="col-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-9"><p class="text-dark m-0">' + chart.data.datasets[i].label + '</p></div></div>');
        text.push('</div>');
      }
      text.push('</div>');
      return text.join("");
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };

  if ($("#barChartStackedDark").length) {
    var barChartCanvas = $("#barChartStackedDark").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartStackedDarkData,
      options: barChartStackedDarkOptions
    });
  }


  if ($("#salesTopChart").length) {
    var graphGradient = document.getElementById("salesTopChart").getContext('2d');;
    var saleGradientBg = graphGradient.createLinearGradient(25, 0, 25, 110);
    saleGradientBg.addColorStop(0, 'rgba(242,18,94, 1)');
    saleGradientBg.addColorStop(1, 'rgba(255, 255, 255, 1)');
    var salesTopData = {
        labels: [
        "Feb 1",
        "Feb 2",
        "Feb 3",
        "Feb 4",
        "Feb 5",
        "Feb 6",
        "Feb 7",
        "Feb 8",
        "Feb 9",
        "Feb 10",
        "Feb 11",
        "Feb 12",
        "Feb 13",
        "Feb 14",
        "Feb 15",
        "Feb 16",
        "Feb 17",
        "Feb 18",
        "Feb 19",
        "Feb 20",
        "Feb 21",
        "Feb 22",
        "Feb 23",
        "Feb 24",
        "Feb 25",
        "Feb 26",
        "Feb 27",
        "Feb 28",
        "Mar 1",
        "Mar 2",
        "Mar 3",
        "Mar 4",
        "Mar 5",
        "Mar 6",
        "Mar 7",
        "Mar 8",
        "Mar 9",
        "Mar 10",
        ],
        datasets: [{
            label: '# of Votes',
            data: [80, 79, 78, 65, 77, 68, 63, 73, 58, 46, 60, 65, 74, 72, 63, 54, 55, 64, 34, 46, 34, 35, 24, 64, 34, 23, 13, 54, 27, 43, 34, 43, 64, 50, 43, 55, 39, 43],
            backgroundColor: saleGradientBg,
            borderColor: [
                'rgba(242,18,94)',
            ],
            borderWidth: 2,
            fill: true, 
        }]
    };

    var salesTopOptions = {
        scales: {
            yAxes: [{
              display: true,
                gridLines: {
                    display: true,
                    drawBorder: true,
                },
                ticks: {
                  display: false,
                  beginAtZero: true,
                }
            }],
            xAxes: [{
              display: true,
                gridLines: {
                    display: true,
                    drawBorder: false,
                },
                ticks: {
                    beginAtZero: true,
                    maxTicksLimit: 4,
                    maxRotation: 360,
                    minRotation: 360,
                    padding: 10
                }
            }],
        },
        legend: {
            display: false
        },
        elements: {
          point: {
            radius: 0
        },
            line: {
                tension: 0.1,
            }
        },
        tooltips: {
            backgroundColor: 'rgba(31, 59, 179, 1)',
        }
    }
    var salesTop = new Chart(graphGradient, {
        type: 'line',
        data: salesTopData,
        options: salesTopOptions
    });
}

var eCommerceAnalyticData = {
  labels: ["14 Feb", "15", "16", "17", "18", "19", "20", "21 Feb"],
  datasets: [{
          label: 'Summary Page Session',
          data: [140, 60, 120, 100, 240, 300, 120, 120 ],
          borderColor: [
              '#f2125e'
          ],
          borderWidth: 3,
          fill: false,
          backgroundColor:"rgba(242, 250, 247, .6)"
      },
      {
          label: 'Downloaded PDF',
          data: [140, 60, 120, 200, 200, 100, 120, 120],
          borderColor: [
              '#392ccd',
          ],
          borderWidth: 3,
          fill: false,
          backgroundColor:'rgba(200, 200, 200,.5)',
      }
  ],
};
var eCommerceAnalyticOptions = {
    scales: {
        yAxes: [{
            display: true,
            gridLines: {
              drawBorder: false,
              display: false,
          },
            ticks: {
              display: true,
              beginAtZero: false,
              stepSize: 50
            }
        }],
        xAxes: [{
          display: true,
            position: 'bottom',
            gridLines: {
                drawBorder: false,
                display: true,
            },
            ticks: {
              display: true,
              beginAtZero: true,
              stepSize: 1
            }
        }],

    },
    legend: {
        display: false,
        labels: {
          boxWidth: 0,
        }
    },
    elements: {
        point: {
            radius: 0
        },
        line: {
          tension: .4,
      },
    },
    tooltips: {
        backgroundColor: 'rgba(2, 171, 254, 1)',
    }
};
if ($("#ecommerceAnalytic").length) {
  var lineChartCanvas = $("#ecommerceAnalytic").get(0).getContext("2d");
  var saleschart = new Chart(lineChartCanvas, {
      type: 'line',
      data: eCommerceAnalyticData,
      options: eCommerceAnalyticOptions
  });
}

var eCommerceAnalyticDarkData = {
  labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44","1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41"],
  datasets: [{
          label: 'Critical',
          data: [56, 56, 55, 59, 59, 59, 57, 56, 57, 54, 56, 58, 57, 59, 58, 59, 57, 55, 56, 54, 52, 52, 50, 50, 50, 52, 48, 49, 50, 52, 53, 52, 55, 54, 53, 56, 55, 56, 55, 54, 55, 57, 58, 56, 55, 56, 57, 58, 59, 58, 57, 55, 53, 52, 55, 57, 55, 54, 52, 55, 57, 56, 57, 58, 59, 58, 59, 57, 56, 55, 57, 58, 59, 60, 62, 60, 59, 58, 57, 56, 57, 56, 58, 59],
          borderColor: [
              '#392ccd'
          ],
          borderWidth: 3,
          fill: true,
          backgroundColor:"rgba(0, 0, 0, .2)"
      },
      {
          label: 'Warning',
          data: [32, 32, 35, 39, 39, 39, 37, 36, 37, 34, 36, 38, 37, 39, 38, 39, 37, 35, 36, 34, 30, 28, 31, 29, 27, 24, 23, 26, 25, 27, 28, 29, 32, 30, 33, 31, 35, 34, 32, 35, 37, 35, 36, 34, 30, 28, 28, 28, 32, 29, 33, 35, 33, 32, 35, 37, 35, 34, 32, 35, 37, 36, 37, 38, 39, 38, 39, 37, 36, 35, 37, 38, 39, 36, 37, 35, 39, 38, 37, 36, 37, 36, 38, 39],
          borderColor: [
              '#17c964',
          ],
          borderWidth: 3,
          fill: true,
          backgroundColor:'rgba(0, 0, 0,.3)',
      }
  ],
};
var eCommerceAnalyticDarkOptions = {
    scales: {
        yAxes: [{
            display: true,
            gridLines: {
              drawBorder: false,
              display: true,
          },
            ticks: {
              display: false,
              beginAtZero: false,
              stepSize: 5
            }
        }],
        xAxes: [{
          display: false,
            position: 'bottom',
            gridLines: {
                drawBorder: false,
                display: false,
            },
            ticks: {
              display: true,
              beginAtZero: true,
              stepSize: 5
            }
        }],

    },
    legend: {
        display: false,
        labels: {
          boxWidth: 0,
        }
    },
    elements: {
        point: {
            radius: 0
        },
        line: {
          tension: .4,
      },
    },
    tooltips: {
        backgroundColor: 'rgba(2, 171, 254, 1)',
    }
};
if ($("#ecommerceAnalyticDark").length) {
  var lineChartCanvas = $("#ecommerceAnalyticDark").get(0).getContext("2d");
  var saleschart = new Chart(lineChartCanvas, {
      type: 'line',
      data: eCommerceAnalyticDarkData,
      options: eCommerceAnalyticDarkOptions
  });
}
$(function() {
  /* ChartJS
   * -------
   * Data and config for chartjs
   */
  'use strict';
  var data = {
    labels: ["Living Room", "Bed Room", "Kitchen", "Bathroom", "Prayer Room", "Outdoor", "Commercial"],
    datasets: [{
      label: '# of Users',
      data: [10, 19, 3, 5, 2, 3,18],
      backgroundColor: getBackgroundColors(7),
      /*borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,*/
      fill: false
    }]
  };
  var dataRoomUsed = {
    labels: ["Wall 1", "Bedroom 1", "Bedroom 2", "Prayer Room 1", "Commercial Room 1", "Kitchen 1", "Outdoor 1", "Outdoor", "Living Room 1"],
    datasets: [{
      label: '# of Users',
      data: [10, 19, 3, 5, 2, 3,18],
      backgroundColor: getBackgroundColors(7),
      /*borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,*/
      fill: false
    }]
  };
  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
        label: 'Dataset 1',
        data: [12, 19, 3, 5, 2, 3],
        borderColor: [
          '#587ce4'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 2',
        data: [5, 23, 7, 12, 42, 23],
        borderColor: [
          '#ede190'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 3',
        data: [15, 10, 21, 32, 12, 33],
        borderColor: [
          '#f44252'
        ],
        borderWidth: 2,
        fill: false
      }
    ]
  };
  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };
  var areaData = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: true, // 3: no fill
    }]
  };

  var areaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    }
  }

  var multiAreaData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
        label: 'Facebook',
        data: [8, 11, 13, 15, 12, 13, 16, 15, 13, 19, 11, 14],
        borderColor: ['rgba(255, 99, 132, 0.5)'],
        backgroundColor: ['rgba(255, 99, 132, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Twitter',
        data: [7, 17, 12, 16, 14, 18, 16, 12, 15, 11, 13, 9],
        borderColor: ['rgba(54, 162, 235, 0.5)'],
        backgroundColor: ['rgba(54, 162, 235, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Linkedin',
        data: [6, 14, 16, 20, 12, 18, 15, 12, 17, 19, 15, 11],
        borderColor: ['rgba(255, 206, 86, 0.5)'],
        backgroundColor: ['rgba(255, 206, 86, 0.5)'],
        borderWidth: 1,
        fill: true
      }
    ]
  };

  var multiAreaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    },
    elements: {
      point: {
        radius: 0
      }
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  var scatterChartData = {
    datasets: [{
        label: 'First Dataset',
        data: [{
            x: -10,
            y: 0
          },
          {
            x: 0,
            y: 3
          },
          {
            x: -25,
            y: 5
          },
          {
            x: 40,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)'
        ],
        borderWidth: 1
      },
      {
        label: 'Second Dataset',
        data: [{
            x: 10,
            y: 5
          },
          {
            x: 20,
            y: -30
          },
          {
            x: -25,
            y: 15
          },
          {
            x: -10,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
        ],
        borderWidth: 1
      }
    ]
  }

  var scatterChartOptions = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom'
      }]
    }
  }
  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });
  }

  if ($("#barChartRoomUsed").length) {
    var barChartCanvas = $("#barChartRoomUsed").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: dataRoomUsed,
      options: options
    });
  }


  if ($("#lineChart").length) {
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: data,
      options: options
    });
  }

  if ($("#linechart-multi").length) {
    var multiLineCanvas = $("#linechart-multi").get(0).getContext("2d");
    var lineChart = new Chart(multiLineCanvas, {
      type: 'line',
      data: multiLineData,
      options: options
    });
  }

  if ($("#areachart-multi").length) {
    var multiAreaCanvas = $("#areachart-multi").get(0).getContext("2d");
    var multiAreaChart = new Chart(multiAreaCanvas, {
      type: 'line',
      data: multiAreaData,
      options: multiAreaOptions
    });
  }

  if ($("#doughnutChart").length) {
    var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: usedTilesPieChartData,
      options: doughnutPieOptions
    });
  }

  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }

  if ($("#scatterChart").length) {
    var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartData,
      options: scatterChartOptions
    });
  }

  if ($("#browserTrafficChart").length) {
    var doughnutChartCanvas = $("#browserTrafficChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: browserTrafficData,
      options: doughnutPieOptions
    });
  }
});


  function getBackgroundColors(p_count){
    var tempAray = [];
    var count = 0;
    for(var i = 0; i < p_count; i++){
    
      if(count >= backgrounColors.length){
        count = 0;
      }
      if(i < p_count){
        tempAray.push(backgrounColors[count]);
        count++;
      }
    }
    return tempAray;
  }

  });
})(jQuery);