<div class="row mt-3">
    <div class="col-12 d-flex grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between">
                <h4 class="card-title mb-3">Summary for Showrooms</h4>
                
            </div>
            
            <hr>
            <div class="row">
                <div class="col-4">
                <!--<div class="text-info mb-1">
                    URL Generated
                </div>-->
                <h2 class="mb-2 mt-2 font-weight-bold">{{number_format(@$totalSessionCount)}}</h2>
                <div class="">
                    Total Sessions
                </div>
                </div>
                <div class="col-4">
                <!--<div class="text-info mb-1">
                    URL Generated
                </div>-->
                <h2 class="mb-2 mt-2 font-weight-bold">{{number_format(@$summaryPageCount)}}</h2>
                <div class="">
                    Session reached to Summary Page
                </div>
                </div>
                <div class="col-4">
                <!-- <div class="text-info mb-1">
                    Downloaded PDF
                </div> -->
                <h2 class="mb-2 mt-2  font-weight-bold">{{number_format(@$totalCustomers)}}</h2>
                <div class="">
                    Customers
                </div>
                </div>
            </div>
            
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Showroom Data in Details</h4>
            
            <div class="table-responsive">
                <table class="table">
                <thead>
                    <tr>
                        <th>Showroom Name</th> 
                        <th>City</th>
                        <th>Session Created</th>
                        <th>Summary Page</th>
                        <th>Customers</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($showroomData))
                        @foreach($showroomData as $aShowRoom)
                            <tr>
                                <td>{{$aShowRoom['name']}}</td>
                                <td>{{$aShowRoom['city']}}</td>
                                <td>{{$aShowRoom['session_created']}}</td>
                                <td>{{$aShowRoom['summary_page']}}</td>
                                <td>{{$aShowRoom['customers']}}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                    </tr>
                    <tr>
                </tbody>
                </table>
            </div>
            
            </div>
        </div>
    </div>

</div>