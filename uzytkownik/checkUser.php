<?php
    header("Content-Type: application/json");

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["token"])) {
        exit;
    }

    $token = $data["token"];

    $query = "SELECT imie, nazwisko FROM uzytkownicy WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(["error" => "Invalid user"]);
        exit;
    }

    echo json_encode(["success" => true, "message" => "User signed in successfully", "data" => $user]);

    $stmt?->close();
    $conn?->close();
?>