<?php
    header("Content-Type: application/json");

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    $token = $data["token"];
    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $kwerenda = "SELECT czy_admin FROM uzytkownicy WHERE id = '$uzytkownik_id'";
    $wynik = mysqli_query($conn, $kwerenda);
    while ($row = mysqli_fetch_row($wynik)) {
        if ($row[0] == 1) {
            echo json_encode(["success" => "Uzytkownik jest adminem"]);
        }
        else {
            echo json_encode(["error" => "Uzytkownik nie jest adminem"]);
            exit;
        }
    }

    mysqli_close($conn);
?>