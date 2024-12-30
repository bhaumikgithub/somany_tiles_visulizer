@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Fetch Product Data</h3>
            <div class="form-group">
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
                <div id="progress-container" style="display: none; margin: 20px 0;">
                    <div style="border: 1px solid #ccc; width: 100%; background-color: #f3f3f3; height: 20px; position: relative;">
                        <div id="progress-bar" style="width: 0%; height: 100%; background-color: green;"></div>
                    </div>
                    <p id="progress-text" style="text-align: center; margin-top: 5px;">0 of 0 records processed...</p>
                </div>
                <button id="fetch-now" class="btn btn-primary">Fetch Now</button>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            document.getElementById('fetch-now').addEventListener('click', function () {
                const lastFetchedDate = "2024-12-01"; // Replace with actual last fetched date
                const todayDate = new Date().toISOString().slice(0, 10); // Current date (YYYY-MM-DD)

                const progressContainer = document.getElementById('progress-container');
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const fetchResult = document.getElementById('last-fetched-date');

                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                progressText.innerText = '0 of 0 records processed...';

                fetch("{{ route('fetch.data') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ start_date: lastFetchedDate, end_date: todayDate }),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch data');
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.success) {
                            const totalRecords = result.total_records;
                            let processedRecords = 0;

                            progressText.innerText = `${processedRecords} of ${totalRecords} records processed...`;

                            const interval = setInterval(() => {
                                // Simulate processing one record at a time
                                processedRecords++;
                                const percentage = Math.min((processedRecords / totalRecords) * 100, 100);

                                // Update progress bar and text
                                progressBar.style.width = `${percentage}%`;
                                progressText.innerText = `${processedRecords} of ${totalRecords} records...`;

                                if (processedRecords >= totalRecords) {
                                    clearInterval(interval);
                                    progressText.innerText = 'Processing complete!';
                                    progressBar.style.width = '100%';

                                    fetchResult.innerText = result.updated_message;

                                    const insertedCount = result.insertedCount;
                                    const updatedCount = result.updatedCount;
                                    const unchangedCount = result.unchangedCount;

                                    let resultMessage;

                                    if (insertedCount === 0 && updatedCount === 0) {
                                        resultMessage = 'No new records inserted or updated.';
                                    } else {
                                        resultMessage = `Processed ${totalRecords} records:
                                        - ${insertedCount} record(s) inserted.
                                        - ${updatedCount} record(s) updated.
                                        - ${unchangedCount} record(s) unchanged.`;
                                    }
                                    document.getElementById('total_result').innerText = resultMessage;
                                    setTimeout(() => {
                                        //progressContainer.style.display = 'none';
                                    }, 2000);
                                }
                            }, 50); // Adjust an interval for progress speed
                        } else {
                            fetchResult.innerText = 'Error processing data';
                        }
                    })
                    .catch(error => {
                        fetchResult.innerText = error.message;
                        console.error(error);
                    });
            });

        </script>
    @endpush
@endsection