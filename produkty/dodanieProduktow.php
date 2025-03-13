<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["productId"]) && !isset($data["token"])) {
        echo json_encode(["error" => "Invalid JSON or missing productId"]);
        exit;
    }

    $productId = intval($data["productId"]); 
    $ilosc = 1;
    $isExist = false;
    $token = $data["token"];

    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $kwerenda = "SELECT produkt_id FROM koszyk WHERE uzytkownik_id = '$uzytkownik_id'";
    $wynik = mysqli_query($conn, $kwerenda);
    while ($row = mysqli_fetch_row($wynik)) {
        if ($row[0] === strval($productId)) {
            $kwereda2 = "UPDATE koszyk SET ilosc = ilosc + 1 WHERE produkt_id = '$row[0]' AND uzytkownik_id = '$uzytkownik_id';";
            $wynik2 = mysqli_query($conn, $kwereda2);

            if ($wynik2) {
                echo json_encode(["success" => "Produkt dodany do koszyka"]);
            } else {
                echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
            }

            $isExist = true;
        }
    }

    if (!$isExist) {
        $query = "INSERT INTO koszyk (produkt_id, ilosc, uzytkownik_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $productId, $ilosc, $uzytkownik_id);
        

        if ($stmt->execute()) {
            echo json_encode(["success" => "Produkt dodany do koszyka"]);
        } else {
            echo json_encode(["error" => "Błąd zapisu do bazy: " . $conn->error]);
        }

        $stmt->close();
    }

    $conn->close();
?>