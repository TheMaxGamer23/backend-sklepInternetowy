<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    $kwerenda = "SELECT kod_zamowienia FROM zamowienia ORDER BY id DESC LIMIT 1";
    $wynik = mysqli_query($conn, $kwerenda);
    $kod_zamowienia = "";

    while ($row = mysqli_fetch_row($wynik)) {
        $kod_zamowienia .= $row[0];
    }
    
    if ($wynik) {
        echo json_encode($kod_zamowienia);
    } else {
        echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
    }

    $conn->close();
?>