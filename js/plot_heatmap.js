function extractDataFromCSV(csv) {
    var x = [];
    var y = [];
    var z = [];
    var labels = new Map();
    for(var i=1; i<csv[0].length; ++i)
        labels.set(csv[0][i], i-1);
    labels = new Map([...labels.entries()]);
    var order = Array.from(labels.values());
    for(var i=1; i<csv[0].length; ++i)
        x.push(csv[0][order[i-1]+1]);
    for(var i=2; i<csv.length; ++i) {
        y.push(csv[i][0]);
        row = [];
        for(var j=1; j<csv[i].length; ++j)
            row.push(parseFloat(csv[i][order[j-1]+1]).toFixed(3));
        z.push(row);
    }
    return [x, y, z];
}

function makePlot(div_id, heatmapData, height) {
    var graphDiv = document.getElementById(div_id);
    var minHeight = 400;
    var computedHeight = (20*heatmapData[1].length + 100);
    var displayHeight = (height === null) ? ((computedHeight < minHeight) ? minHeight : computedHeight) : 600;

    var data = [{
            x: heatmapData[0],
            y: heatmapData[1],
            z: heatmapData[2],
            xgap: 0,
            ygap: 0,
            zmid: 0,
            colorscale: 'RdBu',
            reversescale: false,
            colorbar: {
                len: 0.7,
                title: {
                    text: '<i>log<sub>2</sub></i> Fold Change',
                    side: 'right',
                    font: {size: 18}
                }
            },
            type: 'heatmap'
        }];

    var layout = {
        plot_bgcolor: '#ffffff',//'#fff0f5',
        paper_bgcolor: '#ffffff',//'#fff0f5',
        height: displayHeight,
        bargap: 10,
        margin: {
            t: 80,
            l: 100,
            b: 20
        },
        hoverlabel: {
            font: {size: 16}
        },
        xaxis: {
            visible : true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 14},
            side: 'top',
            title : {
                text : 'Studies',
                font: {size: 22}
            }
        },
        yaxis: {
            visible : true,
            showgrid: false,
            color: 'black',
            linewidth: 2,
            ticks: 'outside',
            ticklen: 10,
            tickwidth: 2,
            tickfont: {size: 10},
            title : {
                text : "Gene Name",
                font: {size: 22}
            }
        }
    };

    Plotly.newPlot(graphDiv, data, layout, {showSendToCloud:false});
}

function plotAlphabetHeatmap(alphabet, div_id, data) {
    document.getElementById(div_id).innerHTML = '';
    x = data[0];
    y = data[1];
    z = data[2];
    y_new = [];
    z_new = [];
    for(var i=0; i<y.length; ++i){
        if (y[i][0] == alphabet) {
            y_new.push(y[i]);
            z_new.push(z[i]);
        }
    }
    if (y_new.length > 0)
        makePlot(div_id, [x, y_new, z_new], null);
    else
        document.getElementById(div_id).innerHTML = '<p>No proteins starting with "' + alphabet + '"</p>';
    document.getElementById('alA').scrollIntoView({behavior:'smooth'});
}

function plotHeatmap(div_id, response) {
    document.getElementById(div_id).innerHTML = '';
    var csv = $.csv.toArrays(response);
    var data = extractDataFromCSV(csv);

    makePlot(div_id, data, 800);
//     document.getElementById(div_id).innerHTML += data[0] + '<br/></br/>' + data[1] + '<br/></br/>' + data[2] + '<br/></br/>';
    return data;
}

