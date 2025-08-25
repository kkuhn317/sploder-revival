<?php

include('../content/logincheck.php');
require_once('../content/censor.php');
// Get the form data
$username = $_SESSION['username'];
$description = censorText($_POST["description"]);
$hobbies = censorText($_POST["hobbies"]);
$favoriteSports = censorText($_POST["favoriteSports"]);
$favoriteGames = censorText($_POST["favoriteGames"]);
$favoriteMovies = censorText($_POST["favoriteMovies"]);
$favoriteBands = censorText($_POST["favoriteBands"]);
$whomIRespect = censorText($_POST["whomIRespect"]);
$isolated = censorText($_POST["isolate"]) ?? null;

$description = mb_substr($description, 0, 500);
$hobbies = mb_substr($hobbies, 0, 500);
$favoriteSports = mb_substr($favoriteSports, 0, 500);
$favoriteGames = mb_substr($favoriteGames, 0, 500);
$favoriteMovies = mb_substr($favoriteMovies, 0, 500);
$favoriteBands = mb_substr($favoriteBands, 0, 500);
$whomIRespect = mb_substr($whomIRespect, 0, 500);

# $isolated is set to "on" if the checkbox is checked
if ($isolated === "on") {
    $isolated = false;
} else {
    $isolated = true;
}

// Connect to the PostgreSQL database
include('../database/connect.php');
require_once('../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$db = getDatabase();

# Check if the user was already isolated
$oldIsolated = $userRepository->isIsolated($username);

if ($isolated !== $oldIsolated) {
    // Update the user's isolation status
    $userRepository->setIsolation($username, $isolated);
}

// Prepare the SQL statement
//Insert the form data into the user_info table if already exists update the data
$sql = "INSERT INTO user_info (username, description, hobbies, sports, games, movies, bands, respect) 
        VALUES (:username, :description, :hobbies, :sports, :games, :movies, :bands, :respect)
        ON CONFLICT (username) DO UPDATE SET description = :description, hobbies = :hobbies, sports = :sports, games = :games, movies = :movies, bands = :bands, respect = :respect";
$db->execute($sql, [
  ':username' => $username,
  ':description' => $description,
  ':hobbies' => $hobbies,
  ':sports' => $favoriteSports,
  ':games' => $favoriteGames,
  ':movies' => $favoriteMovies,
  ':bands' => $favoriteBands,
  ':respect' => $whomIRespect
]);
header('Location: profile-edit.php');
