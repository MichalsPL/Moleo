$(document).ready(function(){
var dps = [];

var chart = new CanvasJS.Chart("chartContainer",
    {
        title: {
            text: "Chart with Date Selector"
        },
        data: [
            {
                type: "line",
                dataPoints: randomData(new Date(2017, 0, 1), 400)
            }
        ]
    });
chart.render();

var axisXMin = chart.axisX[0].get("minimum");
var axisXMax = chart.axisX[0].get("maximum");

function randomData(startX, numberOfY){
    var xValue, yValue = 0;
    for (var i = 0; i < 400; i += 1) {
        xValue = new Date(startX.getTime() + (i * 24 * 60 * 60 * 1000));
        yValue += (Math.random() * 10 - 5) << 0;

        dps.push({
            x: xValue,
            y: yValue
        });
    }
    return dps;
}

$( function() {
    $("#fromDate").val(CanvasJS.formatDate(axisXMin, "DD MMM YYYY"));
    $("#toDate").val(CanvasJS.formatDate(axisXMax, "DD MMM YYYY"));
    $("#fromDate").datepicker({dateFormat: "d M yy"});
    $("#toDate").datepicker({dateFormat: "d M yy"});
});

$("#date-selector").change( function() {
    var minValue = $( "#fromDate" ).val();
    var maxValue = $ ( "#toDate" ).val();

    if(new Date(minValue).getTime() < new Date(maxValue).getTime()){
        chart.axisX[0].set("minimum", new Date(minValue));
        chart.axisX[0].set("maximum", new Date(maxValue));
    }
});

    $('#my-select').multiSelect({
        afterSelect: function(values){
            alert("Select value: "+values);
        },
        afterDeselect: function(values){
            alert("Deselect value: "+values);
        }
    });
});