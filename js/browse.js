// rows = null;
// rows = [1,2,3];

function hideDiv(divId){
    document.getElementById(divId).style.display = 'none';
}

function check_disease_pair(id1, id2) {
    var dg1 = document.getElementById(id1).value;
    var dg2 = document.getElementById(id2).value;
    if (dg1 === dg2){
        alert('Disease 1 and Disease 2 cannot be same. Please select two different disease groups.');
        return false;
    } else
        return true;
}

function getStudies(diseaseGroup, key, div_id) {
    var query = 'get_studies.php?key=' + encodeURIComponent(diseaseGroup);
    var hideButton = '<button type="button" class="round" style="margin-right:10px;" onclick="hideDiv(\'' + div_id + '\')">&#10005;</button>';
    var heatmapButton = '<a href="expression_heatmap.php?dg=' + key + '"><button type="button" class="round">Get expression heatmap</button></a>';
    var menublock = '<center>' +
                        '<div style="margin-top:10px;">' +
                            hideButton + heatmapButton +
                        '</div>' +
                    '</center>';
    var resultElement = document.getElementById(div_id);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var rows = JSON.parse(this.responseText);
            var s = '<table class="browse-result-summary" border="1"><tr><th>Study ID</th>' +
                                                                        '<th>Disease category</th>' +
                                                                        '<th>Disease name</th>' +
                                                                        '<th>Sample</th>' +
                                                                        '<th>Log<sub>2</sub> Fold Change Subjects [Sample size]</th>' +
                                                                        '<th>Condition/State</th>' +
                                                                        '<th>GEO Accession</th>'
                    '</tr>';
            for(var i=0; i<rows.length; ++i) {
                s += '<tr>';
                s += '<td>' + rows[i].StudyID + '</td>';
                s += '<td>' + rows[i].DiseaseCategory + '</td>';
                s += '<td>' + rows[i].DiseaseName + '</td>';
                s += '<td>' + rows[i].Sample + '</td>';
                s += '<td>' + rows[i].FoldChangeSubjects + '</td>';
                s += '<td>' + rows[i].ConditionState + '</td>';
                s += '<td><a class="link" href="https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=' + rows[i].GEOAccession + '">' + rows[i].GEOAccession + '</td>';
                s += '</tr>';
            }
            s += '</table>';

            var studyCountMessage = '<center><p>The number studies found in the database for "<i>' + diseaseGroup + '</i>" = <b>' + rows.length + '</b></p></center>';

            resultElement.innerHTML = menublock + studyCountMessage + s + menublock;
            resultElement.style.display = 'block';
        }
    };
    xmlhttp.open('GET', query, true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}

function getDiseases(diseaseGroup, div_id) {
    var query = 'get_diseases.php?key=' + encodeURIComponent(diseaseGroup);
    var hideButton = '<center><button type="button" class="round" style="margin-top:10px;" onclick="hideDiv(\'' + div_id + '\')">&#10005;</button></center>';
    var resultElement = document.getElementById(div_id);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var rows = JSON.parse(this.responseText);
            var s = '<table class="browse-result-summary" border="1"><tr><th>Disease ID</th><th>Disease name</th><th>Disease category</th><th>Protein Associations</th></tr>';
            for(var i=0; i<rows.length; ++i) {
                s += '<tr>';
                s += '<td><a class="link" href="disease.php?key=' + rows[i].MADID + '">' + rows[i].MADID + '</a></td>';
                s += '<td>' + rows[i].DiseaseName + '</td>';
                s += '<td>' + rows[i].DiseaseCategory + '</td>';
                s += '<td>' + rows[i].ProteinAssociations + '</td>';
                s += '</tr>';
            }
            s += '</table>';

            var diseaseCountMessage = '<center><p>The number diseases found in the database for "<i>' + diseaseGroup + '</i>" category = <b>' + rows.length + '</b></p></center>';

            resultElement.innerHTML = hideButton + diseaseCountMessage + s + hideButton;
            resultElement.style.display = 'block';
        }
    };
    xmlhttp.open('GET', query, true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}

function getProteins(character, resultDivId){
    var query = 'get_proteins.php?key=' + encodeURIComponent(character);
    var hideButton = '<center><button type="button" class="round" style="margin-top:10px;" onclick="hideDiv(\'' + resultDivId + '\')">&#10005;</button></center>';
    var resultElement = document.getElementById(resultDivId);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var rows = JSON.parse(this.responseText);
            var s = '<table class="browse-result-summary" border="1"><tr><th>UniProt accession ID</th><th>Gene name</th><th>Disease Associations</th></tr>';
            for(var i=0; i<rows.length; ++i) {
                s += '<tr>';
                s += '<td><a class="link" href="protein.php?keytype=ID&key=' + rows[i].UniProtAccession + '">' + rows[i].UniProtAccession + '</a></td>';
                s += '<td>' + rows[i].GeneName + '</td>';
                s += '<td>' + rows[i].DiseaseAssociations + '</td>';
                s += '</tr>';
            }
            s += '</table>';

            var proteinCountMessage = '<center><p>The number proteins found in the database starting with "' + character + '" category = <b>' + rows.length + '</b></p></center>';

            resultElement.innerHTML = hideButton + proteinCountMessage + s + hideButton;
            resultElement.style.display = 'block';
        }
    };
    xmlhttp.open('GET', query, true);
    xmlhttp.setRequestHeader("Content-type", "text/json");
    xmlhttp.send();
}
