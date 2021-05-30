<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;400&family=Salsa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Document</title>
</head>

<body>
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
        <div class="search-container">
            <form action="result.php" method="POST">
            	<?php 
		            if (isset($_POST['search'])) {
		    					if (empty($_POST['search'])) {
		        						$search = 'Search the lesson here...';
		    						} else { 
		        						$search = $_POST['search'];
		    						}
								}
            	?>
                <input type="text" placeholder="<?php echo $search ?>" name="search">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class='result'>




        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


        <!--Backend-->
        <?php

        use BorderCloud\SPARQL\SparqlClient;

        require_once('../vendor/autoload.php');

        //Error Handling
        $search = false;
        $matkul = false;
        $dosen = false;
        $kode = false;
        $semester = false;
        $sks = false;
        $biodata = false;

        if (isset($_POST['search']))
            $search = $_POST['search'];

        if (!$search) {
            echo "<div><h1>Masukkan Pencarian!</h1></div>";
        }
        //Error Handling
        else {
            $fuseki_server = "http://localhost:3030"; // fuseki server address 
            $fuseki_sparql_db = "Matkul2"; // fuseki Sparql database 
            $endpoint = $fuseki_server . "/" . $fuseki_sparql_db . "/query";
            $sc = new SparqlClient();
            $sc->setEndpointRead($endpoint);
            $q = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
                PREFIX owl: <http://www.w3.org/2002/07/owl#>
                PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
                PREFIX : <http://www.semanticweb.org/sarah/ontologies/2021/4/Matkul#>
                
                SELECT ?Kode_Matkul ?Nama_Matkul ?Nama_Dosen ?Biodata_Dosen ?SKS ?Semester
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
                FILTER (regex(?Nama_Matkul, '$search', 'i') || 
                  regex(?Nama_Dosen, '$search', 'i') ||
                  regex(?SKS, '$search', 'i') ||
                  regex(?Kode_Matkul, '$search', 'i') ||
                  regex(?Semester, '$search', 'i')) }
                ";
            // proses ke query 
            $rows = $sc->query($q, 'rows');
            $err = $sc->getErrors();
            if ($err) {
                print_r($err);
                throw new Exception(print_r($err, true));
            }

            echo "<div>Hasil Pencarian $search</div>";

            if(empty($rows["result"]["rows"])){
               echo "<div><h2>Hasil tidak ditemukan</h2></div>";
            }

            foreach ($rows["result"]["rows"] as $row) {
                $matkul = $row["Nama_Matkul"];
                $dosen = $row["Nama_Dosen"];
                $semester = $row["Semester"];
                $sks = $row["SKS"];
                $kode = $row["Kode_Matkul"];
                $biodata = $row["Biodata_Dosen"];

                echo "
                <div class='card-result'>
                    Mata Kuliah : <strong>$matkul</strong> <br>
                    Nama Dosen : $dosen<br>
                    Semester : $semester<br>
                    SKS : $sks<br>
                    Kode : $kode<br>
                    Biodata Dosen :  <a href='".$biodata."'>$biodata</a><br>
                </div>";
            }
            // echo "
            
            // </div>
            // <fieldset>
            // <legend>MatkulTI</legend>
            // <legend>Teknik Informatika Unpad</legend>
            // </fieldset>
            // </div>";
        }
        ?>
        </div>

</body>

</html>