<?php
    header("Content-Type: application/json");

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    $productId = $data["productId"];

    class Komentarz {
        public $id;
        public $produkt_id;
        public $uzytkownik_id;
        public $ocena;
        public $komentarz;
        public $data;
        public $imie;
        public $nazwisko;

        function __construct($id, $produkt_id, $uzytkownik_id, $ocena, $komentarz, $data, $imie, $nazwisko) {
            $this->id = $id;
            $this->produkt_id = $produkt_id;
            $this->uzytkownik_id = $uzytkownik_id;
            $this->ocena = $ocena;
            $this->komentarz = $komentarz;
            $this->data = $data;
            $this->imie = $imie;
            $this->nazwisko = $nazwisko;
        }
    }

    $kwerenda = "SELECT komentarze.*, uzytkownicy.imie, uzytkownicy.nazwisko FROM komentarze JOIN uzytkownicy ON komentarze.uzytkownik_id = uzytkownicy.id WHERE komentarze.produkt_id = '$productId'";
    $wynik = mysqli_query($conn, $kwerenda);
    $komentarze = [];
    while ($row = mysqli_fetch_row($wynik)) {
        array_push($komentarze, new Komentarz($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]));
    }

    echo json_encode($komentarze);

    mysqli_close($conn);
?>