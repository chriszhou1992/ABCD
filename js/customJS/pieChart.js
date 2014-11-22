function showPieChart(data) {
    var max = data.splice(0, 1);
    if(max[0].label.lastIndexOf("!-!-!") !== -1) {
        max[0].label = max[0].label.substring(0, max[0].label.lastIndexOf("!-!-!")) + ' and ' + max[0].label.substring(max[0].label.lastIndexOf("!-!-!")+5);
        max[0].label = max[0].label.replace(/!-!-!/g, ", ");
    }
     $("#suggestFieldPieLabel").html("<hr><div class='alert alert-white'><i class='fa fa-lightbulb-o'></i>&nbsp&nbspBased on the distribution, you might want to look into games published by <span style='color: orangered;'>" + max[0].label + "</span>.</div>");
    
    var total = 0;
    for(var i = 0; i < data.length; i++) {
        total += data[i].value;
    }
    
    var w = $("#suggestFieldPie").width();
    var h = w/2 + 80;
    var r = w/4;
    var ir = 2*r/3;
    var labelr = r + 30;

    var pie = d3.layout.pie().value(function(d) { 
        return d.value; 
    });    
    
    var color = d3.scale.category10();
    
    var arc = d3.svg.arc()
        .innerRadius(ir)
        .outerRadius(r);

    var vis = d3.select("#suggestFieldPie").append("svg:svg")         
        .attr("width", w)
        .attr("height", h)
        .data([data])
        .append("svg:g")
            .attr("transform", "translate(" + (w/2) + "," + (h/2) + ")");

    //GROUP FOR LABELS
    var label_group = vis.append("svg:g")
        .attr("class", "label_group")
        .attr("transform", "translate(" + 0 + "," + 0 + ")");

    //GROUP FOR CENTER TEXT  
    var center_group = vis.append("svg:g")
        .attr("class", "center_group")
        .attr("transform", "translate(" + 0 + "," + 0 + ")");

    ///////////////////////////////////////////////////////////
    // CENTER TEXT ////////////////////////////////////////////
    ///////////////////////////////////////////////////////////

    //WHITE CIRCLE BEHIND LABELS
    var whiteCircle = center_group.append("svg:circle")
        .attr("fill", "white")
        .attr("r", ir);

    // "TOTAL" LABEL
    var totalLabel = center_group.append("svg:text")
        .attr("class", "label")
        .attr("dy", -15)
        .attr("text-anchor", "middle") // text-align: right
        .text("Games you liked and");

    //TOTAL TRAFFIC VALUE
    var totalValue = center_group.append("svg:text")
        .attr("class", "total")
        .attr("dy", 7)
        .attr("text-anchor", "middle") // text-align: right
        .text("their publishers");

    //UNITS LABEL
    var totalUnits = center_group.append("svg:text")
        .attr("class", "units")
        .attr("dy", 21)
        .attr("text-anchor", "middle") // text-align: right
        .text();
       

    
    var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
        .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
        .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
            .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
                .attr("class", "slice");    //allow us to style things in the slices (like text)
 
        arcs.append("svg:path")
                .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
                .attr("d", arc);                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function
        /*
        arcs.append("svg:text")                                     //add a label to each slice
                .attr("transform", function(d) {                    //set the label's origin to the center of the arc
                    //we have to make sure to set these before calling arc.centroid
                    d.innerRadius = ir;
                    d.outerRadius = r;
                    return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
                })
            .attr("text-anchor", "middle")                          //center the text on it's origin
            .text(function(d, i) { return data[i].label; });        //get the label from our original data array
            */
        arcs.append("svg:text")
            .attr("transform", function(d) {
                var c = arc.centroid(d),
                    x = c[0],
                    y = c[1],
                    // pythagorean theorem for hypotenuse
                    h = Math.sqrt(x*x + y*y);
                return "translate(" + (x/h * labelr) +  ',' +
                   (y/h * labelr) +  ")"; 
            })
            .attr("dy", ".35em")
            .attr("text-anchor", function(d) {
                // are we past the center?
                return (d.endAngle + d.startAngle)/2 > Math.PI ?
                    "end" : "start";
            })
            .text(function(d, i) { return data[i].label; });
}

