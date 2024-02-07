<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>mitoPADdb - Mitochondrial Proteins Associated with Diseases database</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
        <script>
            function getPathwayData(pathwayType, div_id){
                if (pathwayType == 'KEGG')
                    var query = 'get_kegg_pathway.php';
                else
                    var query = 'get_mitocarta_pathway.php';

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var pathwayTreeData = JSON.parse(this.responseText);
                        $('#' + div_id).jstree({ 'core':
                            pathwayTreeData
                        });
//                         document.getElementById('foo').innerHTML = this.responseText;
                    }
                };
                xmlhttp.open('GET', query, true);
                xmlhttp.setRequestHeader("Content-type", "text/json");
                xmlhttp.send();
            }
        </script>
        <style>
            .jstree-node {
                padding-left: 100px;
                padding-top: 2px;
                padding-bottom: 2px;
/*                 transition: all 0.3s ease; */
            }
            #j1_1 {
                padding-left: 10px;
            }
        </style>
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

        <!--<div class = "section_left"></div>-->

        <?php
            if ($_GET["key"] === "KEGG" || $_GET["key"] === "MC") {
        ?>
                <div class = "section_middle">
                    <p>
                        <b>N.B.-</b>
                        <b>First</b>, users need to double-click on a pathway to get a list
                        of genes associated with a pathway. <b>Further</b>, users need to
                        double-click on a  gene to get a list of diseases associated with
                        that gene.
                    </p>
                    <div id="pathway_tree_div"></div>
<!--                     <div id="foo"></div> -->
                </div>
                <script>
                    getPathwayData(<?php echo "'".$_GET["key"]."'" ?>, 'pathway_tree_div');
                </script>
        <?php
            } else {
                echo "<div class = \"section_middle\"><p>Invalid pathway type selected !! Cannot display pathways.</p></div>";
            }
        ?>
    </body>
</html>
