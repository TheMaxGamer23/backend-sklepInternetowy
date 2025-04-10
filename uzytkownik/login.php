<?php
    require_once("../token.php");

    header("Content-Type: application/json");

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["email"], $data["password"])) {
        echo json_encode(["error" => "Missing email or password"]);
        exit;
    }

    $email = $data["email"];
    $password = $data["password"];

    $query = "SELECT imie, nazwisko, haslo FROM uzytkownicy WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user && password_verify($password, $user["haslo"])) {
        echo json_encode(["error" => "Invalid email or password"]);
        exit;
    }

    $query = "UPDATE uzytkownicy SET token = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "User signed in successfully", "token" => $token]);

    $stmt?->close();
    $conn?->close();
?>