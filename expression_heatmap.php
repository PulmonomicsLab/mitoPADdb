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

    $dg = urldecode($_GET["dg"]);
//     echo $dg."<br/>";
//     echo implode(", ", $friendlyNames)."<br/>";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>mitoPADdb - Mitochondrial Proteins Associated with Diseases database</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/plot_heatmap.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
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
            <div style="width:100%;">
                <p>
                    In the heatmap, the X-axis and Y-axis represent expression studies and gene symbols
                    respectively. To visualize the heatmap of selected genes starting with a particular
                    alphabet, users need to select an alphabet (A-Z) provided below. In case, there are
                    no genes available for the selected alphabet, a message will show that <i>&quot;No
                    genes starting with alphabet&quot;</i>. Here, the Log<sub>2</sub> Fold Change values
                    [diseased vs control] were used to plot the expression heatmap.
                </p>
            </div>
<!--             <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div> -->

            <div style="width:100%;" id="plot_container_1"></div>
            <br/>

            <script>
                var data = null;
                function getHeatmapData(disease_group, f_name) {
                    var prefix = 'input/ExpressionHeatmap/';
                    var file = prefix + disease_group + '.csv';
                    var display = f_name;

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById('display_text').innerHTML = '<h3>' + display + '</h3>';
//                             document.getElementById('download_div').innerHTML = '<a href="' + file + '"><button type="button" style="margin:2px;">Download data</button></a>';
                            data = plotHeatmap('plot_container_1', this.responseText);
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                }

                <?php echo "getHeatmapData('".$dg."','".$friendlyNames[$dg]."');"; ?>
            </script>

            <div id="alA" class="alphabet" onclick="plotAlphabetHeatmap('A', 'plot_container_2', data)">A</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('B', 'plot_container_2', data)">B</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('C', 'plot_container_2', data)">C</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('D', 'plot_container_2', data)">D</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('E', 'plot_container_2', data)">E</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('F', 'plot_container_2', data)">F</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('G', 'plot_container_2', data)">G</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('H', 'plot_container_2', data)">H</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('I', 'plot_container_2', data)">I</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('J', 'plot_container_2', data)">J</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('K', 'plot_container_2', data)">K</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('L', 'plot_container_2', data)">L</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('M', 'plot_container_2', data)">M</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('N', 'plot_container_2', data)">N</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('O', 'plot_container_2', data)">O</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('P', 'plot_container_2', data)">P</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('Q', 'plot_container_2', data)">Q</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('R', 'plot_container_2', data)">R</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('S', 'plot_container_2', data)">S</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('T', 'plot_container_2', data)">T</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('U', 'plot_container_2', data)">U</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('V', 'plot_container_2', data)">V</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('W', 'plot_container_2', data)">W</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('X', 'plot_container_2', data)">X</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('Y', 'plot_container_2', data)">Y</div>
            <div class="alphabet" onclick="plotAlphabetHeatmap('Z', 'plot_container_2', data)">Z</div>
            <div style="clear:both;"></div>
            <div style="width:100%;" id="plot_container_2"></div>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2024 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a class="link" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a class="link" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
