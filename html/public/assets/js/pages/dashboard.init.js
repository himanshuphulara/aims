function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var o = document.getElementById(e).getAttribute("data-colors");
        if (o) return (o = JSON.parse(o)).map(function(e) {
            var o = e.replace(" ", "");
            return -1 === o.indexOf(",") ? getComputedStyle(document.documentElement).getPropertyValue(o) || o : 2 == (e = e.split(",")).length ? "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")" : o
        });
        console.warn("data-colors atributes not found on", e)
    }
}


// Line Bar Graph
var options, chart, linechartBasicColors = getChartColorsArray("stacked-column-chart"),
    barchartColors = (linechartBasicColors && (options = {
        chart: {
            height: 405,
            type: "bar",
            toolbar: {
                show: !1
            },
            zoom: {
                enabled: !0
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "60%",
                endingShape: "rounded"
            }
        },
        dataLabels: {
            enabled: !1
        },
        series: [{
            name: "Assets",
            data: window.assets
        }, {
            name: "Liabilities",
            data: window.liabilities
        }],
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
        },
        grid: {
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        colors: linechartBasicColors,
        legend: {
            show: !1
        },
        fill: {
            opacity: 1
        }
    }, 


// Circular Chart
    (chart = new ApexCharts(document.querySelector("#stacked-column-chart"), options)).render()), getChartColorsArray("structure-widget"));
barchartColors && (options = {
    chart: {
        height: 440,
        type: "pie"
    },
    series: window.pieNetValue,
    labels: window.pieNetKey,
    colors: barchartColors,
    plotOptions: {
        pie: {
            dataLabels:{
                minAngleToShowLabel:0 //for show % for samllest value
            },
            startAngle: 0,
            pie: {
                size: "80%"
            }
        }
    },
    legend: {
        show: !1
    },
    dataLabels: {
        style: {
            fontSize: "10px",
            fontFamily: "DM Sans,sans-serif",
            colors: void 0
        },
        background: {
            enabled: !0,
            foreColor: "#fff",
            padding: 4,
            borderRadius: 2,
            borderWidth: 1,
            borderColor: "#fff",
            opacity: 1
        }
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: !1
            }
        }
    }]
}, (chart = new ApexCharts(document.querySelector("#structure-widget"), options)).render());

// Public Fund Financial Summary Chart
var publicFundColors = getChartColorsArray("public-fund-chart");
if (publicFundColors && window.publicFundData) {
    var subcategories = window.publicFundData.map(item => item.name);
    var totalAllotments = window.publicFundData.map(item => item.total_allotment);
    var pcdaBookings = window.publicFundData.map(item => item.pcda_booking);
    var totalExpenditures = window.publicFundData.map(item => item.total_expenditure);

    var publicFundOptions = {
        chart: {
            height: 400,
            type: "bar",
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "60%",
                endingShape: "rounded"
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            name: "Total Allotment",
            data: totalAllotments
        }, {
            name: "PCDA Booking",
            data: pcdaBookings
        }, {
            name: "Total Expenditure", 
            data: totalExpenditures
        }],
        xaxis: {
            categories: subcategories,
            labels: {
                rotate: -45,
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: "Amount (₹)"
            },
            labels: {
                formatter: function(val) {
                    return "₹" + val.toLocaleString();
                }
            }
        },
        grid: {
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        colors: publicFundColors,
        legend: {
            show: true,
            position: 'top'
        },
        fill: {
            opacity: 0.8
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return "₹" + val.toLocaleString();
                }
            }
        }
    };
    
    var publicFundChart = new ApexCharts(document.querySelector("#public-fund-chart"), publicFundOptions);
    publicFundChart.render();
}