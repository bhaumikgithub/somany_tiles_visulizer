@if (Session::has('error'))
  <div class="alert alert-danger">
    <strong>{{ session('error') }}</strong>
  </div>
@endif

@if (Session::has('info'))
  <div class="alert alert-info">
    <strong>{{ session('info') }}</strong>
  </div>
@endif

@if (Session::has('success'))
  <div class="alert alert-success">
    <strong>{{ session('success') }}</strong>
  </div>
@endif

@if (Session::has('warning'))
  <div class="alert alert-warning">
    <strong>{{ session('warning') }}</strong>
  </div>
@endif


<div id="warningAlertBox" class="alert alert-warning alert-top-right" onclick="window.$(this).fadeOut();" style="display: none">
    <strong>Warning!</strong> No one item selected.
</div>

<div id="successAlertBox" class="alert alert-success alert-top-right" onclick="window.$(this).fadeOut();" style="display: none">
    <strong>Success!</strong> ...
</div>

<div id="infoAlertBox" class="alert alert-info alert-top-right" onclick="window.$(this).fadeOut();" style="display: none">
    <strong>Info!</strong> ...
</div>

<div id="dangerAlertBox" class="alert alert-danger alert-top-right" onclick="window.$(this).fadeOut();" style="display: none">
    <strong>Danger!</strong> ...
</div>
