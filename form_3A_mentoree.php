<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'third_year') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gather form data
    $filiere = $_POST['filiere'];
    $option = $_POST['option'];
    $stage_pfe_location = $_POST['stage_pfe_location'];
    $stage_pfe_subject = $_POST['stage_pfe_subject'];
    $domaine = $_POST['domaine'];
    $collaborate = $_POST['collaborate'];
    $long_term_goals = $_POST['long_term_goals'];
    $skills_to_develop = $_POST['skills_to_develop'];

    // Insert form data into the database
    $stmt = $conn->prepare("INSERT INTO third_year_students_mentoree (user_id, filiere, option, stage_PFE, PFE_domaine, engineering_domaine, equipe_multidisciplinaire, objectif_professionnel, developper_competences) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $_SESSION['user_id'], $filiere, $option, $stage_pfe_location, $stage_pfe_subject, $domaine, $collaborate, $long_term_goals, $skills_to_develop);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Form 3A Mentoree</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background: linear-gradient(-135deg, #14557a, #7acac3);
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
            background: linear-gradient(-135deg, #14557a, #7acac3);
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

        .user-details .input-box input:not([type="radio"]),
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

        .user-details .input-box input:focus,
        .user-details .input-box input:valid,
        .user-details .input-box select:focus,
        .user-details .input-box select:valid,
        .user-details .input-box textarea:focus,
        .user-details .input-box textarea:valid {
            border-color: #9b59b6;
        }

        form .button {
            height: 45px;
            margin: 35px 0;
        }

        form .button button {
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
            background: linear-gradient(-135deg, #14557a, #7acac3);
        }

        form .button button:hover {
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

        form input[type="radio"] {
            display: inline-block;
            width: 25px;
            height: 25px;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">Form 3A Mentoree</div>
        <div class="content">
            <form method="post" action="">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Quelle est votre filière (3ème année) ?</span>
                        <select name="filiere" required>
                            <option value="Entrepreneuriat">Entrepreneuriat</option>
                            <option value="Conception et innovation">Conception et innovation</option>
                            <option value="Recherche">Recherche</option>
                            <option value="Management">Management</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Quelle est votre option (3ème année) ?</span>
                        <select name="option" required>
                            <option value="Énergie et développement durable">Énergie et développement durable</option>
                            <option value="Science de données et digitalisation">Science de données et digitalisation</option>
                            <option value="Génie industriel">Génie industriel</option>
                            <option value="Analyse de politique publique en développement">Analyse de politique publique en développement</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Où allez-vous effectuer votre stage PFE ?</span>
                        <select name="stage_pfe_location" required>
                            <option value="Maroc">Maroc</option>
                            <option value="Étranger">Étranger</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <span class="details">Quel est le sujet ou le domaine spécifique de votre Projet de Fin d'Études (PFE) ?</span>
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
                        <span class="details">Dans quel domaine d'ingénierie souhaitez-vous être engagé ?</span>
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
                        <span class="details">Voulez-vous collaborer avec des équipes multidisciplinaires ou internationales ?</span>
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
                        <span class="details">Quelles compétences spécifiques voulez-vous développer le plus ?</span>
                        <select name="skills_to_develop" required>
                            <option value="Compétences techniques avancées">Compétences techniques avancées</option>
                            <option value="Compétences en gestion de projets">Compétences en gestion de projets</option>
                            <option value="Compétences interpersonnelles et de communication">Compétences interpersonnelles et de communication</option>
                            <option value="Expérience pratique en milieu professionnel">Expérience pratique en milieu professionnel</option>
                        </select>
                    </div>
                </div>

                <div class="button">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

