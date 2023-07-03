<?php
    $friendlyNames = array(
        "CD" => "Cardiovascular Diseases",
        "CHNDA" => "Congenital, Hereditary, and Neonatal Diseases and Abnormalities",
        "DSD" => "Digestive System Diseases",
        "ED" => "Eye Diseases",
        "HLD" => "Hemic and Lymphatic Diseases",
        "MD" => "Mental Disorders",
        "MSD" => "Musculoskeletal Diseases",
        "NEO" => "Neoplasms",
        "NSD" => "Nervous System Diseases",
        "NMD" => "Nutritional and Metabolic Diseases",
        "OD" => "Otorhinolaryngologic Diseases",
        "PCSS" => "Pathological Conditions, Signs and Symptoms",
        "RTD" => "Respiratory Tract Diseases",
        "UD" => "Urogenital Diseases",
    );

    $dg1 = $_GET["dg1"];
    $dg2 = $_GET["dg2"];
//     echo $dg1."<br/>".$dg2;
//     echo implode(", ", $friendlyNames)."<br/>";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>mitoPADdb - Mitochondrial Proteins Associated with Diseases database</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/plot_heatmap_pair.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
        <script>
            var dg1 = '<?php echo $dg1 ?>';
            var dg2 = '<?php echo $dg2 ?>';
            var f_name1 = '<?php echo $friendlyNames[$dg1] ?>';
            var f_name2 = '<?php echo $friendlyNames[$dg2] ?>';
        </script>
    </head>
    <body>
        <div class = "section_header">
            <center><p class="title">mitoPADdb - Mitochondrial Proteins Associated with Diseases database</p></center>
        </div>

        <div class = "section_menu">
            <center>
            <table cellpadding="3px">
                <tr class="nav">
                    <td class="nav"><a href="index.html" class="side_nav">Home</a></td>
                    <td class="nav"><a href="browse.html" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="statistics.html" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <div class = "section_middle">
            <center><p id="display_text"></p></center>

            <div class="alphabet" onclick="getHeatmapData('A', 'plot_container_1', dg1, dg2)">A</div>
            <div class="alphabet" onclick="getHeatmapData('B', 'plot_container_1', dg1, dg2)">B</div>
            <div class="alphabet" onclick="getHeatmapData('C', 'plot_container_1', dg1, dg2)">C</div>
            <div class="alphabet" onclick="getHeatmapData('D', 'plot_container_1', dg1, dg2)">D</div>
            <div class="alphabet" onclick="getHeatmapData('E', 'plot_container_1', dg1, dg2)">E</div>
            <div class="alphabet" onclick="getHeatmapData('F', 'plot_container_1', dg1, dg2)">F</div>
            <div class="alphabet" onclick="getHeatmapData('G', 'plot_container_1', dg1, dg2)">G</div>
            <div class="alphabet" onclick="getHeatmapData('H', 'plot_container_1', dg1, dg2)">H</div>
            <div class="alphabet" onclick="getHeatmapData('I', 'plot_container_1', dg1, dg2)">I</div>
            <div class="alphabet" onclick="getHeatmapData('J', 'plot_container_1', dg1, dg2)">J</div>
            <div class="alphabet" onclick="getHeatmapData('K', 'plot_container_1', dg1, dg2)">K</div>
            <div class="alphabet" onclick="getHeatmapData('L', 'plot_container_1', dg1, dg2)">L</div>
            <div class="alphabet" onclick="getHeatmapData('M', 'plot_container_1', dg1, dg2)">M</div>
            <div class="alphabet" onclick="getHeatmapData('N', 'plot_container_1', dg1, dg2)">N</div>
            <div class="alphabet" onclick="getHeatmapData('O', 'plot_container_1', dg1, dg2)">O</div>
            <div class="alphabet" onclick="getHeatmapData('P', 'plot_container_1', dg1, dg2)">P</div>
            <div class="alphabet" onclick="getHeatmapData('Q', 'plot_container_1', dg1, dg2)">Q</div>
            <div class="alphabet" onclick="getHeatmapData('R', 'plot_container_1', dg1, dg2)">R</div>
            <div class="alphabet" onclick="getHeatmapData('S', 'plot_container_1', dg1, dg2)">S</div>
            <div class="alphabet" onclick="getHeatmapData('T', 'plot_container_1', dg1, dg2)">T</div>
            <div class="alphabet" onclick="getHeatmapData('U', 'plot_container_1', dg1, dg2)">U</div>
            <div class="alphabet" onclick="getHeatmapData('V', 'plot_container_1', dg1, dg2)">V</div>
            <div class="alphabet" onclick="getHeatmapData('W', 'plot_container_1', dg1, dg2)">W</div>
            <div class="alphabet" onclick="getHeatmapData('X', 'plot_container_1', dg1, dg2)">X</div>
            <div class="alphabet" onclick="getHeatmapData('Y', 'plot_container_1', dg1, dg2)">Y</div>
            <div class="alphabet" onclick="getHeatmapData('Z', 'plot_container_1', dg1, dg2)">Z</div>
            <div style="clear:both;"></div>

            <br/>
            <div style="width:100%;" id="plot_container_1"></div>
            <br/>
        </div>

        <script>
            function getHeatmapData(alphabet, div_id, d1, d2) {
                document.getElementById('display_text').innerHTML = '<h3>' + f_name1 + ' (' + dg1 + ') - ' + f_name2 + ' (' + dg2 + ') | Alphabet: ' + alphabet + '</h3>';
                var data1 = null;
                var data2 = null;
                var dgs = [d1, d2];
                var xmlhttp = new XMLHttpRequest();
                (function one(index){
                    if(index > 2)
                        plotHeatmap(div_id, alphabet, data1, data2, dg1, dg2, '', '');

                    var prefix = 'input/PairwiseExpressionHeatmap/';
                    var file = prefix + dgs[index - 1] + '.csv';
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            if (index == 1)
                                data1 = this.responseText;
                            else
                                data2 = this.responseText;
                            one(index + 1);
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                })(1);
            }
            getHeatmapData('A', 'plot_container_1', dg1, dg2);
        </script>
    </body>
</html>
