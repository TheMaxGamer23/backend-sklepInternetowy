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

    $id = $data["productId"];
    $nazwa = $data["productName"];
    $cena = $data["price"];
    $ilosc = $data["quantity"];
    $kategoria = $data["category"];
    $deskrypcja = $data["description"];

    $kwernazw = "UPDATE produkty SET nazwa = '$nazwa' WHERE id = '$id'";
    $wynnazw = mysqli_query($conn, $kwernazw);

    $kwercena = "UPDATE produkty SET cena = '$cena' WHERE id = '$id'";
    $wyncena = mysqli_query($conn, $kwercena);

    $kwerilo = "UPDATE produkty SET ilosc = '$ilosc' WHERE id = '$id'";
    $wynilo = mysqli_query($conn, $kwerilo);

    $kwerkate = "UPDATE produkty SET kategoria = '$kategoria' WHERE id = '$id'";
    $wynkate = mysqli_query($conn, $kwerkate);
    
    $kwerdesk = "UPDATE produkty SET deskrypcja = '$deskrypcja' WHERE id = '$id'";
    $wyndesk = mysqli_query($conn, $kwerdesk);

    if ($wynnazw && $wyncena && $wynilo && $wynkate && $wyndesk) {
        echo json_encode(["success" => "Poprawnie zmieniono dane"]);
    } else {
        echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
    }

    mysqli_close($conn);
?>