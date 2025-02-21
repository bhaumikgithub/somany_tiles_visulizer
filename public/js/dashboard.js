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

    console.log("Start Date:", startDate, "End Date:", endDate);
    fetchZoneData(startDate,endDate);
});

function fetchZoneData(startDate,endDate) {
    $.ajax({
        url: "/get_analytics_data", // Laravel Route for fetching data
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { startDate: startDate , endDate : endDate },
        success: function(response) {
            //updateZoneTable(response.data);
            updateZoneChart(response.chartData.labels , response.chartData.values);
        }
    });
}

function updateZoneChart(labels, values) {
    let ctx = document.getElementById('zoneChart').getContext('2d');
    window.zoneChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visits by Zone',
                data: values,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
}