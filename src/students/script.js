
$(document).ready( function() {

    let titleRow =  '<td>Item</td>' +
                    '<td>Description</td>' +
                    '<td>Cost</td>';

    let header = '<br>' + 'Please break down your funding into an itemized list:'

    // row that takes user input
    // width does nothing, use size
    let generalRow = '<td style><input type="text" width="25%"/></td>' +
                     '<td><input type="text" width="50%"/></td>' +
                     '<td><input type="text" width="25%"/></td>';

    // set up the table [if align: "left", buttons lose alignment
    // generate table
    let tmp = $(document.createElement('table')).attr("align","left").append(header);
    let tblBody = tmp.appendTo("#table");

    tblBody.css({"width":"100%",
                 "border-collapse":"collapse",
                 "margin":"auto",
                 "background-color":"f7f7f7" });

    $('#table').append( tblBody );
    tblBody.append( '<tr>' + titleRow + '</tr>');
    tblBody.append( '<tr>' +  generalRow + '</tr>' );

    // click event to generate new rows
    $('#increment').click( function() {
        tblBody.append( '<tr>' +  generalRow + '</tr>' );
    });

});