<?php

// Function to calculate score for matching first-year students with third-year students (mentors)
function calculate_score_first_to_third($first_year, $third_year) {
    $score = 0;


    if ($first_year['major'] == $third_year['major']) {
        $score += 10;
    }

    if ($first_year['filiere'] == $third_year['filiere']) {
        $score += 10;
    }

    if ($first_year['option'] == $third_year['option']) {
        $score += 10;
    }

    if ($first_year['cesure'] == $third_year['cesure']) {
        $score += 5;
    }

    if ($first_year['mobilite'] == $third_year['mobilite']) {
        $score += 5;
    }

    if ($first_year['stage_observation'] == $third_year['stage_observation']) {
        $score += 10;
    }

    if ($first_year['stage_2eme_annee'] == $third_year['stage_2eme_annee']) {
        $score += 10;
    }

    if ($first_year['competence'] == $third_year['competence']) {
        $score += 10;
    }

    if ($first_year['mentorat'] == $third_year['mentorat']) {
        $score += 5;
    }

    if ($first_year['interet'] == $third_year['interet']) {
        $score += 5;
    }

    return $score;
}

function find_matches_first_to_third($conn, $first_year_id) {
    $stmt = $conn->prepare("SELECT * FROM first_year_students WHERE user_id = ?");
    $stmt->bind_param("i", $first_year_id);
    $stmt->execute();
    $first_year = $stmt->get_result()->fetch_assoc();
    $stmt = $conn->prepare("SELECT * FROM third_year_students_mentor");
    $stmt->execute();
    $third_year_students = $stmt->get_result();

    $matches = [];

    while ($third_year = $third_year_students->fetch_assoc()) {
        $score = calculate_score_first_to_third($first_year, $third_year);
        $matches[] = ['third_year_id' => $third_year['user_id'], 'score' => $score];
    }

    usort($matches, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    return $matches;
}

function save_matches_first_to_third($conn, $first_year_id, $matches) {
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO matches_first_to_third (first_year_id, third_year_id, score) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $first_year_id, $match['third_year_id'], $match['score']);
        $stmt->execute();
    }
}

function calculate_score_third_to_laureate($third_year, $laureate) {
    $score = 0;
   
    if ($third_year['filiere'] == $laureate['filiere_3A']) {
        $score += 10;
    }
  

    if ($third_year['option'] == $laureate['option_3A']) {
        $score += 10;
    }

    if ($third_year['stage_PFE'] == $laureate['stage_PFE']) {
        $score += 10;
    }

    if ($third_year['PFE_domaine'] == $laureate['PFE_domaine']) {
        $score += 10;
    }

    if ($third_year['engineering_domaine'] == $laureate['engineering_domaine']) {
        $score += 10;
    }

    if ($third_year['equipe_multidisciplinaire'] == $laureate['equipe_multidisciplinaire']) {
        $score += 5;
    }

    if ($third_year['objectif_professionnel'] == $laureate['objectif_professionnel']) {
        $score += 5;
    }

    if ($third_year['developper_competences'] == $laureate['apporter_competences']) {
        $score += 5;
    }

    return $score;
}

// Function to find matches for third-year students (mentorees) with laureates
function find_matches_third_to_laureate($conn, $third_year_id) {
    $stmt = $conn->prepare("SELECT * FROM third_year_students_mentoree WHERE user_id = ?");
    $stmt->bind_param("i", $third_year_id);
    $stmt->execute();
 

    $third_year = $stmt->get_result()->fetch_assoc();
 
    $stmt = $conn->prepare("SELECT * FROM laureates");
    $stmt->execute();
    $laureates = $stmt->get_result();


    $matches = [];

    while ($laureate = $laureates->fetch_assoc()) {
        $score = calculate_score_third_to_laureate($third_year, $laureate);
        $matches[] = ['laureate_id' => $laureate['user_id'], 'score' => $score];
    }

    usort($matches, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    return $matches;
}

// Function to save matches for third-year students (mentorees) with laureates
function save_matches_third_to_laureate($conn, $third_year_id, $matches) {
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO matches_third_to_laureate (third_year_id, laureate_id, score) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $third_year_id, $match['laureate_id'], $match['score']);
        $stmt->execute();
    }
}

// Additional functions for third-year students (mentors) matching with first-year students
function find_matches_third_to_first($conn, $third_year_id) {
    $stmt = $conn->prepare("SELECT * FROM third_year_students_mentor WHERE user_id = ?");
    $stmt->bind_param("i", $third_year_id);
    $stmt->execute();
    $third_year = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM first_year_students");
    $stmt->execute();
    $first_year_students = $stmt->get_result();

    $matches = [];

    while ($first_year = $first_year_students->fetch_assoc()) {
        $score = calculate_score_first_to_third($first_year, $third_year);
        $matches[] = ['first_year_id' => $first_year['user_id'], 'score' => $score];
    }

    usort($matches, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    return $matches;
}

function save_matches_third_to_first($conn, $third_year_id, $matches) {
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO matches_third_to_first (third_year_id, first_year_id, score) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $third_year_id, $match['first_year_id'], $match['score']);
        $stmt->execute();
    }
}

// Additional functions for laureates matching with third-year students (mentorees)
function find_matches_laureate_to_third($conn, $laureate_id) {
    $stmt = $conn->prepare("SELECT * FROM laureates WHERE user_id = ?");
    $stmt->bind_param("i", $laureate_id);
    $stmt->execute();
    $laureate = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM third_year_students_mentoree");
    $stmt->execute();
    $third_year_students = $stmt->get_result();

    $matches = [];

    while ($third_year = $third_year_students->fetch_assoc()) {
        $score = calculate_score_third_to_laureate($third_year, $laureate);
        $matches[] = ['third_year_id' => $third_year['user_id'], 'score' => $score];
    }

    usort($matches, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    return $matches;
}

function save_matches_laureate_to_third($conn, $laureate_id, $matches) {
    foreach ($matches as $match) {
        $stmt = $conn->prepare("INSERT INTO matches_laureate_to_third (laureate_id, third_year_id, score) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $laureate_id, $match['third_year_id'], $match['score']);
        $stmt->execute();
    }
}

?>
