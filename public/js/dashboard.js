$('#daterange').daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    startDate: moment().subtract(7, 'days'),
    endDate: moment(),
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
});

// Fetch data and update UI on button click
$('#filterButton').click(function() {
    let dateRange = $('#daterange').val();
    let dates = dateRange.split(" - ");

    function convertDateFormat(date) {
        let [day, month, year] = date.split("-");
        return `${year}-${month}-${day}`;
    }

    let startDate = convertDateFormat(dates[0]);
    let endDate = convertDateFormat(dates[1]);
    fetchAnalyticsData(startDate , endDate);

});

function fetchAnalyticsData(startDate , endDate)
{
    $.ajax({
        url: '/get_analytics_data',  // Update with your actual API endpoint
        type: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        data: { start_date: startDate, end_date: endDate },
        success: function(response) {
            renderChart(response.chartData);
        },
        error: function(error) {
            console.log("Error fetching zone data:", error);
        }
    });
}


function renderChart(chartData) {
    let ctx = document.getElementById("zonePincodeChart").getContext("2d");
    let zoneChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.values,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            }]
        },
        options: {
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return chartData.labels[tooltipItem.dataIndex] +
                                ": " + chartData.values[tooltipItem.dataIndex] +
                                " (" + chartData.percentages[tooltipItem.dataIndex] + ")";
                        }
                    }
                }
            }
        }
    });
}


fetchAnalyticsData('2025-02-14', '2025-02-21');