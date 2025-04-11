<?php
    require_once("../token.php");

    header("Content-Type: application/json"); 

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["firstName"], $data["lastName"], $data["password"], $data["email"], $data["confirmPassword"], $data["isAccept"])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    $imie = $data["firstName"];
    $nazwisko = $data["lastName"];
    $haslo = $data["password"];
    $email = $data["email"];
    $powhaslo = $data["confirmPassword"];
    $is_accept = $data["isAccept"];


    $query = "INSERT INTO uzytkownicy (haslo, email, imie, nazwisko, token, czy_admin) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $haslo, $email, $imie, $nazwisko, $token, false);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User signed up successfully", "token" => $token]);
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
?>