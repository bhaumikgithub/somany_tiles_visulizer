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
    console.log(dateRange);
    fetchZoneData(dateRange);
});

function fetchZoneData(dateRange) {
    $.ajax({
        url: "/get-zone-data", // Laravel Route for fetching data
        method: "GET",
        data: { daterange: dateRange },
        success: function(response) {
            //updateZoneTable(response.data);
            updateZoneChart(response.chartData);
        }
    });
}

function updateZoneChart(chartData) {
    let ctx = document.getElementById('zoneChart').getContext('2d');
    if (window.zoneChartInstance) {
        window.zoneChartInstance.destroy();
    }
    window.zoneChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.values,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
            }]
        }
    });
}