<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    $data = json_decode(file_get_contents('php://input'), true);

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    if (!isset($data["token"])) {
        echo json_encode(["error" => "Missing token"]);
        exit;
    }

    class Koszyk {
        public $nazwa;
        public $cena;
        public $id;
        public $produkt_id;
        public $ilosc;
        public $zdjecie;

        function __construct($nazwa, $cena, $id, $produkt_id, $ilosc, $zdjecie) {
            $this->nazwa = $nazwa;
            $this->cena = $cena;
            $this->id = $id;
            $this->produkt_id = $produkt_id;
            $this->ilosc = $ilosc;
            $this->zdjecie = $zdjecie;
        }
    }

    $token = $data["token"];
    $kwrenda = "SELECT id FROM uzytkownicy WHERE token = '$token'";
    $wynk = mysqli_query($conn, $kwrenda);
    $uzytkownik_id = 0;
    while ($row = mysqli_fetch_row($wynk)) {
        $uzytkownik_id = $row[0];
    }

    $kwerenda1 = "SELECT koszyk.id, koszyk.produkt_id, koszyk.ilosc, produkty.nazwa, produkty.cena, produkty.zdjecie FROM koszyk JOIN produkty ON koszyk.produkt_id = produkty.id WHERE koszyk.uzytkownik_id = '$uzytkownik_id'";
    $wynik1 = mysqli_query($conn, $kwerenda1);
    $moj_koszyk = [];

    while ($row = mysqli_fetch_row($wynik1)) {
        array_push($moj_koszyk, new Koszyk($row[3], $row[4], $row[0], $row[1], $row[2], $row[5]));
    }

    echo json_encode($moj_koszyk);

    mysqli_close($conn);
?>