<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    $conn = mysqli_connect("localhost", "root", "", "sklep");

    class Koszyk {
        public $nazwa;
        public $cena;
        public $id;
        public $ilosc;
        public $zdjecie;
        public $kategoria;
        public $ocena;

        function __construct($nazwa, $cena, $id, $ilosc, $zdjecie, $kategoria, $ocena) {
            $this->nazwa = $nazwa;
            $this->cena = $cena;
            $this->id = $id;
            $this->ilosc = $ilosc;
            $this->zdjecie = $zdjecie;
            $this->kategoria = $kategoria;
            $this->ocena = $ocena;
        }
    }

    $kwerenda1 = "SELECT * FROM produkty";
    $wynik1 = mysqli_query($conn, $kwerenda1);
    $moj_koszyk = [];

    while ($row = mysqli_fetch_row($wynik1)) {
        $kweroc = "SELECT AVG(ocena) FROM komentarze WHERE produkt_id = '$row[0]'"; 
        $wynoc = mysqli_query($conn, $kweroc);

        if ($wynoc) {
            while ($ocena = mysqli_fetch_row($wynoc)) {
                array_push($moj_koszyk, new Koszyk($row[1], $row[2], $row[0], $row[3], $row[4], $row[5], $ocena[0]));
            }
        } 

        if (!$wynoc) {
            while ($ocena = mysqli_fetch_row($wynoc)) {
                array_push($moj_koszyk, new Koszyk($row[1], $row[2], $row[0], $row[3], $row[4], $row[5], null));
            }
        }
    }

    echo json_encode($moj_koszyk);

    mysqli_close($conn);
?>