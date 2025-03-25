<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    $ocena = $data["ocena"];
    $komentarz = $data["komentarz"];
    $date = $data["date"];
    $token = $data["token"];
    $produkt_id = $data["productId"];

    if (!isset($data["productId"]) || !isset($data["token"]) || !isset($data["ocena"]) || !isset($data["komentarz"]) || !isset($data["date"])) {
        echo json_encode(["error" => "Invalid JSON or missing variables"]);
        exit;
    }

    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $kwerenda = "INSERT INTO komentarze(produkt_id, uzytkownik_id, ocena, komentarz, data) VALUES ('$produkt_id', '$uzytkownik_id', '$ocena', '$komentarz', '$date' );";
    $wynik = mysqli_query($conn, $kwerenda);

    if ($wynik) {
        echo json_encode(["success" => "Komentarz dodany"]);
    } else {
        echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
    }

    mysqli_close($conn);
?>