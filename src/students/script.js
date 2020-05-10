
$(document).ready( function() {

    let headerRow =  '<th>Item</th>' +
                     '<th>Description</th>' +
                     '<th>Cost</th>';

    let colgroup =  '<colgroup>'+
                    '<col width="20%">' +
                    '<col width="50%">' +
                    '<col width="20%">' +
                    '</colgroup>';

    // row that takes user input
    // width does nothing, use size
    let generalRow = '<td style><input type="text" width="25%"/></td>' +
                     '<td><input type="text" width="50%"/></td>' +
                     '<td><input type="text" width="25%"/></td>';


    // set up the table [if align: "left", buttons lose alignment
    // generate table
    let tblBody = $(document.createElement('table')).attr("align","left")
                                                              .append(colgroup)
                                                              .appendTo('#table');

    tblBody.css({"width":"100%",
                        "border-collapse":"collapse",
                        "margin":"auto",
                        "background-color":"f7f7f7" });

    $('#table').append( tblBody );
    tblBody.append( '<tr>' + headerRow + '</tr>');
    tblBody.append( '<tr>' +  generalRow + '</tr>' );

    // click event to generate new rows
    $('#increment').click( function() {
        tblBody.append( '<tr>' +  generalRow + '</tr>' );
    });

});