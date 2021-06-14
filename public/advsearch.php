<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        <?php include "css/style.css" ?>
    </style>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;400&family=Salsa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Advanced Search</title>

</head>

<body>
    <?php

    use BorderCloud\SPARQL\SparqlClient;

    require_once('../vendor/autoload.php');
    ?>
    <div>

        <nav class="navbar ">
            <div class="container-fluid d-flex">
                <a href="index.html" class="navbar-brand">TI MatKul Repository</a>
                <div class="">
                    <li class="nav-item">
                        <a class="nav-link" href="advsearch.php">Advanced Search</a>
                    </li>
                    </form>
                </div>
            </div>
        </nav>


    </div>

    <div class="content-result">
        <div class="advance">
            <form action="advsearch.php" method="POST">

                <div class="">
                    <div class="">
                        <div class="">
                            <label>Nama Dosen :</label>
                            <input type="text" name="dosen" id="dosen" placeholder="Nama Dosen..." />
                        </div>
                    </div>

                    <div class="">
                        <div class="">
                            <label>Pilih Semester :</label>
                            <select name="semester" id="semester">
                                <option value="">Semester...</option>
                                <?php

                                $fuseki_server = "http://31.220.62.156:3030"; // fuseki server address 
                                $fuseki_sparql_db = "matkul"; // fuseki Sparql database 
                                $endpoint = $fuseki_server . "/" . $fuseki_sparql_db . "/query";
                                $sc = new SparqlClient();
                                $sc->setEndpointRead($endpoint);
                                $q = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
                                            PREFIX owl: <http://www.w3.org/2002/07/owl#>
                                            PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                                            PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
                                            PREFIX : <http://www.semanticweb.org/sarah/ontologies/2021/4/Matkul#>
                                            
                                            SELECT DISTINCT ?Semester
                                            WHERE { ?Dosen rdf:type :Dosen . 
                                            ?Dosen :Nama_Dosen ?Nama_Dosen.
                                            ?Dosen :Biodata_Dosen ?Biodata_Dosen.
                                            ?Dosen :Mengajar ?Matkul.
                                            ?Matkul rdf:type :Matkul .
                                                OPTIONAL {?Matkul :Semester ?Semester . }}ORDER BY ASC(?Semester)
                                        ";

                                $rows = $sc->query($q, 'rows');
                                $err = $sc->getErrors();
                                if ($err) {
                                    print_r($err);
                                    throw new Exception(print_r($err, true));
                                }
                                foreach ($rows["result"]["rows"] as $row) {
                                    $semesters = $row["Semester"];

                                    echo "
                                        <option value='" . $semesters . "'>$semesters</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="">
                            <label>Pilih SKS :</label>
                            <select name="sks" id="sks">
                                <option value="">SKS...</option>
                                <option value="2">2 SKS</option>
                                <option value="3">3 SKS</option>
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="">
                            <label>Tampilkan semua matkul :</label>
                            <input type="checkbox" id="check" name="check" value="check">
                        </div>
                    </div>
                </div>
                <div class="inner-form">
                    <button class="btn-search" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class='result'>




            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


            <!--Backend-->
            <?php



            //Error Handling
            $matkul = false;
            $dosen = false;
            $kode = false;
            $semester = false;
            $sks = false;
            $biodata = false;
            $deskripsi = false;
            $check = false;

            if (isset($_POST['dosen']))
                $dosen = $_POST['dosen'];

            if (isset($_POST['semester']))
                $semester = $_POST['semester'];

            if (isset($_POST['sks']))
                $sks = $_POST['sks'];

            if (isset($_POST['check']))
                $check = $_POST['check'];

            if (!$dosen && !$semester && !$sks && !$check) {
                echo "<div><h2>Masukkan Pencarian!</h2></div>";
            } elseif (!$check) {
                $fuseki_server = "http://31.220.62.156:3030"; // fuseki server address 
                $fuseki_sparql_db = "matkul"; // fuseki Sparql database 
                $endpoint = $fuseki_server . "/" . $fuseki_sparql_db . "/query";
                $sc = new SparqlClient();
                $sc->setEndpointRead($endpoint);
                $q2 = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
                PREFIX owl: <http://www.w3.org/2002/07/owl#>
                PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
                PREFIX : <http://www.semanticweb.org/sarah/ontologies/2021/4/Matkul#>
                
                SELECT ?Kode_Matkul ?Nama_Matkul ?Nama_Dosen ?Biodata_Dosen ?SKS ?Semester ?Deskripsi
                WHERE { ?Dosen rdf:type :Dosen . 
                ?Dosen :Nama_Dosen ?Nama_Dosen.
                ?Dosen :Biodata_Dosen ?Biodata_Dosen.
                ?Dosen :Mengajar ?Matkul.
                ?Matkul rdf:type :Matkul .
                    OPTIONAL {?Matkul :Kode_Matkul ?Kode_Matkul . }
                    OPTIONAL {?Matkul :Nama_Matkul ?Nama_Matkul . }
                    OPTIONAL {?Matkul :Nama_Dosen ?Nama_Dosen . }
                    OPTIONAL {?Matkul :Nama_Dosen ?Biodata_Dosen . }
                    OPTIONAL {?Matkul :SKS ?SKS . }
                    OPTIONAL {?Matkul :Semester ?Semester . }
                    OPTIONAL {?Matkul :Deskripsi ?Deskripsi .}
                FILTER (
                  regex(?Nama_Dosen, '$dosen', 'i') &&
                  regex(?SKS, '$sks', 'i') &&
                  regex(?Semester, '$semester', 'i')) }
                ";
                // proses ke query 
                $rows = $sc->query($q2, 'rows');
                $err = $sc->getErrors();
                if ($err) {
                    print_r($err);
                    throw new Exception(print_r($err, true));
                }

                if ($dosen == "") {
                    $dosen = "-";
                }
                if ($semester == "") {
                    $semester = "-";
                }
                if ($sks == "") {
                    $sks = "-";
                }

                $count = count($rows);
                echo "<div> Hasil Pencarian Dosen : <strong>$dosen</strong> / Semester : <strong>$semester</strong> / SKS : <strong>$sks</strong> </div>";

                if (empty($rows["result"]["rows"])) {
                    echo "<div><h2>Hasil tidak ditemukan</h2></div>";
                }

                foreach ($rows["result"]["rows"] as $row) {
                    $matkul = $row["Nama_Matkul"];
                    $dosen = $row["Nama_Dosen"];
                    $semester = $row["Semester"];
                    $sks = $row["SKS"];
                    $kode = $row["Kode_Matkul"];
                    $biodata = $row["Biodata_Dosen"];
                    $deskripsi = $row["Deskripsi"];


                    echo "
                <div class='card-result'>
                    Mata Kuliah : <strong>$matkul</strong> <br>
                    <button class='collapsible'>Deskripsi Mata Kuliah</button>
                    <div class='deskripsi'>
                    <p>$deskripsi</p>
                    </div>
                    Nama Dosen : $dosen<br>
                    Semester : $semester<br>
                    SKS : $sks<br>
                    Kode : $kode<br>
                    Biodata Dosen :  <a href='" . $biodata . "'>$biodata</a><br>
                </div>";
                }
            } else {

                $fuseki_server = "http://31.220.62.156:3030"; // fuseki server address 
                $fuseki_sparql_db = "matkul"; // fuseki Sparql database 
                $endpoint = $fuseki_server . "/" . $fuseki_sparql_db . "/query";
                $sc = new SparqlClient();
                $sc->setEndpointRead($endpoint);
                $q3 = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX owl: <http://www.w3.org/2002/07/owl#>
            PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
            PREFIX : <http://www.semanticweb.org/sarah/ontologies/2021/4/Matkul#>
            
            SELECT ?Kode_Matkul ?Nama_Matkul ?Nama_Dosen ?Biodata_Dosen ?SKS ?Semester ?Deskripsi
            WHERE { ?Dosen rdf:type :Dosen . 
            ?Dosen :Nama_Dosen ?Nama_Dosen.
            ?Dosen :Biodata_Dosen ?Biodata_Dosen.
            ?Dosen :Mengajar ?Matkul.
            ?Matkul rdf:type :Matkul .
                OPTIONAL {?Matkul :Kode_Matkul ?Kode_Matkul . }
                OPTIONAL {?Matkul :Nama_Matkul ?Nama_Matkul . }
                OPTIONAL {?Matkul :Nama_Dosen ?Nama_Dosen . }
                OPTIONAL {?Matkul :Nama_Dosen ?Biodata_Dosen . }
                OPTIONAL {?Matkul :SKS ?SKS . }
                OPTIONAL {?Matkul :Semester ?Semester . }
                OPTIONAL {?Matkul :Deskripsi ?Deskripsi .}
            }
                ";
                // proses ke query 
                $rows = $sc->query($q3, 'rows');
                $err = $sc->getErrors();
                if ($err) {
                    print_r($err);
                    throw new Exception(print_r($err, true));
                }

                if ($deskripsi == "") {
                    $deskripsi = "tidak ada";
                }


                $count = count($rows);
                echo "<div> Menampilkan Semua Mata Kuliah </div>";

                if (empty($rows["result"]["rows"])) {
                    echo "<div><h2>Hasil tidak ditemukan</h2></div>";
                }

                foreach ($rows["result"]["rows"] as $row) {
                    $matkul = $row["Nama_Matkul"];
                    $dosen = $row["Nama_Dosen"];
                    $semester = $row["Semester"];
                    $sks = $row["SKS"];
                    $kode = $row["Kode_Matkul"];
                    $deskripsi = $row["Deskripsi"];
                    $biodata = $row["Biodata_Dosen"];

                    echo "
                <div class='card-result'>
                    Mata Kuliah : <strong>$matkul</strong> <br>
                    <button class='collapsible'>Deskripsi Mata Kuliah</button>
                    <div class='deskripsi'>
                    <p>$deskripsi</p>
                    </div>
                    Nama Dosen : $dosen<br>
                    Semester : $semester<br>
                    SKS : $sks<br>
                    Kode : $kode<br>
                    Biodata Dosen :  <a href='" . $biodata . "'>$biodata</a><br>
                </div>";
                }
            }



            ?>
        </div>
        <script src="js/collapsible.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>