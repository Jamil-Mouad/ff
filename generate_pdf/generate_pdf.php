<?php
session_start();

$selected_filiere = $_SESSION['selected_filiere'];
$selected_annee = $_SESSION['selected_annee'];
$selected_stage = $_SESSION['selected_stage'];
$selected_etudiant = $_SESSION['selected_etudiant'];

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "f_evaluation";
$port = 3307; // Port MySQL

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
$sql_filere="SELECT Nom_filiere,Abbreviation_filiere 
             FROM Filiere
             WHERE ID_filiere=" . intval($selected_filiere);
$result_filiere=$conn->query($sql_filere);
if($result_filiere->num_rows === 1){
    while ($row_filier = $result_filiere->fetch_assoc()) {
        $Nom_filiere=$row_filier["Nom_filiere"];
        $Abbreviation_filiere=$row_filier["Abbreviation_filiere"];
    }
}

$sql_stage="SELECT Type_stage,Abbreviation_stage
            FROM stage
            WHERE ID_stage=".intval($selected_stage);
$result__stage=$conn->query($sql_stage);
if($result__stage->num_rows === 1){
    while ($row_stage = $result__stage->fetch_assoc()) {
        $Nom__stage=$row_stage["Type_stage"];
        $Abbreviation__stage=$row_stage["Abbreviation_stage"];
    }
}

$sql_year= "SELECT Description_annee
            FROM annee_scolaire
            WHERE ID_annee=".intval($selected_annee);
$result_year=$conn->query($sql_year);
if($result_year->num_rows === 1){
    while ($row_year = $result_year->fetch_assoc()) {
        $Nom_year=$row_year["Description_annee"];
    }
}

$sql_student = "SELECT CONCAT(Nom_etudiant, ' ', Prenom_etudiant) AS FullName
                FROM Etudiant 
                WHERE Numero_D_apogee=".intval($selected_etudiant);
$result_student = $conn->query($sql_student);
if($result_student ->num_rows === 1){
    while ($row_student = $result_student ->fetch_assoc()) {
        $Nom_etudiant= $row_student ["FullName"];
    }
}

$sql_rapport = "SELECT Titre_Rapport, Nom_Prenom_encadrent, Note_rapport, Note_presentation_orale, Note_encadrant, Note_finale
                FROM rapport
                WHERE Numero_D_apogee = ".intval($selected_etudiant)." AND ID_stage = ".intval($selected_stage);
$result_rapport = $conn->query($sql_rapport);
if ($result_rapport->num_rows === 1) {
    while ($row_rapport = $result_rapport->fetch_assoc()) {
        $Titre_Rapport = $row_rapport["Titre_Rapport"];
        $Nom_Prenom_encadrent = $row_rapport["Nom_Prenom_encadrent"];
        $Note_rapport = $row_rapport["Note_rapport"];
        $Note_presentation_orale = $row_rapport["Note_presentation_orale"];
        $Note_encadrant = $row_rapport["Note_encadrant"];
        $Note_finale = $row_rapport["Note_finale"];
    }
}

$sql_percent="SELECT Pourcentage_rapport,Pourcentage_presentation_orale,Pourcentage_encadrant
              FROM pourcentage
              WHERE ID_filiere=".intval($selected_filiere);
$result_percent = $conn->query($sql_percent);
if($result_percent ->num_rows === 1){
    while ($row_percent = $result_percent ->fetch_assoc()) {
        $Percent_rapport=$row_percent["Pourcentage_rapport"];
        $Percent_presentation_orale=$row_percent["Pourcentage_presentation_orale"];
        $Percent_encadrant=$row_percent["Pourcentage_encadrant"];
    }
}

$sql_soutenance="SELECT Numero_soutenance,Date_soutenance,Heure_soutenance,Lieu_soutenance
                 FROM soutenance
                 WHERE ID_stage=".intval($selected_stage)." AND Numero_D_apogee=".intval($selected_etudiant);
$result_soutenance = $conn->query($sql_soutenance);
if($result_soutenance ->num_rows === 1){
    while ($row_soutenance = $result_soutenance ->fetch_assoc()) {
        $Numero_soutenance=$row_soutenance["Numero_soutenance"];
        $Date_soutenance=$row_soutenance["Date_soutenance"];
        $Heure_soutenance=$row_soutenance["Heure_soutenance"];
        $Lieu_soutenance=$row_soutenance["Lieu_soutenance"];
    }
}

$sql_jury_num="SELECT COUNT(ID_jury) AS jury_num
               FROM jury_soutenance
               WHERE ID_soutenance=".intval($Numero_soutenance);
$result_jury_num = $conn->query($sql_jury_num);
if($result_jury_num->num_rows > 0){
    while($row_jury_num = $result_jury_num ->fetch_assoc()){
        $jury_num=$row_jury_num["jury_num"];
    }
}            

$jury=array();
$sql_jury="SELECT ID_jury,role_du_jury
           FROM jury_soutenance
           WHERE ID_soutenance=".intval($Numero_soutenance);
$result_jury = $conn->query($sql_jury);
if($result_jury->num_rows > 0){
    while($row_jury = $result_jury ->fetch_assoc()){
        $jury[$row_jury["ID_jury"]]=$row_jury["role_du_jury"];
    }
}

$jury_details = array();
foreach ($jury as $id_jury => $role_du_jury) {
    $sql_prof = "SELECT Nom_prof, Prenom_prof
                 FROM Membre_Jury
                 WHERE ID_prof = $id_jury";
    $result_prof = $conn->query($sql_prof);
    if ($result_prof->num_rows > 0) {
        $row_prof = $result_prof->fetch_assoc();
        $jury_details[] = array(
            'NomPrenom' => $row_prof['Nom_prof'] . ' ' . $row_prof['Prenom_prof'],
            'Role' => $role_du_jury
        );
    }
}
//DUT OR LP
$position = strpos($Nom_year, "DUT");
if($position !== false){
    $is_dut="DUT ";
}else{
    $is_dut="LP ";
}

//filiere
//for title
$fiche=$Nom__stage ." (". $Abbreviation__stage .")";
$currentYear = date("Y");
$academicYear =  $is_dut.$Abbreviation_filiere." ("."Promotion " . $currentYear . "/" . $currentYear+1 .")";
$filiere=$is_dut.$Nom_filiere." (".$Abbreviation_filiere.")";
$fil=$is_dut.$Abbreviation_filiere
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate PDF</title>
    <link rel="stylesheet" href="generate_pdf.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
</head>
<body>
    <div id="pdfContent">
        <header>
        <img src="EST Fkih Ben Saleh (1).png" class="header-img">
        <div class="header-txt">                                                    
            <p>Royaume du Maroc</p>
            <p>Ministère de l'Education Nationale, de la Formation Professionnelle,</p>
            <p>de l'Enseignement Supérieur et de la Recherche Scientifique</p>
            <p>Université Sultan Moulay Slimane</p>
            <p>L’Ecole Supérieure de Technologie – Fkih Ben Salah</p></div>
        <img src="UMS.png" class="header-img" >
        </header>
        <main>

        <div class="main-txt">
            <h3>Fiche d'évaluation du <?php echo htmlspecialchars($fiche); ?></h3>
            <h3><?php echo htmlspecialchars($academicYear); ?></h3>
        </div>

        <div class="info">
            <p><strong>Filiere: </strong><?php echo htmlspecialchars($filiere); ?></p>
            <p><strong>Nom & Prénom de l’étudiant : </strong><?php echo htmlspecialchars($Nom_etudiant); ?></p>
            <p><strong>Titre du <?php echo htmlspecialchars($Abbreviation__stage); ?> : </strong><?php echo htmlspecialchars($Titre_Rapport); ?></p>
            <p><strong>Nom et prénom de l’encadrant : </strong><?php echo htmlspecialchars($Nom_Prenom_encadrent); ?></p>
            <div class="time-container">
                <p class="date"><strong>Date de la soutenance : </strong><?php echo htmlspecialchars($Date_soutenance); ?></p>
                <p class="time"><strong>à </strong><?php echo htmlspecialchars($Heure_soutenance); ?></p>
            </div>
            <p><strong>Lieu de la soutenance :</strong><?php echo htmlspecialchars($Lieu_soutenance); ?></p>
        </div>
        <div class="evaluation">
            <p class="t_title">Mode d'évaluation :</p>
            <table >
                <tr>
                <td><p>Rapport</p></td>
                <td><p class="percent"><?php echo htmlspecialchars($Percent_rapport*100); ?>%</p></td>
                <td><p class="note"><?php echo htmlspecialchars($Note_rapport); ?>/20</p></td>
                </tr>

                <tr>
                    <td><p>Présentation orale et discssions</p></td>
                    <td><p class="percent"><?php echo htmlspecialchars($Percent_presentation_orale*100); ?>%</p></td>
                    <td><p class="note"><?php echo htmlspecialchars($Note_presentation_orale); ?>/20</p></td>
                </tr>
        
                <tr>
                    <td><p>Note des encadrents</p></td>
                    <td><p class="percent"><?php echo htmlspecialchars($Percent_encadrant*100); ?>%</p></td>
                    <td><p class="note"><?php echo htmlspecialchars($Note_encadrant); ?>/20</p></td>
                </tr>
                
                <tr>
                    <td><p class="foot">Note finale</p></td>
                    <td><p class="percent foot" >100%</p></td>
                    <td><p class="note foot"><?php echo htmlspecialchars($Note_finale); ?>/20</p></td>
                </tr>
            </table>
        </div>
        <div class="jury">
            <p class="t_title">Membre de jury :</p>
            <table>
                <tr class="jury_txt">
                    <td><p>Membres</p></td>
                    <td><p>Nom et Prénom</p></td>
                    <td><p>Emargement</p></td>
                </tr>
                <?php
                for ($i = 0; $i < $jury_num; $i++) {
                    echo '<tr>';
                    if (isset($jury_details[$i])) {
                        echo '<td><p>' .$jury_details[$i]['Role'] . '</p></td>';
                        echo '<td><p>' .  $jury_details[$i]['NomPrenom'] . '</p></td>';
                        echo '<td><div class="content">
                                    <canvas id="signatureCanvas' . $i . '" class="block1" width="216" height="27"></canvas>
                                    <button class="block2" onclick="clearSignature(' . $i . ')">X</button>
                                </div></td>';

                    } else {
                        echo '<td><p>Unknown</p></td>';
                        echo '<td><p>Unknown</p></td>';
                        echo '<td></td>';

        }
        echo '</tr>';
    }
    ?>
            </table>
        </div>
        <div class="coordonateur">
            <div class="cdn">
                <p class="t_title">Le coordonateur de la filiere <?php echo htmlspecialchars($fil); ?></p>
            </div>
            <div class="signature-container">
                <canvas id="coordinatorSignatureCanvas" width="300" height="100"></canvas>
                <button onclick="clearCoordinatorSignature()">X</button>
            </div>
        </div>
        </main>
        <button id="generatePDF" onclick="generatePDF()">Generate PDF</button>
        <footer>
            <div class="footer-txt">
                <p>Ecole Supérieure de Technologie - Fkih Ben Salah</p>
                <p>Hay Tighnari, Route Nationale N° 11, 23200 Fkih Ben Salah, B.P: 336</p>
                <p>Tel.:05.23.43.46.66/05.23.43.49.99, 
                    Email:estfbs@usms.ma , 
                    Site Web: <a href="http://estfbs.usms.ac.ma/">http://estfbs.usms.ac.ma/</a> </p>
            </div>
        </footer>
    </div>
    <script>
        //Modifiez  JavaScript pour ajouter cette classe avant de générer le PDF
        function prepareForPDF() {
            const elementsToHide = document.querySelectorAll('.block2, #generatePDF, .signature-container button');
            elementsToHide.forEach(el => el.classList.add('hide-for-pdf'));

            const canvases = document.querySelectorAll('canvas');
            canvases.forEach(canvas => canvas.style.border = 'none');
        }

        function restoreAfterPDF() {
            const elementsToShow = document.querySelectorAll('.hide-for-pdf');
            elementsToShow.forEach(el => el.classList.remove('hide-for-pdf'));

            const canvases = document.querySelectorAll('canvas');
            canvases.forEach(canvas => canvas.style.border = '1px solid #000');
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        let jury_num=<?php echo $jury_num; ?>;
    </script>
    <script src="generate_signature.js"></script>
    <script src="befor_genrate_pdf.js"></script>
    <script src="genrate_pdf.js"></script>
</body>
</html>
