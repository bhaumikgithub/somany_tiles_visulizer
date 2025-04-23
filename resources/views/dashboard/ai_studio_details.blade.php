<div class="row mt-3">
    <div class="col-12 d-flex grid-margin stretch-card">
    <div class="card">
            <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between">
                <h4 class="card-title mb-3">Summary for AI Studio</h4>
                
            </div>
            
            <hr>
            <div class="row">
                <div class="col-4">
                <h2 class="mb-2 mt-2 font-weight-bold">{{@number_format($totalAISession)}}</h2>
                <div class="">
                    Total Sessions
                </div>
                </div>
                <div class="col-4">
                <!--<div class="text-info mb-1">
                    URL Generated
                </div>-->
                <h2 class="mb-2 mt-2 font-weight-bold">{{@number_format($reachToSummaryPage)}}</h2>
                <div class="">
                    Session reached to Summary Page
                </div>
                </div>
                <div class="col-4">
                <!-- <div class="text-info mb-1">
                    Downloaded PDF
                </div> -->
                <h2 class="mb-2 mt-2  font-weight-bold">{{@number_format($pdfsFromUserPdfDataCount)}}</h2>
                <div class="">
                    Download PDFs
                </div>
                </div>
            </div>
            
            </div>
        </div>
    </div>
</div>