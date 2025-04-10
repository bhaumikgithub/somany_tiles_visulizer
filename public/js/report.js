let zoneChart = null;
let roomChart = null;
let roomCategoryChart = null;
let summaryChart = null;
let tileUsageChart = null;

let backgrounColors = [
    'rgba(255, 99, 132, 0.5)',
    'rgba(54, 162, 235, 0.5)',
    'rgba(255, 206, 86, 0.5)',
    'rgba(75, 192, 192, 0.5)',
    'rgba(153, 102, 255, 0.5)',
    'rgba(255, 159, 64, 0.5)',
    'rgba(100, 200, 86, 0.5)',
];


function formatDateRangeLabel(rangeKey, startDate, endDate) {
    return rangeKey !== "Custom Range" ? rangeKey : `${moment(startDate).format("DD-MM-YYYY")} to ${moment(endDate).format("DD-MM-YYYY")}`;
}

let defaultStartDate = moment().subtract(6, 'days'); // Last 7-day default
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
        // Keep predefined text instead of showing a date range
        if (label && label !== "Custom Range") {
            $("#daterange").val(label);
        } else {
            $("#daterange").val(`${start.format("DD-MM-YYYY")} to ${end.format("DD-MM-YYYY")}`);
        }

        fetchAnalyticsData(start, end);
        updateViewAllLinks(start, end);
    });

// Set the default text as "Last 7 Days"
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

updateViewAllLinks(moment().subtract(6, 'days'), moment());

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

    if( !$('.report_wrapper').length ){
        // Call each block's data separately
        fetchSummaryOrDownloadPDFChart(formattedStart, formattedEnd,"summaryPdfDownloadChart");
        fetchPincodeChart(formattedStart, formattedEnd , "pincodeChart");
        fetchAppliedTilesChart(formattedStart, formattedEnd , "appliedTilesBlock");
        fetchRoomCategoryChart(formattedStart, formattedEnd , "roomCategoryBlock");
        fetchTopFiveTiles(formattedStart, formattedEnd);
        fetchTopFiveRooms(formattedStart,formattedEnd);
        fetchTopFiveShowRooms(formattedStart,formattedEnd);
    }
}

/** Summary PDF chart */
function fetchSummaryOrDownloadPDFChart(startDate , endDate , chartType){
    $.ajax({
        url: '/get_summary_pdf_chart',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate , chartType },
        success: function(response) {
            renderSummaryOrDownloadPDFChart(response.summary_pdf_chart_data);
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
            console.error("Error loading Summary/PDF chart:", error);
        }
    });
}

function renderSummaryOrDownloadPDFChart(summaryPDFChartData) {
    let summaryAnalyticData = {
        labels: summaryPDFChartData.labels, // X-axis: Dates,
        datasets: [{
            label: 'Summary Page Session',
            data: summaryPDFChartData.sessionData,
            borderColor: [
                '#f2125e'
            ],
            borderWidth: 3,
            fill: false,
            backgroundColor:"rgba(242, 250, 247, .6)"
        }, {
            label: 'Downloaded PDF',
            data: summaryPDFChartData.pdfDownloadData, // Y-axis: PDF Downloads Count
            borderColor: [
                '#392ccd',
            ],
            borderWidth: 3,
            fill: false,
            backgroundColor:'rgba(200, 200, 200,.5)',
        }
        ],
    };

    let summaryeAnalyticOptions = {
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
                    stepSize: 1,
                    callback: function(value, index, values) {
                        const label = summaryPDFChartData.labels[index]; // e.g., "01-Mar"
                        
                        if (index === 0 || index === values.length - 1) {
                            return label; // Show full "01-Mar" and "31-Mar"
                        }

                        return label.split("-")[0]; // Just show the day part for others
                    }
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

    if ($("#summaryPdfDownloadChart").length) {
        let lineChartCanvas = $("#summaryPdfDownloadChart").get(0).getContext("2d");
        let saleschart = new Chart(lineChartCanvas, {
            type: 'line',
            data: summaryAnalyticData,
            options: summaryeAnalyticOptions
        });
    }
}


/** Pincode Chart **/
function fetchPincodeChart(startDate , endDate , chartType){
    $.ajax({
        url: '/get_pincode_chart',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate , chartType },
        success: function(response) {
            renderPinCodeChart(response.pincode_chartData , response.total_visits);
        },
        error: function(error) {
            console.error("Error loading Summary/PDF chart:", error);
        }
    });
}

function renderPinCodeChart(chartData) {
    let doughnutPieData = {
        datasets: [{
            data: chartData.values,
            backgroundColor: getBackgroundColors(6),
        }],

        labels: chartData.labels,
    };


    if ($("#zonePincodeChart").length) {
        let doughnutChartCanvas = $("#zonePincodeChart").get(0).getContext("2d");
        let doughnutChart = new Chart(doughnutChartCanvas, {
            type: 'doughnut',
            data: doughnutPieData,
            options: doughnutPieOptions()
        });
    }

    // Update Summary List
    let summaryHtml = "";
    let summaryElement = document.getElementById("summaryList");
    if (summaryElement) {
        chartData.labels.forEach((label, index) => {
            summaryHtml += `<li><div>${label}</div> <div>${chartData.values[index]} (${chartData.percentages[index]}%)</div></li>`;
        });
        if (chartData.totalVisitsPinCode > 0) {
            summaryHtml += `<li><div><b>Total</b></div> <div><b>${chartData.totalVisitsPinCode} (100%)</b></div></li>`;
        }
        summaryElement.innerHTML = summaryHtml;
    }
}


/** Applied Tiles Chart */
function fetchAppliedTilesChart(startDate , endDate , chartType){
    $.ajax({
        url: '/get_tiles_applied_chart',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate , chartType },
        success: function(response) {
            renderTilesAppliedChart(response.wall_count,response.floor_count,response.counter_count);
        },
        error: function(error) {
            console.error("Error loading Summary/PDF chart:", error);
        }
    });
}

function renderTilesAppliedChart(wall_count,floor_count,counter_count) {
    let totalTilesUsed = wall_count + floor_count + counter_count;

    if( totalTilesUsed > 0 ){
        html = `<li><div>Wall</div><div>${wall_count} (${((wall_count / totalTilesUsed) * 100).toFixed(2)}%)</div></li>
                <li><div>Floor</div><div>${floor_count} (${((floor_count / totalTilesUsed) * 100).toFixed(2)}%)</div></li>
                <li><div>Counter</div><div>${counter_count} (${((counter_count / totalTilesUsed) * 100).toFixed(2)}%)</div></li>
                <li><div><b>Total</b></div><div><b>${totalTilesUsed} (100%)</b></div></li>`;

        $('#tile-applied-list').html(html);

        let usedTilesPieChartData = {
            labels: [
                `Wall (${((wall_count / totalTilesUsed) * 100).toFixed(2)}%)`,
                `Floor (${((floor_count / totalTilesUsed) * 100).toFixed(2)}%)`,
                `Counter (${((counter_count / totalTilesUsed) * 100).toFixed(2)}%)`
            ],
            datasets: [{
                data: [
                    ((wall_count / totalTilesUsed) * 100).toFixed(2),
                    ((floor_count / totalTilesUsed) * 100).toFixed(2),
                    ((counter_count / totalTilesUsed) * 100).toFixed(2)
                ],
                backgroundColor: getBackgroundColors(3)
            }]
        };

        if ($("#tilesAppliedOnChart").length) {
            let pieChartCanvas = $("#tilesAppliedOnChart").get(0).getContext("2d");
            let pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: usedTilesPieChartData,
                options: doughnutPieOptions()
            });
        }
        }
}

/*** Room Category Chart **/
function fetchRoomCategoryChart(startDate , endDate , chartType){
    $.ajax({
        url: '/get_room_category_chart',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate , chartType },
        success: function(response) {
            renderRoomChart(response.applied_tiles_chart_data);
        },
        error: function(error) {
            console.error("Error loading Summary/PDF chart:", error);
        }
    });
}

function renderRoomChart(roomChartData){
    let data = {
        labels: roomChartData.labels,
        datasets: [{
            label: 'No of visits',
            data: roomChartData.values,
            backgroundColor: getBackgroundColors(7),
            fill: false
        }]
    };

    let options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 2,     // Controls the interval between ticks
                    min: 0,          // Start at 0
                    max: 20  
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

    if ($("#roomCategoryChart").length) {
        let barChartCanvas = $("#roomCategoryChart").get(0).getContext("2d");

        if (roomChart !== null) {
            roomChart.destroy();
        }
        // This will get the first returned node in the jQuery collection.
        roomChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: data,
            options: options
        });
    }
}

function getBackgroundColors(p_count){
    let tempAray = [];
    let count = 0;
    for(let i = 0; i < p_count; i++){

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

function doughnutPieOptions(){
    return {
        responsive: true,
        animation: {
            animateScale: true,
            animateRotate: true
        },
        legend: false
    };
}

/** Top Five Tiles **/
function fetchTopFiveTiles(startDate , endDate){
    $.ajax({
        url: '/get_top_tiles',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate },
        success: function(response) {
            $('.topFiveTilesWrapper').html(response.body);
        },
        error: function(error) {
            console.error("Error loading Data:", error);
        }
    });
}

/** Top five rooms **/
function fetchTopFiveRooms(startDate , endDate){
    $.ajax({
        url: '/get_top_rooms',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate },
        success: function(response) {
            $('.topFiveRoomsWrapper').html(response.body);
        },
        error: function(error) {
            console.error("Error loading Data:", error);
        }
    });
}

/** Top five rooms **/
function fetchTopFiveShowRooms(startDate , endDate){
    $.ajax({
        url: '/get_top_show_rooms',  // Update with your actual API endpoint
        type: 'POST',
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
        data: { startDate, endDate },
        success: function(response) {
            $('.topFiveShowRoomsWrapper').html(response.body);
        },
        error: function(error) {
            console.error("Error loading Data", error);
        }
    });
}


function updateViewAllLinks(start, end) {
    let startDate = start.format('YYYY-MM-DD');
    let endDate = end.format('YYYY-MM-DD');

    $("#viewAllPincode").attr("href", `/analytics/details/pincode?start_date=${startDate}&end_date=${endDate}`);
    $("#viewAllAppliedTiles").attr("href", `/analytics/details/appliedTiles?start_date=${startDate}&end_date=${endDate}`);
    $("#viewAllTiles").attr("href", `/analytics/details/tiles?start_date=${startDate}&end_date=${endDate}`);
    $(".viewAllRooms").attr("href", `/analytics/details/rooms?start_date=${startDate}&end_date=${endDate}`);
    $(".viewAllShowRooms").attr("href", `/analytics/details/showroom?start_date=${startDate}&end_date=${endDate}`);
    $("#viewAllPDF").attr("href", `/analytics/details/pdf?start_date=${startDate}&end_date=${endDate}`);
}


if( $('.report_wrapper').length) {
    /**** Detail Page **/
    // Function to get query parameters from the URL
    function getQueryParam(param) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Extract start_date and end_date from URL
    let startDateFromURL = getQueryParam('start_date'); // Example: "2025-02-01"
    let endDateFromURL = getQueryParam('end_date'); // Example: "2025-02-28"

    // Convert them to moment.js objects (if found)
    let defaultStartDateDetail = startDateFromURL ? moment(startDateFromURL, 'YYYY-MM-DD') : moment().subtract(29, 'days');
    let defaultEndDateDetail = endDateFromURL ? moment(endDateFromURL, 'YYYY-MM-DD') : moment();

    // Function to determine if the selected range matches a predefined option
    function getRangeLabel(startDate, endDate) {
        let ranges = {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        };

        for (let key in ranges) {
            if (startDate.isSame(ranges[key][0], 'day') && endDate.isSame(ranges[key][1], 'day')) {
                return key; // Return predefined range label
            }
        }
        return "Custom Range";
    }


    let selectedRangeKeyDetail = getRangeLabel(defaultStartDateDetail, defaultEndDateDetail);
    let defaultLabel = formatDateRangeLabel(selectedRangeKeyDetail, defaultStartDateDetail, defaultEndDateDetail);


    $('#daterangeDetail').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        startDate: defaultStartDateDetail,
        endDate: defaultEndDateDetail,
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function (start, end, label) {
        let formattedLabel = formatDateRangeLabel(label, start, end);
        $("#daterangeDetail").val(formattedLabel);


        let startDate = start.format('YYYY-MM-DD');
        let endDate = end.format('YYYY-MM-DD');
    
        // 🔹 Update URL without refreshing the page
        let newUrl = new URL(window.location.href);
        newUrl.searchParams.set('start_date', startDate);
        newUrl.searchParams.set('end_date', endDate);
        history.pushState(null, '', newUrl.toString());

        // Fetch data based on the selected date range
        fetchDetailData(startDate, endDate);
    });

    // Set initial formatted date range in the input field
    $('#daterangeDetail').val(defaultLabel);
    $("#selectedDateRangeDetailText").text(`Showing result from ${moment(defaultStartDateDetail).format("Do MMM")} to ${moment(defaultEndDateDetail).format("Do MMM YYYY")}`);
    let detailPageType = $('#detailPageType').val();

    function fetchDetailData(startDate , endDate){
        let formattedStart = moment(startDate).format('YYYY-MM-DD');
        let formattedEnd = moment(endDate).format('YYYY-MM-DD');

        // Update the display text
        $("#selectedDateRangeDetailText").text(`Showing result from ${moment(startDate).format("Do MMM")} to ${moment(endDate).format("Do MMM YYYY")}`);
        getAndFetchDetailPages(formattedStart,formattedEnd,detailPageType);
    }
    let pdfTable ;
    let viewdUsedTiles;

    /** Datatable initialization */
    if( $('#pdf_table').length){
        pdfTable = new DataTable('#pdf_table', {
            responsive: true,
            pageLength: 10,
            searching: true,
            ordering: false,
            autoWidth: false,
        });
    }

    if( $('#viewed_used_table').length){
        viewdUsedTiles = new DataTable('#viewed_used_table', {
            responsive: true,
            pageLength: 10,
            searching: true,
            ordering: true,
            order: [], // disables default sorting on first column
            autoWidth: false,
            columnDefs: [
                {
                    targets: Array.from({ length: 10 }, (_, i) => i), // [0 to 9]
                    orderable: false
                }
            ]
        });
    }

    setTimeout(() => {
        $('#pdf_table').removeClass('dataTable');
        $('#viewed_used_table').removeClass('dataTable');
    }, 5); // adjust delay if needed
    

    getAndFetchDetailPages(startDateFromURL,endDateFromURL,detailPageType);

    function fetchReportDetail(startDate , endDate , type){
        $.ajax({
            url: '/get_detail_report',  // Update with your actual API endpoint
            type: 'POST',
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
            data: { startDate, endDate , type },
            success: function(response) {
                if( $('#pdf_table').length || $('#viewed_used_table').length){
                    // Destroy previous table if exists
                    pdfTable.destroy();
                    viewdUsedTiles.destroy();
                }

                $('#'+detailPageType+'_tbody').html(response.body);

                if( $('#pdf_table').length || $('#viewed_used_table').length){
                    // Reinitialize DataTable
                    pdfTable = $('#pdf_table').DataTable({
                        responsive: true,
                        pageLength: 10,
                        searching: true,
                        ordering: false,
                        autoWidth: false
                    });

                    /** Datatable initialization */
                    viewdUsedTiles = new DataTable('#viewed_used_table', {
                        responsive: true,
                        pageLength: 10,
                        searching: true,
                        ordering: true,
                        autoWidth: false,
                        order: [], // disables default sorting on first column
                        columnDefs: [
                            {
                                targets: Array.from({ length: 10 }, (_, i) => i), // [0 to 9]
                                orderable: false
                            }
                        ]
                    });
                }
            },
            error: function(error) {
                console.error("Error loading Data:", error);
            }
        });
    }

    function  getAndFetchDetailPages(formattedStart,formattedEnd,detailPageType)
    {
        if( detailPageType === "pincode")
            fetchReportDetail(formattedStart, formattedEnd , "pincode");

        if( detailPageType === "appliedTiles")
            fetchReportDetail(formattedStart, formattedEnd , "appliedTiles");

        if( detailPageType === "roomCategories")
            fetchReportDetail(formattedStart, formattedEnd , "roomCategories");

        if( detailPageType === "tiles")
            fetchReportDetail(formattedStart, formattedEnd , "tiles");

        if( detailPageType === "rooms")
            fetchReportDetail(formattedStart, formattedEnd , "rooms");

        if( detailPageType === "showroom")
            fetchReportDetail(formattedStart, formattedEnd , "showroom");

        if( detailPageType === "pdf")
            fetchReportDetail(formattedStart, formattedEnd , "pdf");

        if( detailPageType === "ai-studio")
            fetchReportDetail(formattedStart, formattedEnd , "ai-studio");
    }
}