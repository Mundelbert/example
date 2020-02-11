# Informationstechnologie - Informatik

## Getting Started

### Tools

- [Visual Studio Code](https://code.visualstudio.com/)
  - Extensions
    - [Horizon Theme](https://marketplace.visualstudio.com/items?itemName=jolaleye.horizon-theme-vscode)
    - [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
    - [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-intellisense)
  - [Settings](https://github.com/Bluuax/lap/blob/master/configurations/vs-code-settings.json)
- [GitHub Desktop](https://desktop.github.com/)
- [XAMPP](https://www.apachefriends.org/de/index.html)
- [MySQL Workbench](https://www.mysql.com/products/workbench)
  - [Version 8.0.18](https://downloads.mysql.com/archives/workbench/) - Windows (x86, 64-bit), MSI Installer
  - [Visual C++ Redistributable für Visual Studio 2015](https://www.microsoft.com/de-at/download/details.aspx?id=48145)
  - Forward Engineering konfigurieren: Model --> Model Options --> Target MySQL: 5.7
  - Varchar auf 255 setzen

### Allgemein

- Chrome als Standardbrowser festlegen
- Virtuelle Desktops konfigurieren

## SQL

Checklist

- Modell speichern
- Target MySQL: 5.7 einstellen
- VARCHAR(255): Edit --> Preferences --> Modeling --> Defaults --> Column Type
- Dokumentation starten

### Export

- Administration -> Data Export
  - Edit -> Preferences -> Administrator -> Path to MySQLDumpTool -> C:\xampp\mysql\bin\mysqldump.exe

## Coding

URL

```
http://localhost/lap/example/filmsuche.php
```

Connect workbench

```
$server   = 'localhost';
$database = '${name}';
$user     = 'root';
$password = '';
```

DB-Zugriff
```
try {
  $connection = new PDO("mysql:host=localhost;dbname=filmauswahl", "root", "");
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $statement = $connection->prepare(
    "SELECT Name 
    FROM produktionsfirma
    WHERE Name LIKE :input;"
  );
  $statement->bindParam(':input', $param);
  $statement->execute();
  $result = $statement->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $connection = null;
}

```

Console logging

```
echo "<script>console.log('" . json_encode($result) . "');</script>";
```

## Helpful Links

- [PHP](https://www.w3schools.com/php/default.asp)
- [SQL](https://www.w3schools.com/sql/default.asp)
- [Bootstrap](https://www.w3schools.com/bootstrap/default.asp)
- [Bootstrap - Navs](https://getbootstrap.com/docs/4.0/components/navs/)
