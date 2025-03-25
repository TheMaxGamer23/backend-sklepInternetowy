<?php
    header("Content-Type: application/json");
    
    $conn = mysqli_connect("localhost", "root", "", "sklep");

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data["search"])) {
        echo json_encode(["error" => "Missing token"]);
        exit;
    }
    
    class Koszyk {
        public $nazwa;
        public $cena;
        public $id;
        public $ilosc;
        public $zdjecie;
        public $deskrypcja;
        public $ocena;

        function __construct($nazwa, $cena, $id, $ilosc, $zdjecie, $deskrypcja, $ocena) {
            $this->nazwa = $nazwa;
            $this->cena = $cena;
            $this->id = $id;
            $this->ilosc = $ilosc;
            $this->zdjecie = $zdjecie;
            $this->deskrypcja = $deskrypcja;
            $this->ocena = $ocena;
        }
    }
    
    $moj_koszyk = [];
    $search = $data["search"];
    $kwerenda = "SELECT * FROM produkty WHERE nazwa LIKE '%$search%'";
    $wynik = mysqli_query($conn, $kwerenda);
    
    while ($row = mysqli_fetch_row($wynik)) {
        $kweroc = "SELECT AVG(ocena) FROM komentarze WHERE produkt_id = '$row[0]'"; 
        $wynoc = mysqli_query($conn, $kweroc);
        if ($wynoc) {
            while ($ocena = mysqli_fetch_row($wynoc)) {
                array_push($moj_koszyk, new Koszyk($row[1], $row[2], $row[0], $row[3], $row[4], $row[5], $ocena[0]));
            }
        }
        else {
            while ($ocena = mysqli_fetch_row($wynoc)) {
                array_push($moj_koszyk, new Koszyk($row[1], $row[2], $row[0], $row[3], $row[4], $row[5], null));
            }
        }
    }

    echo json_encode($moj_koszyk);
    mysqli_close($conn);
?>