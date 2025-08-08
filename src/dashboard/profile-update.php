<?php

session_start();
// Get the form data
$username = $_SESSION['username'];
$description = $_POST["description"];
$hobbies = $_POST["hobbies"];
$favoriteSports = $_POST["favoriteSports"];
$favoriteGames = $_POST["favoriteGames"];
$favoriteMovies = $_POST["favoriteMovies"];
$favoriteBands = $_POST["favoriteBands"];
$whomIRespect = $_POST["whomIRespect"];
$isolated = $_POST["isolate"] ?? null;

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
