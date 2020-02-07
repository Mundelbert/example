<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Document</title>
  </head>
  <body>
    <div class="custom-container">
      <nav>
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" href="filmsuche.html">Filmsuche</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="schauspielersuche.html">Schauspielersuche</a>
          </li>
        </ul>
      </nav>

      <div class="content-container">
        <h3>Suchergebnis</h3>
        <?php
          if (isset($_GET["firstname"]) && isset($_GET["lastname"])) {
            // echo "<script>console.log('" . json_encode($result) . "');</script>";
            $connection = new PDO("mysql:host=localhost;dbname=filmauswahl", "root", "");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Trim um eventuelle Leerzeichen zu entfernen
            $firstName = trim($_GET["firstname"]);
            $lastName = trim($_GET["lastname"]);

            $statement = $connection->prepare(
              "SELECT Vorname, Nachname
              FROM schauspieler
              WHERE Vorname LIKE :firstName AND Nachname LIKE :lastName;"
            );
            $param = "%" . $firstName ."%";
            $param2 = "%" . $lastName ."%";
            $statement->bindParam(':firstName', $param);
            $statement->bindParam(':lastName', $param2);
            $statement->execute();
            $result = $statement->fetchAll();
            ?>

            <p>Gesuchter Schauspieler: <span><?php echo $_GET["firstname"] . " " . $_GET["lastname"] ?></span></p>
            
            <?php
            if (sizeof($result) == 0) {
              echo "<p>Schauspieler nicht gefunden</p>";
            } else {
              echo "<p>Gefundene Schauspieler: ";
              $resultString = "";
              foreach ($result as $row) {
                $resultString = $resultString . $row["Vorname"] . " " . $row["Nachname"] . ", ";
              }
              echo "<span>" . substr($resultString, 0, -2) . "</span></p>";

              $statement = $connection->prepare(
                "SELECT film.Name AS Film, produktionsfirma.Name AS Produktionsfirma
                FROM schauspieler
                INNER JOIN schauspieler_has_film ON schauspieler.id = schauspieler_has_film.schauspieler_Id
                INNER JOIN film ON schauspieler_has_film.film_Id = film.Id
                INNER JOIN produktionsfirma ON film.produktionsfirma_Id = produktionsfirma.Id
                WHERE Vorname LIKE :firstName AND Nachname LIKE :lastName
                ORDER BY film.Name;"
              );
              $param = "%" . $firstName ."%";
              $param2 = "%" . $lastName ."%";
              $statement->bindParam(':firstName', $param);
              $statement->bindParam(':lastName', $param2);
              $statement->execute();
              $result = $statement->fetchAll();
              
              echo "<p>Gefundene Filme: <span>" . sizeof($result) . "</span></p>";

              if (sizeof($result) > 0) {
                ?>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Film</th>
                      <th scope="col">Produktionsfirma</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach ($result as $row) {
                        echo "<tr>";
                          echo "<td>" . $row["Film"] . "</td>";
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
            echo "<p>Es wurde kein Schauspieler mitgegeben</p>";
          }
        ?>
      </div>
    </div>
  </body>
</html>
