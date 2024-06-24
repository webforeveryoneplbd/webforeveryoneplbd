<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'first_year') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $major = $_POST['major'];
    $filiere = $_POST['filiere'];
    $option = $_POST['option'];
    $cesure = $_POST['cesure'];
    $cesure_type = $_POST['cesure_type'];
    $cesure_domaine = $_POST['cesure_domaine'];
    $mobilite = $_POST['mobilite'];
    $mobilite_option = $_POST['mobilite_option'];
    $stage_observation = $_POST['stage_observation'];
    $stage_2eme_annee = $_POST['stage_2eme_annee'];
    $competence = $_POST['competence'];
    $mentorat = $_POST['mentorat'];
    $interet = $_POST['interet'];

    $stmt = $conn->prepare("INSERT INTO first_year_students (user_id, major, filiere, option, cesure, cesure_type, cesure_domaine, mobilite, mobilite_option, stage_observation, stage_2eme_annee, competence, mentorat, interet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssss", $user_id, $major, $filiere, $option, $cesure, $cesure_type, $cesure_domaine, $mobilite, $mobilite_option, $stage_observation, $stage_2eme_annee, $competence, $mentorat, $interet);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
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
        margin: 35px 0
    }

    form .button input {
        height: 100%;
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

    form .button input:hover {

        /* transform: scale(0.99); */
        background: linear-gradient(-135deg, #76b7e6, #9b59b6);
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
        <div class="title">Take a moment to fill this survey</div>
        <div class="content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Quelle majeure envisagez-vous le plus d'intégrer en 2ème année ?</span>
                        <select name="major" required>
                            <option value="Modélisation et industrie" >Modélisation et industrie</option>
                            <option value="Modélisation et Énergie">Modélisation et Énergie</option>
                            <option value="Modélisation et Aide à la décision">Modélisation et Aide à la décision
                            </option>
                            <option value="Modélisation et Matériaux">Modélisation et Matériaux</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Quelle filière envisagez-vous le plus de choisir au cours de votre 2ème
                            année ?</span>
                        <select name="filiere" required>
                            <option value="Management">Management</option>
                            <option value="Recherche">Recherche</option>
                            <option value="Conception et innovation">Conception et innovation</option>
                            <option value="Entrepreneuriat">Entrepreneuriat</option>
                        </select>
                    </div>
                    
                    <div class="input-box">
                        <span class="details">Quelle option envisagez-vous de choisir au cours de votre 2ème année
                            ?</span>
                        <select name="option" required>
                            <option value="Énergie et développement durable">Énergie et développement durable</option>
                            <option value="Science de données et digitalisation">Science de données et digitalisation
                            </option>
                            <option value="Génie industriel">Génie industriel</option>
                            <option value="Analyse de politique publique en développement">Analyse de politique publique
                                en développement</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Considérez-vous faire une année de césure à l'avenir ?</span>
                        <input type="radio" name="cesure" value="Oui" required> Oui
                        <input type="radio" name="cesure" value="Non" required> Non
                    </div>
                    <div class="input-box">
                        <span class="details">Si oui, quel type de césure envisagez-vous ?</span>
                        <select name="cesure_type">
                            <option value="Académique">Académique</option>
                            <option value="Professionnelle">Professionnelle</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Et dans quel domaine envisagez-vous le plus de faire votre césure ?</span>
                        <select name="cesure_domaine">
                            <option value="Génie industriel">Génie industriel</option>
                            <option value="Data science">Data science</option>
                            <option value="Cybersécurité">Cybersécurité</option>
                            <option value="Énergie">Énergie</option>
                            <option value="Finance">Finance</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Considérez-vous faire une mobilité à l'avenir ?</span>
                        <input type="radio" name="mobilite" value="Oui" required> Oui
                        <input type="radio" name="mobilite" value="Non" required> Non
                    </div>
                    <div class="input-box">
                        <span class="details">Si oui, en quelle option envisagez-vous le plus ?</span>
                        <select name="mobilite_option">
                            <option value="Industrie">Industrie</option>
                            <option value="Systèmes et environnements intelligents">Systèmes et environnements
                                intelligents</option>
                            <option value="Ingénierie et santé">Ingénierie et santé</option>
                            <option value="Énergie">Énergie</option>
                            <option value="Aéronautique">Aéronautique</option>
                            <option value="Génie mer">Génie mer</option>
                            <option value="Génie civil et construction durable">Génie civil et construction durable
                            </option>
                            <option value="Mathématique et Data science">Mathématique et Data science</option>
                            <option value="Informatique et numérique">Informatique et numérique</option>
                            <option value="Physique et nanotechnologie">Physique et nanotechnologie</option>
                            <option value="Grands systèmes en interaction">Grands systèmes en interaction</option>
                            <option value="Construction">Construction</option>
                            <option value="Transport">Transport</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Quel domaine envisagez-vous pour votre stage d'observation ?</span>
                        <select name="stage_observation" required>
                            <option value="Industrie">Industrie</option>
                            <option value="Développement web">Développement web</option>
                            <option value="Data Science">Data Science</option>
                            <option value="Finance/banque">Finance/banque</option>
                            <option value="Énergie">Énergie</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Quel domaine envisagez-vous le plus pour votre stage de 2ème année
                            ?</span>
                        <select name="stage_2eme_annee" required>
                            <option value="Industrie">Industrie</option>
                            <option value="Développement web">Développement web</option>
                            <option value="Data science">Data science</option>
                            <option value="Finance/Banque">Finance/Banque</option>
                            <option value="Énergie">Énergie</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Avez-vous des compétences particulières que vous souhaiteriez développer
                            ou apprendre au cours de votre formation ?</span>
                        <select name="competence" required>
                            <option value="Programmation">Programmation</option>
                            <option value="Langues étrangères">Langues étrangères</option>
                            <option value="Leadership et gestion de projet">Leadership et gestion de projet</option>
                            <option value="Design et arts créatifs">Design et arts créatifs</option>
                            <option value="Communication">Communication</option>
                            <option value="Gestion de stress">Gestion de stress</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Y a-t-il des domaines ou compétences spécifiques pour lesquels vous
                            aimeriez recevoir des conseils ou du mentorat de la part des élèves de 3ème année ?</span>
                        <select name="mentorat" required>
                            <option value="Orientation académique">Orientation académique</option>
                            <option value="Stages et carrières">Stages et carrières</option>
                            <option value="Gestion du temps et des études">Gestion du temps et des études</option>
                            <option value="Vie étudiante et intégration">Vie étudiante et intégration</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="details">Quels sont vos centres d'intérêt ou passions en dehors des études ?
                            (choisir une)</span>
                        <select name="interet" required>
                            <option value="Sport">Sport</option>
                            <option value="Musique">Musique</option>
                            <option value="Lecture">Lecture</option>
                            <option value="Voyage">Voyage</option>
                            <option value="Bénévolat">Bénévolat</option>
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