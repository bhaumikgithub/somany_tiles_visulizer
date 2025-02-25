let zoneChart = null;
let roomChart = null;
let roomCategoryChart = null;
let summaryChart = null;
let tileUsageChart = null;

function formatDateRangeLabel(rangeKey, startDate, endDate) {
    return rangeKey !== "Custom Range" ? rangeKey : `${moment(startDate).format("DD-MM-YYYY")} to ${moment(endDate).format("DD-MM-YYYY")}`;
}

let defaultStartDate = moment().subtract(6, 'days'); // Last 7 Days default
let defaultEndDate = moment();
let selectedRangeKey = "Last 7 Days";

$('#daterange').daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    startDate: defaultStartDate,
    endDate: defaultEndDate,
    autoUpdateInput : false,
    ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    } ,
    function (start, end, label) {
        selectedRangeKey = label || "Custom Range";
        // ✅ Keep predefined text instead of showing a date range
        if (label && label !== "Custom Range") {
            $("#daterange").val(label);
        } else {
            $("#daterange").val(`${start.format("DD-MM-YYYY")} to ${end.format("DD-MM-YYYY")}`);
        }

        fetchAnalyticsData(start, end);
    });

// Set default text as "Last 7 Days"
$("#daterange").val("Last 7 Days");

function getLastWeekDates() {
    let endDate = new Date(); // Today
    let startDate = new Date();
    startDate.setDate(endDate.getDate() - 6); // Last 7 days (including today)

    let formattedStartDate = startDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD
    let formattedEndDate = endDate.toISOString().split('T')[0];

    return { startDate: formattedStartDate, endDate: formattedEndDate };
}
// Fetch last 7-day data dynamically
let dates = getLastWeekDates();
fetchAnalyticsData(dates.startDate, dates.endDate);

// Fetch data and update UI on button click
$('#filterButton').click(function() {
    let selectedRange = $("#daterange").data('daterangepicker');
    fetchAnalyticsData(selectedRange.startDate, selectedRange.endDate);

});

function fetchAnalyticsData(startDate , endDate) {
    let formattedStart = moment(startDate).format('YYYY-MM-DD');
    let formattedEnd = moment(endDate).format('YYYY-MM-DD');

    // Update the display text
    $("#selectedDateRangeText").text(`Showing result from ${moment(startDate).format("Do MMM")} to ${moment(endDate).format("Do MMM YYYY")}`);

    $.ajax({
        url: '/get_analytics_data',  // Update with your actual API endpoint
        type: 'POST',
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        data: { startDate: formattedStart, endDate: formattedEnd },
        success: function(response) {
            renderChart(response.pincode_chartData, response.total_visits);
            renderCategoryChart(response.category_chart_data);
            renderRoomChart(response.room_chart_data);
            renderTilesData(response.tilesTabularData);
            renderSummaryOrDownloadPDFChart(response.summary_pdf_chart_data);
            renderTilesAppliedOnChart(response.wall_count,response.floor_count,response.counter_count);
            renderTopFiveTilesData(response.top_five_tiles);
            renderTopUsedRooms(response.top_five_rooms);
            renderTopShowrooms(response.top_showrooms);

            if(response.total_users > 0 ) {
                let guestPercentage = ((response.total_guest_users / response.total_users) * 100).toFixed(2);
                let loggedInPercentage = ((response.total_logged_in_users / response.total_users) * 100).toFixed(2);
                //Display usersData
                $('.totalUsers').text(response.total_users);
                $('.totalGuestUsers').text(`${response.total_guest_users} (${guestPercentage}%)`);
                $('.totalLoggedInUsers').text(`${response.total_logged_in_users} (${loggedInPercentage}%)`);
            }

            $('.total_sessions').text(response.total_session);
            $('.session_to_summary_page').text(response.session_reach_summary_page);
            $('.pdf_download').text(response.download_pdf);
        },
        error: function(error) {
            console.log("Error fetching zone data:", error);
        }
    });
}

function renderChart(chartData, totalVisits,pincodeTabularData) {
    let ctx = document.getElementById("zonePincodeChart").getContext("2d");

    // Destroy the previous chart if it exists
    if (zoneChart !== null) {
        zoneChart.destroy();
    }

    zoneChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.values,
                backgroundColor: ['#a5dfdf', '#ffe6aa', '#9ad0f5', '#ffb1c1', '#ccb2ff'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {display: false}, // Hide default legend
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return chartData.labels[tooltipItem.dataIndex] +
                                ": " + chartData.values[tooltipItem.dataIndex] +
                                " (" + chartData.percentages[tooltipItem.dataIndex] + "%)";
                        }
                    }
                }
            }
        }
    });

    // Update Summary List
    let summaryHtml = "";
    chartData.labels.forEach((label, index) => {
        summaryHtml += `<li>🔵 ${label} <span style="float:right;">${chartData.values[index]} (${chartData.percentages[index]}%)</span></li>`;
    });
    summaryHtml += `<li class="total">🔵 Total <span style="float:right;"><b>${chartData.totalVisitsPinCode} (100%)</b></span></li>`;
    document.getElementById("summaryList").innerHTML = summaryHtml;
    }

function renderCategoryChart(chartData) {
    let ctx = document.getElementById("roomCategoryChart").getContext("2d");
    // Destroy existing chart instance if it exists
    if (roomCategoryChart !== null) {
        roomCategoryChart.destroy();
    }
    roomCategoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.values,
                backgroundColor: ['#ffb1c1', '#9ad0f5', '#ffe6aa', '#a5dfdf', '#ccb2ff', '#ffcf9f', '#aee0a7'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Allows custom width & height
            scales: {
                x: { ticks: { autoSkip: false } },
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let index = tooltipItem.dataIndex;
                            return chartData.labels[index] + ": " + chartData.values[index] + " (" + chartData.percentages[index] + "%)";
                        }
                    }
                }
            }
        }
    });

    let summaryHtml = "";
    chartData.labels.forEach((label, index) => {
        summaryHtml += `<li style="list-style-type:circle;"> ${label} <span style="float:right;">${chartData.values[index]} (${chartData.percentages[index]}%)</span></li>`;
    });
    summaryHtml += `<li class="total" style="font-weight:bold;">Total <span style="float:right;">${chartData.total} (100%)</span></li>`;
    document.getElementById("roomCategorySummary").innerHTML = summaryHtml;
}

function renderRoomChart(roomChartData){
    let ctx = document.getElementById("roomChart").getContext("2d");

    if (roomChart !== null) {
        roomChart.destroy();
    }
    roomChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: roomChartData.labels,
            datasets: [{
                data: roomChartData.values,
                backgroundColor: ['#a5dfdf', '#ffe6aa', '#9ad0f5', '#ffb1c1', '#ccb2ff'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {display: false}, // Hide default legend
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return roomChartData.labels[tooltipItem.dataIndex] +
                                ": " + roomChartData.values[tooltipItem.dataIndex] +
                                " (" + roomChartData.percentages[tooltipItem.dataIndex] + "%)";
                        }
                    }
                }
            }
        }
    });
    // Update Summary List
    let summaryHtml = "";
    roomChartData.labels.forEach((label, index) => {
        summaryHtml += `<li>🔵 ${label} <span style="float:right;">${roomChartData.values[index]} (${roomChartData.percentages[index]}%)</span></li>`;
    });
    summaryHtml += `<li class="total">🔵 Total <span style="float:right;"><b>${roomChartData.totalRoomVisits} (100%)</b></span></li>`;
    document.getElementById("roomSummary").innerHTML = summaryHtml;
}

function renderTilesData(tilesData){
    let tableBody = "";
    tilesData.forEach(item => {
        tableBody += `
                        <tr>
                            <td>${item.name}</td>
                            <td><img src="${item.photo}" alt="${item.photo}" width="50" height="50"></td>
                            <td>${item.size}</td>
                            <td>${item.view_count}</td>
                            <td>${item.used_count}</td>
                            <td>${item.floor_count}</td>
                            <td>${item.wall_count}</td>
                            <td>${item.counter_count}</td>
                        </tr>
                    `;
    });

    // Destroy existing DataTable instance (if already initialized)
    if ($.fn.DataTable.isDataTable("#tilesTable")) {
        $("#tilesTable").DataTable().destroy();
    }

    $("#tilesTable tbody").html(tableBody);

    // Reinitialize DataTable
    $("#tilesTable").DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        columnDefs: [
            { orderable: false, targets: [1] } // Disable sorting on photo column
        ]
    });
}

function renderSummaryOrDownloadPDFChart(summaryPDFChartData) {
    let ctx = document.getElementById('summaryPdfDownloadChart').getContext('2d');

    // Destroy the previous chart if it exists
    if (summaryChart !== null) {
        summaryChart.destroy();
    }

    summaryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: summaryPDFChartData.labels, // X-axis: Dates
            datasets: [
                {
                    label: 'Summary Page Session',
                    data: summaryPDFChartData.sessionData, // Y-axis: Sessions Count
                    borderColor: '#ed2b72',
                },
                {
                    label: 'PDF Downloaded',
                    data: summaryPDFChartData.pdfDownloadData, // Y-axis: PDF Downloads Count
                    borderColor: '#886dd5',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

}

function renderTilesAppliedOnChart(wall_count,floor_count,counter_count){
    let ctx = document.getElementById('tilesAppliedOnChart').getContext('2d');

    let totalTilesUsed = wall_count + floor_count + counter_count;

    let data = [
        wall_count,
        floor_count,
        counter_count
    ];

    let labels = [
        `Wall (${((wall_count / totalTilesUsed) * 100).toFixed(2)}%)`,
        `Floor (${((floor_count / totalTilesUsed) * 100).toFixed(2)}%)`,
        `Counter (${((counter_count / totalTilesUsed) * 100).toFixed(2)}%)`
    ];

    // Update UI text
    $("#wallCount").html(`${wall_count} (${((wall_count / totalTilesUsed) * 100).toFixed(2)}%)`);
    $("#floorCount").html(`${floor_count} (${((floor_count / totalTilesUsed) * 100).toFixed(2)}%)`);
    $("#counterCount").html(`${counter_count} (${((counter_count / totalTilesUsed) * 100).toFixed(2)}%)`);
    $("#totalTiles").html(`<b>${totalTilesUsed} (100%)</b>`);

    // Destroy the previous chart if it exists
    if (tileUsageChart !== null) {
        tileUsageChart.destroy();
    }

    tileUsageChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#ffe6aa', '#9ad0f5', '#ffb1c1'],
                hoverBackgroundColor: ['#ffe6aa', '#9ad0f5', '#ffb1c1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {display: false}, // Hide default legend
            }
        }
    });
}

function renderTopFiveTilesData(topFiveTiles){
    let html = "";

    topFiveTiles.forEach(tile => {
        html += `<li class="tile-item">
                        <img src="${tile.photo}" alt="${tile.name}">
                        <div class="tile-info">
                            <div class="tile-name">${tile.name}</div>
                            <div class="tile-stats">
                                ${tile.size}<br>
                                Used <strong>${tile.used_count}</strong> times 
                                (<span class="tile-percentage">${tile.percentage}%</span>)
                            </div>
                        </div>
                    </li>`;
    });

    $("#topTilesList").html(html);
}

function renderTopUsedRooms(topFiveRooms){
    let maxCount = Math.max(...topFiveRooms.map(room => room.count || 0)); // Prevent NaN errors
    let html = topFiveRooms.map(room => {
        let percentage = maxCount > 0 ? (room.count / maxCount) * 100 : 0;
        let color = getRandomColor();
        return `<div class="room-item">
                    <div class="room-header">
                        <div class="room-info">
                            <strong class="room-name">${room.name}</strong>
                            <br><span class="room-category">${room.category}</span>
                        </div>
                        <div class="room-count">${room.count}</div>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="background-color: #f0f0f0">
                            <div class="progress" style="width: ${percentage}%; background-color: ${color}; margin: unset"></div>
                        </div>
                    </div>
                </div>`;
    }).join('');

    $("#topUsedRooms").html(html);
}

function renderTopShowrooms(topShowRooms){
    let maxCount = Math.max(...topShowRooms.map(room => room.usage_count || 0)); // Prevent NaN errors
    let html = topShowRooms.map(showrooms => {
        let percentage = maxCount > 0 ? (showrooms.usage_count / maxCount) * 100 : 0;
        let color = getRandomColor();
        return `<div class="room-item">
                    <div class="room-header">
                        <div class="room-info">
                            <strong class="room-name">${showrooms.name}</strong>
                            <br><span class="room-category">${showrooms.city}</span>
                        </div>
                        <div class="room-count">${showrooms.usage_count}</div>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="background-color: #f0f0f0">
                            <div class="progress" style="width: ${percentage}%; background-color: ${color}; margin: unset"></div>
                        </div>
                    </div>
                </div>`;
    }).join('');

    $("#activeShowRooms").html(html);
}


function getRandomColor() {
    let colors = ["#4caf50", "#ff9800", "#2196f3", "#f44336", "#e91e63"];
    return colors[Math.floor(Math.random() * colors.length)];
}

$("#zonePincodeTable").DataTable({
    responsive: true,
    paging: false,         // Disable pagination
    ordering: true,        // Enable sorting on columns
    searching: true,       // Enable search box
    info: false,           // Hide "Showing X of Y entries"
    columnDefs: [
        {orderable: true, targets: [0, 1, 2]} // Make all columns sortable
    ]
});