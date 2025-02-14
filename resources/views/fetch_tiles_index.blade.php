@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Fetch Product Data</h3>
            <div class="form-group">
                <input type="hidden" value="{{$api_details->last_fetch_date_from_api}}" id="last_fetch_date_val">
                <p>Last Fetched Date:
                    <span id="last-fetched-date">
                        @if( $api_details->last_fetch_date_from_api === NULL)
                            No Fetch till Date
                        @else
                            {{Carbon::parse($api_details->last_fetch_date_from_api)->format('d M Y')}}
                            ( {{$api_details->fetch_products_count}} records has been fetch )
                        @endif
                    </span>
                    <hr>
                    <span id="total_result"></span>
                </p>
            </div>
            <div class="form-group">
                <div id="progress-container" style="display: none;">
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar" style="width: 0%"></div>
                    </div>
                    <p id="progress-text">0 records processed...</p>
                </div>
                <button id="fetch-now" class="btn btn-primary">Fetch Now</button>
            </div>

            <div class="form-group">
                <h3>Skipped Records</h3>
                <ul id="error-list"></ul>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            document.getElementById('fetch-now').addEventListener('click', function () {
                let fetchButton = document.getElementById('fetch-now');
                let progressContainer = document.getElementById('progress-container');
                let progressBar = document.getElementById('progress-bar');
                let progressText = document.getElementById('progress-text');
                let errorList = document.getElementById('error-list');

                const lastFetchedDate = $('#last_fetch_date_val').val() || "2000-01-01";
                const todayDate = new Date().toISOString().slice(0, 10);

                fetchButton.disabled = true;
                fetchButton.innerText = "Processing...";

                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                progressText.innerText = '0 records processed...';
                errorList.innerHTML = '';

                fetch("/fetch-data", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ start_date: lastFetchedDate, end_date: todayDate })
                })
                    .then(response => response.json())
                    .then(result => {
                        let totalRecords = result.total_records;
                        let progressInterval = setInterval(() => {
                            fetch("/fetch-progress")
                                .then(res => res.json())
                                .then(progressData => {
                                    if (progressData.total > 0) {
                                        let percentage = (progressData.processed / progressData.total) * 100;
                                        progressBar.style.width = `${percentage}%`;
                                        progressText.innerText = `${progressData.processed} of ${progressData.total} records processed (SKU: ${progressData.sku}, Surface: ${progressData.surface})`;

                                        if (progressData.processed >= progressData.total) {
                                            clearInterval(progressInterval);
                                            progressText.innerText = "Processing complete!";
                                            progressBar.style.width = "100%";
                                            fetchButton.disabled = false;
                                            fetchButton.innerText = "Fetch Now";
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error("Error fetching progress:", error);
                                });
                        }, 2000);
                    })
                    .catch(error => {
                        progressText.innerText = "Error fetching data!";
                        console.error(error);
                        fetchButton.disabled = false;
                        fetchButton.innerText = "Fetch Now";
                    });
            });

        </script>
    @endpush
@endsection