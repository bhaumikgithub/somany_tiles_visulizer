var layoutMode = "LANDSCAPE";

function isThisMobileDevice(){
    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/Opera Mini/i) || navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/Windows Phone/i)) {

        return true;

    }
    return false;
};

function AdjustCanvasWidthHeight() {

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();

    var newWidth = windowWidth;
    var newHeight = windowHeight;

    var topPanelHeight = newHeight - 20;

    var newCanvasHeight = windowHeight;
    var newCanvasWidth = windowWidth;

    //Check if mode is landscape or portrait

    //FOR LANDSCAPE
    if (windowWidth > windowHeight) {
        layoutMode = "LANDSCAPE";

        newWidth = windowHeight * 1.78;
        newCanvasWidth = newWidth;

        topPanelTopPosition = 10;
        $("#topPanelHideBtn").css({ "top": "auto" });
        $("#container").width(windowWidth);
    }
    else {//FOR PORTRAIT
        layoutMode = "PORTRAIT";

        newHeight = windowHeight / 2;
        newWidth = windowWidth;

        newCanvasHeight = newHeight;
        newCanvasWidth = newHeight * 1.78;

        topPanelHeight = newHeight;
        topPanelTopPosition = newHeight + 20;

        //Center up / down button pressed
        $("#topPanelHideBtn").css({ "top": topPanelTopPosition - 50 });

        if (firstTime == true) {
            firstTime = false;
            hideTopPanelMainPanel();
        }
        $("#container").width(windowWidth);
        $("#container").css("overflow-x", "auto");

        setTimeout(function () {
            $("#container").scrollLeft((newCanvasWidth - windowWidth) / 2);

        }, 300);
    }

    $("#selectd-data").css("max-height", Math.round(topPanelHeight - 20));



    $("#topPanelTilesListBox").height(topPanelHeight - 130);
    $("#grout-list").height(topPanelHeight - 250 + 120);
    $("#layout-list").height(topPanelHeight - 130 - 35);
    $("#topPanelThemeListBox").height(topPanelHeight - 220);



    newLeft = Math.round((windowWidth - newWidth) / 2);
    newRight = Math.round((windowWidth - newLeft - newWidth)) + 6;  // Calculate new right position

    $("#roomCanvas").height(newCanvasHeight);
    $("#roomCanvas").width(newCanvasWidth);

    $("#container").css({ left: newLeft });

    //$("#topPanel").css('top',newHeight + 'px');

    $('.top-panel').css('top', topPanelTopPosition + 'px');
    $('.top-panel').css('height', topPanelHeight + 'px'); // Set height dynamically
    $("#productInfoPanel").hide();
    heightAdjust();
    $("#roomCanvas").height(newCanvasHeight);
    $("#roomCanvas").width(newCanvasWidth);

    if (topPanelCustomVisible == true) {
        setTopPanelOpenPosition(false);
    }
    else {
        setTopPanelClosedPosition(false);
    }


    if (layoutMode == "PORTRAIT") {
        $(".back-btn").css({ left: newLeft });
        $(".cn-btn").css("right", "0px");
        $(".share-btn-img").css("right", "18px");
        $(".share-div").css("right", "18px");
    }
    else {
        $(".back-btn").css({ left: newLeft });
        $(".cn-btn").css({ right: newRight });
        $(".share-div").css({ right: newRight + 21 });
        share_button();

    }
    if(isThisMobileDevice()==true){
        $('.share-btn-img').show();
        $('.share-div').hide();
    }
    else{
        $('.share-btn-img').hide();
        $('.share-div').show();
        $(".share-btn-img").css({right:70});
    }

    showHideTabs();

}

function allLoadCompleted() {
    $(".cmn-room-btn").css('visibility', 'visible');
    $("#topPanelmainpanel").css('visibility', 'visible');
    $(".share-div").css('visibility', 'visible');
    $(".room-canvas").css('visibility', 'visible');

    showProductContent();

}


function showProductContent() {
    $('#topPanelGrout').hide();
    $('#topPanelLayout').hide();
    $('.radio-surface-rotation-wrap').show();


    $(".partOfProductTabContent").show();
    $('.partOfProductTabContent-wrap').show();

    if(isThisMobileDevice()==true){
        $(".partOfProductTabButtons").show();
        setPanelToggleStatus('.serach-pad-set', '#searchIconToggle');
        setPanelToggleStatus('.filterContentPanel', '#sliderIconToggle');
    }
    else{
        $(".partOfProductTabButtons").hide();
    }

}

function setPanelToggleStatus(p_panelIdorClass, p_buttonIdorClass) {
    if ($(p_panelIdorClass).css('display') == 'none') {
        if (p_buttonIdorClass) {
            $(p_panelIdorClass).show();
            $(p_buttonIdorClass).addClass('top-panel-button-active');
            heightAdjust();
        }
        return false;
    }
    if (p_buttonIdorClass) {
        $(p_panelIdorClass).hide();
        $(p_buttonIdorClass).removeClass('top-panel-button-active');
        heightAdjust();
    }
    return true;
}
function heightAdjust() {
    setTimeout(function () {
        if ($(".search-filter-panel-box").height() < 10) {
            $(".search-filter-panel-box").hide();
        }
        else {
            $(".search-filter-panel-box").show();
        }

        $(".search-filter-panel-box").animate({ "top": $('#topPanel').offset().top - $(".search-filter-panel-box").height() - 15 });
    }, 10);

}

$("#btnProduct").addClass("top-panel-button-active");

$('#btnProduct').on('click', function () {
    activeTab = "PRODUCT";
    showHideTabs();
});

$('#btnLayout').on('click', function () {
    activeTab = "LAYOUT";
    showHideTabs();
});

$('#btnGrout').on('click', function () {
    activeTab = "GROUT";
    showHideTabs();
    setTimeout(function () {

        AdjustCanvasWidthHeight();
    }, 0);
});

/**********************************************************************

 This function is calling from 2d.min.js when user press Filter button

 This function's role
 - This function call when user press filter button on front side
 - Find the available filters Category
 - Create dummy navigation and click the first one

 This function do following
 - Get the filter block craeated by original code in 2d.min.js
 - Create separate dummy navigation HTML to overcome existing structure issue
 - Add html into the new navigation panel
 - Show navigation panel

 *********************************************************************/

 var allnavLinks = [];
 function customFilterManagement() {
     $("#topPanelNavFilter").empty();
     allnavLinks = [];
 
     var filterBlocks = $("#topPanelFilter").find(".filter-block");
 
     var htmlStr = "<ul>";
     var clickedCategoryName = "";
 
     for (var i = 0; i < filterBlocks.length; i++) {
 
         var filterBlock = filterBlocks.eq(i);
 
         if ($(filterBlock).css('display') == 'none') {
             /*
             There are many object which are hidden if those tiles are not present.
             We do not consider those titles and remain display none as it is
             */
         }
         else {
 
             //Get the title of the Category
             var titleHeader = filterBlock.find(".-header-title");
             var filterNameID = titleHeader.attr("id");
             var splitter = filterNameID.split("_");
             //-headerId_wall_Finishes
             var filterName = String(splitter[1] + "_" + splitter[2]).toLowerCase();
 
             if (clickedCategoryName == "") {
                 clickedCategoryName = filterName;
             }
             allnavLinks.push({ "navName": filterName });
             $(filterBlock).attr("id", filterName);
             //Create list (clickable horizontal category list)
             htmlStr += "<li onclick=clickFilterCategory('" + filterName + "',this) id='filterclick_" + filterName + "' class='filter-click' style='text-transform:capitalize;'>" + splitter[2] + "</li>"; ``
         }
 
     }
     htmlStr += "</ul>";
 
     $("#topPanelNavFilter").html(htmlStr);
 
     clickFilterCategory(clickedCategoryName);
 
     showAllFilters(true);
 }
 
 /***********************************************************************
  This function is calling from customFilterManagement
 
  This function do following
  - Hide original Category listing (made by owner of code)
  - Open the content of the clicked category
  - Remove existing active class and add newly clicked category
  - Keep height of the remaining div to 0 to avoid height movement during clicking
  - Reset height and adjust height of newly added content
  ***********************************************************************/
 
 function clickFilterCategory(p_filterName) {
    $('.-body').hide();
    $('#-body_' + p_filterName).css({ "display": "flex" });
 
    $(".filter-click").removeClass("filter-click-active");
    $("#filterclick_" + p_filterName).addClass("filter-click-active");

    $("#filterclick_" + p_filterName).data("allUnChecked")

    $(".filter-block").css({ "height": "0px", "margin": "0px" });


    $('#-body_' + p_filterName).parent(".filter-block").css({ "height": "auto", "margin": "null" });
    $("#topPanelFilter").show();

     heightAdjust();
 }
 
 /***********************************************************************
  This function is calling from 2d.min.js
 
  This function Role
  - User click on the checkboxes of category
  - This function count how many checkboxes checked and count total number
 
  This function do following
  - Total length of the checked checkboxes
  - Count number and return
  ***********************************************************************/
 function totalFilterCheckboxesChecked() {
     var checked = $(".checkboxClass");
     var totalCheckboxes = checked.length;
     var count = 0;
     for (var i = 0; i < totalCheckboxes; i++) {
         var obj = $(checked).eq(i);
         if ($(obj).prop("checked")) {
             count++;
         }
     }
     //checkAllBlankCategories();
     return count;
 }
 function checkAllBlankCategories(p_category,p_wallorfloor) {
 
    var checkBoxes =$("#"+p_wallorfloor+"_"+p_category).find(".checkboxClass");
 
     var totalCheckboxes = checkBoxes.length;
     var count = 0;
     for (var j = 0; j < totalCheckboxes; j++) {
         var checkBoxIndividual = $(checkBoxes).eq(j);
         if ($(checkBoxIndividual).prop("checked")) {
             count++;
         }
     }
     return count;
 }

 function showAllFilters(p_show) {
    if (p_show == true) {
        $("#topPanelNavFilter").show();
    }
    else {
        $("#topPanelNavFilter").hide();
    }
}