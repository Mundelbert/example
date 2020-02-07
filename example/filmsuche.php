<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Filmsuche</title>
  </head>
  <body>
    <div  class="custom-container">
      <nav>
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link active" href="filmsuche.html">Filmsuche</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="schauspielersuche.html">Schauspielersuche</a>
          </li>
        </ul>
      </nav>

      <div class="content-container">
        <h3>Suchergebnis</h3>
        <?php
          if (isset($_GET["input"]) && $_GET["input"] != "") {
            $connection = new PDO("mysql:host=localhost;dbname=filmauswahl", "root", "");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $param = "%" . $_GET["input"] . "%";

            $statement = $connection->prepare(
              "SELECT Name FROM produktionsfirma
              WHERE Name LIKE :input;"
            );
            $statement->bindParam(':input', $param);
            $statement->execute();
            $result = $statement->fetchAll();
            // echo "<script>console.log('" . json_encode($result) . "');</script>";
            ?>

            <p>Gesuchte Produktionsfirma: <span><?php echo $_GET["input"] ?></span></p>
          
            <?php
            if (sizeof($result) == 0) {
              echo "<p>Produktionsfirma nicht gefunden</p>";
            } else {
              echo "<p>Gefundene Produktionsfirma/Produktionsfirmen: ";
              $resultString = "";
              foreach ($result as $row) {
                $resultString = $resultString . $row["Name"] . ", ";
              }
              echo "<span>" . substr($resultString, 0, -2) . "</span></p>";

              $statement = $connection->prepare(
                "SELECT film.Name AS Titel, film.Erscheinungsdatum AS Erscheinungsdatum, produktionsfirma.Name AS Produktionsfirma
                FROM film
                LEFT JOIN produktionsfirma
                ON film.Produktionsfirma_Id = produktionsfirma.Id
                WHERE produktionsfirma.Name LIKE :input
                ORDER BY film.Erscheinungsdatum asc;"
              );
              $statement->bindParam(':input', $param);
              $statement->execute();
              $result = $statement->fetchAll();

              echo "<p>Gefundene Filmtitel: <span>" . sizeof($result) . "</span></p>";

              if (sizeof($result) > 0) {
                ?>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Titel</th>
                      <th scope="col">Erscheinungsdatum</th>
                      <th scope="col">Produktionsfirma</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach ($result as $row) {
                        echo "<tr>";
                          echo "<td>" . $row["Titel"] . "</td>";
                          echo "<td>" . date("m.d.Y", strtotime($row["Erscheinungsdatum"])) . "</td>";
                          echo "<td>" . $row["Produktionsfirma"] . "</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
                <?php
                }
              }
          } else {
            echo "<p>Es wurde keine Produktionsfirma mitgegeben</p>";
          }
        ?>
      </div>
    </div>
  </body>
</html>
