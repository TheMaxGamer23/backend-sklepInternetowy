<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["token"])) {
        echo json_encode(["error" => "Missing token"]);
        exit;
    }

    $token = $data["token"];
    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $kod_zamowienia = "#";
    for ($i = 0; $i < 6; $i++) {
        $znak = strval(rand(0, 9));
        $kod_zamowienia = $kod_zamowienia.$znak;
    }

    $kwerenda = "SELECT koszyk.produkt_id, koszyk.ilosc, produkty.cena FROM koszyk JOIN produkty ON koszyk.produkt_id = produkty.id JOIN uzytkownicy ON koszyk.uzytkownik_id = uzytkownicy.id WHERE koszyk.uzytkownik_id = '$uzytkownik_id'";
    $wynik = mysqli_query($conn, $kwerenda);
    while ($row = mysqli_fetch_row($wynik)) {
        if ($row[1] == 0) {
            continue;
        }
        $query = "INSERT INTO zamowienia(produkt_id, kod_zamowienia, ilosc, cena, uzytkownik_id) VALUES('$row[0]', '$kod_zamowienia', '$row[1]', '$row[2]', '$uzytkownik_id')";
        $stmt = mysqli_query($conn, $query);
        $kwerilo = "UPDATE produkty SET ilosc = ilosc - '$row[1]' WHERE id = '$row[0]'";
        $wynilo = mysqli_query($conn, $kwerilo);
    }
    $kwerusun = "DELETE FROM koszyk WHERE uzytkownik_id = '$uzytkownik_id'";
    $wynusun = mysqli_query($conn, $kwerusun);

    $response = ["success" => true, "message" => "Order placed"];
    echo json_encode($response);

    $conn->close();
?>