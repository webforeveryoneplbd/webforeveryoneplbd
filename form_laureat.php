<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'laureat') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather form data
    $user_id = $_SESSION['user_id'];
    $filiere_3A = $_POST['filiere'];
    $option_3A = $_POST['option'];
    $stage_PFE = $_POST['stage_pfe_location'];
    $PFE_domaine = $_POST['stage_pfe_subject'];
    $engineering_domaine = $_POST['domaine'];
    $equipe_multidisciplinaire = $_POST['collaborate'];
    $objectif_professionnel = $_POST['long_term_goals'];
    $apporter_competences = $_POST['skills_to_share'];


    $sql = "INSERT INTO laureates (user_id, filiere_3A, option_3A, stage_PFE, PFE_domaine, engineering_domaine, equipe_multidisciplinaire, objectif_professionnel, apporter_competences) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters and execute statement
    $stmt->bind_param("issssssss", $user_id, $filiere_3A, $option_3A, $stage_PFE, $PFE_domaine, $engineering_domaine, $equipe_multidisciplinaire, $objectif_professionnel, $apporter_competences);

    if ($stmt->execute()) {
  header("Location: dashboard.php");
        exit();
            } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Laureat</title>
  
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        height: 100;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        background: linear-gradient(-135deg, #14557a,
                #7acac3);
    }

    .container {
        max-width: 900px;
        width: 100%;
        background-color: #fff;
        padding: 25px 30px;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
    }

    .container .title {
        font-size: 25px;
        font-weight: 500;
        position: relative;
    }

    .container .title::before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 30px;
        border-radius: 5px;
        background: linear-gradient(-135deg, #14557a,
                #7acac3);
        ;
    }

    .content form .user-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: 20px 0 12px 0;
    }

    form .user-details .input-box {
        margin-bottom: 15px;
        width: calc(100% / 2 - 20px);
    }

    form .input-box span.details {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
    }
    .user-details .input-box input:not([type="radio"]) {
    height: 45px;
    width: 100%;
    outline: none;
    font-size: 16px;
    border-radius: 5px;
    padding-left: 15px;
    border: 1px solid #ccc;
    border-bottom-width: 2px;
    transition: all 0.3s ease;
}


    .user-details .input-box input:focus,
    .user-details .input-box input:valid {
        border-color: #9b59b6;
    }

    form .gender-details .gender-title {
        font-size: 20px;
        font-weight: 500;
    }

    form .category {
        display: flex;
        width: 80%;
        margin: 14px 0;
        justify-content: space-between;
    }

    form .category label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    form .category label .dot {
        height: 18px;
        width: 18px;
        border-radius: 50%;
        margin-right: 10px;
        background: #d9d9d9;
        border: 5px solid transparent;
        transition: all 0.3s ease;
    }

    #dot-1:checked~.category label .one,
    #dot-2:checked~.category label .two,
    #dot-3:checked~.category label .three {
        background: #9b59b6;
        border-color: #d9d9d9;
    }

    form input[type="radio"] {
    display: inline-block;
    width: 25px;
    height: 25px;
    margin: 0;
    padding: 0;
}

    form .button {
        height: 45px;
        margin: 35px 0;
        display: flex;
        justify-content: center;
    }

    form .button input {
        height: 100%;
        padding: 10px;
        width: 100%;
        border-radius: 25px;
        border: none;
        color: #fff;
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(-135deg, #14557a,
                #7acac3);
        border-radius: 25px;
    }


    @media(max-width: 584px) {
        .container {
            max-width: 100%;
        }

        form .user-details .input-box {
            margin-bottom: 15px;
            width: 100%;
        }

        form .category {
            width: 100%;
        }

        .content form .user-details {
            max-height: 300px;
            overflow-y: scroll;
        }

        .user-details::-webkit-scrollbar {
            width: 5px;
        }
    }

    @media(max-width: 459px) {
        .container .content .category {
            flex-direction: column;
        }
    }

    .user-details .input-box select,
    .user-details .input-box textarea {
        height: 45px;
        width: 100%;
        outline: none;
        font-size: 16px;
        border-radius: 5px;
        padding-left: 15px;
        border: 1px solid #ccc;
        border-bottom-width: 2px;
        transition: all 0.3s ease;
    }

    .user-details .input-box select:focus,
    .user-details .input-box select:valid,
    .user-details .input-box textarea:focus,
    .user-details .input-box textarea:valid {
        border-color: #14557a ;
    }

    .user-details .input-box select {
        appearance: none;
        /* Remove default dropdown arrow */
        cursor: pointer;
    }

    .user-details .input-box::after {
        content: "";
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 8px solid #ccc;
        transition: all 0.3s ease;
    }

    .user-details .input-box select:focus::after {
        border-top-color: #9b59b6;
    }

    .user-details .input-box textarea {
        height: 100px;
        /* Adjust height as needed */
        padding-top: 10px;
    }
</style>

<body>
    <div class="container">
        <div class="title">Form Laureat</div>
        <div class="content">
            <form method="post" action="">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Quelle était votre filière en 3ème année ?</span>
                        <select name="filiere" required>
                            <option value="Entrepreneuriat">Entrepreneuriat</option>
                            <option value="Conception et innovation">Conception et innovation</option>
                            <option value="Recherche">Recherche</option>
                            <option value="Management">Management</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Quelle était votre option en 3ème année ?</span>
                        <select name="option" required>
                            <option value="Énergie et développement durable">Énergie et développement durable</option>
                            <option value="Science de données et digitalisation">Science de données et digitalisation</option>
                            <option value="Génie industriel">Génie industriel</option>
                            <option value="Analyse de politique publique en développement">Analyse de politique publique en développement</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Où avez-vous effectué votre stage PFE ?</span>
                        <select name="stage_pfe_location" required>
                            <option value="Maroc">Maroc</option>
                            <option value="Étranger">Étranger</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Quel était le sujet ou le domaine spécifique de votre Projet de Fin d'Études (PFE) ?</span>
                        <select name="stage_pfe_subject" required>
                            <option value="Industrie">Industrie</option>
                            <option value="Data science">Data science</option>
                            <option value="Supply chain">Supply chain</option>
                            <option value="Énergie">Énergie</option>
                            <option value="Matériaux">Matériaux</option>
                            <option value="Finance">Finance</option>
                            <option value="Intelligence artificielle (AI)">Intelligence artificielle (AI)</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Dans quel domaine d'ingénierie travaillez-vous actuellement ?</span>
                        <select name="domaine" required>
                            <option value="Construction">Construction</option>
                            <option value="Industrie">Industrie</option>
                            <option value="Électronique">Électronique</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Énergie">Énergie</option>
                            <option value="Biomédical">Biomédical</option>
                            <option value="Matériaux">Matériaux</option>
                            <option value="Aérospatial">Aérospatial</option>
                            <option value="Géologie">Géologie</option>
                            <option value="Maritime">Maritime</option>
                            <option value="Finance">Finance</option>
                            <option value="Transport">Transport</option>
                            <option value="Conseil">Conseil</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Collaborez-vous actuellement avec des équipes multidisciplinaires ou internationales ?</span>
                        <input type="radio" name="collaborate" value="Oui" required> Oui
                        <input type="radio" name="collaborate" value="Non" required> Non
                    </div>

                    <div class="input-box">
                        <span class="details">Quels sont vos objectifs professionnels à long terme ?</span>
                        <select name="long_term_goals" required>
                            <option value="Entreprendre et créer ma propre entreprise">Entreprendre et créer ma propre entreprise</option>
                            <option value="Travailler dans une multinationale renommée">Travailler dans une multinationale renommée</option>
                            <option value="Évoluer vers des fonctions de gestion ou de direction">Évoluer vers des fonctions de gestion ou de direction</option>
                            <option value="Développer une expertise spécifique dans mon domaine d'ingénierie">Développer une expertise spécifique dans mon domaine d'ingénierie</option>
                            <option value="Contribuer activement à des projets innovants et durables">Contribuer activement à des projets innovants et durables</option>
                            <option value="Explorer des opportunités dans le domaine de la recherche appliquée">Explorer des opportunités dans le domaine de la recherche appliquée</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Quelles compétences spécifiques souhaitez-vous partager ou sur lesquelles vous pourriez conseiller des étudiants de 1ère année ?</span>
                        <select name="skills_to_share" required>
                            <option value="Compétences techniques avancées">Compétences techniques avancées</option>
                            <option value="Compétences en gestion de projets">Compétences en gestion de projets</option>
                            <option value="Compétences interpersonnelles et de communication">Compétences interpersonnelles et de communication</option>
                            <option value="Expérience pratique en milieu professionnel">Expérience pratique en milieu professionnel</option>
                        </select>
                    </div>

                    
                
                </div>
                <div class="button">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
</body>

</html>

