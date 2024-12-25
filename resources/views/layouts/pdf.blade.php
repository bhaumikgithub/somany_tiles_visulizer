<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tiles Visualizer</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/pdf.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div>
    @yield('content')
</div>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/js/app.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/room/add_to_pdf_room.js"></script>
<script>
('#tilecal').modal({
      backdrop: 'static',
      keyboard: false
})
$('#tilecal').on('click', '.modal-backdrop', function(e){
      e.preventDefault();
});

$(document).on('keydown', function(e){
      if (e.key === "Escape") {
        e.preventDefault();
      }
});
</script>
<script>


    var tilesIn1Box = 4; //pieces this should come from DB
var lb = "<br/>"

$("#calculate_btn").click(function () {
	if(validationCheck()==false){
  	return false;
  }  
	
  var widthInFeet = $("#width_feet").val();
  var heightInFeet = $("#length_feet").val();
  
  var wastage = $("#wast_per").val();
  
  var totalArea =  widthInFeet * heightInFeet;
  var totalAreaSqMeter = totalArea/10.764;
 
  var wastageOfTilesArea = (totalArea * wastage)/100;
  var actualWallFloorArea = Number(totalArea + wastageOfTilesArea);
  
  var tileWidthInFeet = getSizeOfTiles("#tiles_size","LEFT");
  var tileHeightInFeet = getSizeOfTiles("#tiles_size","RIGHT");

  var tilesArea =  (tileWidthInFeet * tileHeightInFeet );
  
  var tilesNeeded =  Math.ceil(actualWallFloorArea/tilesArea);
  var boxNeeded = Math.ceil(tilesNeeded/tilesIn1Box);
  
  
 	displayResult("#area_covered_meter","Total Area covered : <b>" + totalAreaSqMeter.toFixed(2)+"</b> Sq. Meter");
  displayResult("#area_covered_feet","Total Area covered : <b>" + totalArea.toFixed(2)+"</b> Sq. Feet");
  displayResult("#required_tiles","Required Tiles : <b>" + tilesNeeded+"</b> Tiles");
  displayResult("#required_box","Required Boxes : <b>" + boxNeeded+"</b> <small>(1 box have "+tilesIn1Box+" Tiles)</small>");
  $('#tilecal').modal('show');
  
  
})
$("#reset_btn").click(function(){
	$("#width_feet").val("");
  $("#length_feet").val("");
  $("#tiles_size").val("");
  $("#wast_per").val("");
  
  displayResult("#area_covered_meter","");
  displayResult("#area_covered_feet","");
  displayResult("#required_tiles","");
  displayResult("#required_box","");
});
function getSizeOfTiles(p_sizeId,p_side){
	var sizeString = $(p_sizeId).val();
  var arr = sizeString.split("x");
  if(p_side=="LEFT"){
  	return (arr[0]/10)*0.0328;//mm to feet
  }
  if(p_side=="RIGHT"){
  	return (arr[1]/10)*0.0328;//mm to feet
  }
}
function displayResult(p_displayid,p_message){
	var html = $(p_displayid).html();
  $(p_displayid).html(p_message);
}

function validationCheck(){
  var errorMessage = "";
  if ($("#width_feet").val() == "") {
    errorMessage += "- Please enter floor/wall width\n";
  }
  if ($("#length_feet").val() == "") {
    errorMessage += "- Please enter floor/wall length/height\n";
  }
  
  if ($("#tiles_size").val() == "") {
  	errorMessage += "- Please select tiles size\n";
  }

  if ($("#wast_per").val() == "") {
    errorMessage += "- Please enter wastage percentage\n";
  }
  if(errorMessage == ""){
  	return true;
  }
  else{
  	alert(errorMessage);
 		return false;
  }
}



</script>
@stack('custom-scripts')
</body>
</html>
