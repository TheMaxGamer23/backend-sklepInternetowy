<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["id"])) {
        echo json_encode(["error" => "Invalid JSON or missing productId"]);
        exit;
    }

    $id = intval($data["id"]);
    $token = $data["token"];
    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $query = "DELETE FROM koszyk WHERE id = ? AND uzytkownik_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id, $uzytkownik_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Produkt usunięty z koszyka"]);
    } else {
        echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
?>