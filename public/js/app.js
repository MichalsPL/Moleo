$(document).ready(function(){
var dps = [];

function PrepareCanvasJs(data){
    var chart = new CanvasJS.Chart("chartContainer",
        {
            title: {
                text: "Chart with Date Selector"
            },
            axisX:{
                interval: 1,
                intervalType: "day",
            },
            data: data
        });
    chart.render();

    var axisXMin = chart.axisX[0].get("minimum");
    var axisXMax = chart.axisX[0].get("maximum");
         $("#fromDate").val(CanvasJS.formatDate(axisXMin, "DD MMM YYYY"));
     $("#toDate").val(CanvasJS.formatDate(axisXMax, "DD MMM YYYY"));
     $("#fromDate").datepicker({dateFormat: "d M yy"});
     $("#toDate").datepicker({dateFormat: "d M yy"});
    $("#date-selector").change( function() {
        var minValue = $( "#fromDate" ).val();
        var maxValue = $ ( "#toDate" ).val();

        if(new Date(minValue).getTime() < new Date(maxValue).getTime()){
            chart.axisX[0].set("minimum", new Date(minValue));
            chart.axisX[0].set("maximum", new Date(maxValue));
        }
    });
}

// var chart = new CanvasJS.Chart("chartContainer",
//     {
//         title: {
//             text: "Chart with Date Selector"
//         },
//         data: data
//     });
// chart.render();


function getData(){
    $.ajax({
        url: "/ajax/getCurrencies",
        type: "POST",
        data: {
            currencies : $('#my-select').val(),
            date_start:2,
            date_end:3
        },
        dataType: "json",
        success: function(Response){
            var charData=prepareData(Response);
            PrepareCanvasJs(charData);
        }
    });
}
getData();
function PrepareDailyHistory(dailyData,currencyName){
    var result = []

    $.each(dailyData,function(){
        var t = this;
        console.log(t)
        result.push({ x: new Date(t.date.split('-')[0],t.date.split('-')[1],t.date.split('-')[2]), y: t.mid_price })

    });
    console.log(result)
    return result;
}
function prepareData(data){
    var result =[];
    $.each(data,function(index,value){

        result.push({
            name: value.name,
            type: "spline",
            showInLegend: true,
            dataPoints: PrepareDailyHistory(value.history,value.name)

        });
    });
    return result;
}

    $('#my-select').multiSelect({
        afterSelect: function(values){
            getData()
        },
        afterDeselect: function(values){
            getData()
        }
    });
});