var duration;

class Duration {
   

    constructor(p_combo, p_result) {
        this.durationCombo = $("#"+p_combo);
        this.resultDiv = $("#"+p_result);
    }
    
    initEvents(){
        this.durationCombo.change(function(){
            duration.updateDurationFromCombobox();
        });
    }

    updateDurationFromCombobox(){
        var date = new Date();
        var fromDate;
        var toDate;

        switch(this.getComboboxValue()){
            case "TODAY":
                fromDate = date;
                toDate = date;
            break;
            case "YESTERDAY":
                fromDate = date;
                toDate = date;
            break; 
            case "LAST_7_DAYS":
                fromDate = date;
                toDate = date;
            break;
            case "THIS_MONTH":
                fromDate = date;
                toDate = date;
            break;
            case "LAST_MONTH":
                fromDate = date;
                toDate = date;
            break;
            case "THIS_YEAR":
                fromDate = date;
                toDate = date;
            break;
        }
    } 
    displayResult(){

    }
    getComboboxValue(){
        return this.durationCombo.val();
    }
}

$(document).ready(function() {
    duration = new Duration("duration-combo","showing_result");
    duration.initEvents();
});


