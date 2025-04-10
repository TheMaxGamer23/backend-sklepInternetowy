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

    $kwerad = "SELECT czy_admin FROM uzytkownicy WHERE id = '$uzytkownik_id'";
    $wynad = mysqli_query($conn, $kwerad);
    while ($row = mysqli_fetch_row($wynad)) {
        if ($row[0] != 1) {
            echo json_encode(["error" => "Account not admin"]);
            exit;
        }
    }

    $komentarz_id = $data["commentId"];

    $kwerenda = "DELETE FROM komentarze WHERE id = '$komentarz_id'";
    $wynik = mysqli_query($conn, $kwerenda);

    if ($wynik) {
        echo json_encode(["success" => "Komentarz usuniety"]);
    } else {
        echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
    }

    mysqli_close($conn);
?>