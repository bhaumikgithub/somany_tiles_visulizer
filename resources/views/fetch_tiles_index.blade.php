@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Fetch Product Data</h3>
            <div class="form-group">
                @if( $api_details->last_fetch_date_from_api !== NULL)
                    @if($api_details->last_fetch_date_from_api == $lastFetchDateFromRecord)
                        <input type="hidden" value="{{$api_details->last_fetch_date_from_api}}" id="last_fetch_date_val">
                    @else
                        <input type="hidden" value="{{$lastFetchDateFromRecord}}" id="last_fetch_date_val">
                    @endif
                @else
                    <input type="hidden" value="2000-01-01" id="last_fetch_date_val">
                @endif
                <p>Last Fetched Date:
                    <span id="last-fetched-date">
                        @if( $api_details->last_fetch_date_from_api === NULL)
                            No Fetch till Date
                        @else
                            {{Carbon::parse($api_details->last_fetch_date_from_api)->format('d M Y')}}
                            ( {{$api_details->fetch_products_count}} records has been fetch )
                        @endif
                    </span>
                    <p class="fromTodate" style="display: none">Fetching Records from <span class="fromDate"></span> to <span class="toDate"></span></p>
                    <hr>
                    <span id="total_result"></span>
                </p>
            </div>
            <div class="form-group">
                <div id="progress-container" style="display: none;">
                    <div class="progress" style="position: relative !important;width: 100% !important;margin: auto !important;height: 15px;height: 22px;font-weight: 600;">
                        <div id="progress-bar" class="progress-bar" style="width: 0%;height: 30px !important;background-color: green !important;width: 0% !important;text-align: center !important;color: white !important;"></div>
                    </div>
                    <p id="progress-text">Waiting...</p>
                </div>
                <button id="fetch-now" class="btn btn-primary">Fetch Now</button>
            </div>

            <div class="form-group skippedRecordWrapper" style="display: none;">
                <h4 style="color: red;">Skipped / Error Records</h4>
                <ul id="error-list"></ul> <!-- Skipped/Error records list -->
            </div>
        </div>
    </div>

    @push('custom-scripts')
{{--        <script>--}}
{{--            document.getElementById('fetch-now').addEventListener('click', function () {--}}
{{--                let fetchButton = document.getElementById('fetch-now');--}}
{{--                let progressContainer = document.getElementById('progress-container');--}}
{{--                let progressBar = document.getElementById('progress-bar');--}}
{{--                let progressText = document.getElementById('progress-text');--}}
{{--                let errorList = document.getElementById('error-list');--}}

{{--                const lastFetchedDate = $('#last_fetch_date_val').val() || "2000-01-01";--}}
{{--                const todayDate = new Date().toISOString().slice(0, 10);--}}

{{--                fetchButton.disabled = true;--}}
{{--                fetchButton.innerText = "Processing...";--}}

{{--                progressContainer.style.display = 'block';--}}
{{--                progressBar.style.width = '0%';--}}
{{--                progressText.innerText = '0 records processed...';--}}
{{--                errorList.innerHTML = '';--}}

{{--                fetch("/fetch-data", {--}}
{{--                    method: "POST",--}}
{{--                    headers: {--}}
{{--                        "Content-Type": "application/json",--}}
{{--                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')--}}
{{--                    },--}}
{{--                    body: JSON.stringify({ start_date: lastFetchedDate, end_date: todayDate })--}}
{{--                })--}}
{{--                    .then(response => response.json())--}}
{{--                    .then(result => {--}}
{{--                        let totalRecords = result.total_records;--}}
{{--                        let progressInterval = setInterval(() => {--}}
{{--                            fetch("/fetch-progress")--}}
{{--                                .then(res => res.json())--}}
{{--                                .then(progressData => {--}}
{{--                                    if (progressData.total > 0) {--}}
{{--                                        let percentage = (progressData.processed / progressData.total) * 100;--}}
{{--                                        progressBar.style.width = `${percentage}%`;--}}

{{--                                        // ✅ Show individual record progress--}}
{{--                                        progressText.innerText = progressData.status;--}}

{{--                                        if (progressData.processed >= progressData.total) {--}}
{{--                                            clearInterval(progressInterval);--}}
{{--                                            progressText.innerText = "Processing complete!";--}}
{{--                                            progressBar.style.width = "100%";--}}
{{--                                            fetchButton.disabled = false;--}}
{{--                                            fetchButton.innerText = "Fetch Now";--}}
{{--                                        }--}}
{{--                                    }--}}
{{--                                })--}}
{{--                                .catch(error => {--}}
{{--                                    console.error("Error fetching progress:", error);--}}
{{--                                });--}}
{{--                        }, 2000);--}}
{{--                    })--}}
{{--                    .catch(error => {--}}
{{--                        progressText.innerText = "Error fetching data!";--}}
{{--                        console.error(error);--}}
{{--                        fetchButton.disabled = false;--}}
{{--                        fetchButton.innerText = "Fetch Now";--}}
{{--                    });--}}
{{--            });--}}
{{--        </script>--}}
        <script>
            $(document).ready(function () {
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                $('#fetch-now').on('click', function () {
                    let startDate = $('#last_fetch_date_val').val();
                    let endDate = new Date().toISOString().slice(0, 10);
                    let errorList = $('#error-list');

                    let progressBar = document.getElementById('progress-bar');
                    let progressText = document.getElementById('progress-text');
                    let fetchButton = $('#fetch-now');

                    fetchButton.prop('disabled', true).text("Processing...");
                    $('#progress-container').show();
                    progressBar.style.width = "0%";
                    progressText.innerText = "0 records processed...";
                    errorList.html('');

                    $('.fromTodate').show();
                    $('.fromDate').text(startDate);
                    $('.toDate').text(endDate);

                    $.ajax({
                        url: "/fetch-data",
                        type: "POST",
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Include CSRF Token
                        },
                        success: function (response) {
                            console.log("Processing started successfully!");
                        },
                        error: function (xhr, status, error) {
                            progressText.text("Error fetching data!");
                            console.error("Error:", error);
                            $('#fetch-now').prop('disabled', false).text("Fetch Now");
                        }
                    });

                    function updateProgress() {
                        $.ajax({
                            url: "/fetch-progress",
                            method: "GET",
                            cache: false,
                            data: { t: new Date().getTime() }, // Add timestamp to bypass cache
                            success: function (progressData) {
                                console.log("Progress Response:", progressData); // ✅ Debug in Console
                                if (progressData.total > 0) {
                                    let percentage = Math.min((progressData.processed / progressData.total) * 100, 100);
                                    progressBar.style.width = `${percentage}%`;
                                    progressBar.innerText = `${Math.round(percentage)}%`;
                                    progressText.innerText = `${progressData.processed} / ${progressData.total} records processed`;

                                    // Display skipped/error records with unique SKUs
                                    let errorList = $("#error-list");
                                    errorList.html(""); // Clear previous records

                                    let seenSkus = new Set(); // Track unique SKUs
                                    if (progressData.skipped_records && progressData.skipped_records.length > 0) {
                                        $('.skippedRecordWrapper').show();
                                        let table = `
                                        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
                                            <thead>
                                                <tr style="background-color: #f8d7da; color: #721c24;">
                                                    <th style="padding: 8px;">#</th>
                                                    <th style="padding: 8px;">Tile Name</th>
                                                    <th style="padding: 8px;">SAP (SKU)</th>
                                                    <th style="padding: 8px;">Date</th>
                                                    <th style="padding: 8px;">Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                                            let index = 1;
                                            progressData.skipped_records.forEach((record) => {
                                                if (!seenSkus.has(record.sku)) { // ✅ Prevent duplicate SKUs
                                                    seenSkus.add(record.sku);
                                                    table += `
                                                        <tr>
                                                            <td style="padding: 8px;">${index++}</td>
                                                            <td style="padding: 8px;">${record.name}</td>
                                                            <td style="padding: 8px;">${record.sku}</td>
                                                            <td style="padding: 8px;">${record.date}</td>
                                                            <td style="padding: 8px; color: red;">${record.reason}</td>
                                                        </tr>`;
                                                }
                                            });

                                        table += `</tbody></table>`;
                                        errorList.append(table);
                                    }
                                    if (progressData.processed >= progressData.total) {
                                        progressText.innerText = "Processing complete!";
                                        progressBar.style.width = "100%";
                                        fetchButton.prop('disabled', false).text("Fetch Now");
                                        clearInterval(progressInterval);
                                    }
                                }
                            }
                        });
                    }

                    let progressInterval = setInterval(updateProgress, 2000);
                });
            });
        </script>
    @endpush
@endsection