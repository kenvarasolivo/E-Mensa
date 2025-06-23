<?php
include 'gerichte.php';  // Einbinden der Datei, die das Gerichte-Array enthält
?>

<?php
/**
 * Praktikum DBWT. Autoren:
 * Kenvara Solivo, Lwie, 3660821
 *  Hanif Aulia, Ibrahim, 3657278
 */

$link = mysqli_connect("localhost", "root", "Kokomi23@", "emensawerbeseite");
mysqli_set_charset($link, "utf8mb4");

if (!$link) {
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// Standard-Sortierreihenfolge (aufsteigend)
//$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$allowed_orders = ['ASC', 'DESC']; // Whitelist for allowed sorting orders
$order = 'ASC'; // Default value

if (isset($_GET['order']) && in_array(strtoupper($_GET['order']), $allowed_orders)) {
    $order = strtoupper($_GET['order']);
}

function updateVisitorCount($link) {
    // Erhöhe den Besucherzähler um 1
    $sql = "UPDATE visitor_counter SET count = count + 1 WHERE id = 1";
    mysqli_query($link, $sql);

    // Hole den aktuellen Besucherzähler
    $result = mysqli_query($link, "SELECT count FROM visitor_counter WHERE id = 1");
    $visitor_count = mysqli_fetch_assoc($result)['count'];
    mysqli_free_result($result);

    return $visitor_count;
}

$visitor_count = updateVisitorCount($link);

// Standardsprachauswahl (Deutsch)
$interval = '2';
function is_temp_email($email) {
    $temp_domains = ['wegwerfmail.de', 'trashmail.de', 'trashmail.com'];
    foreach ($temp_domains as $domain) {
        if (strpos($email, $domain) !== false) {
            return true;
        }
    }
    return false;
}

$success_message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['user']);
    $email = trim($_POST['email']);
    $datenschutz = isset($_POST['datenschutz']) ? true : false;
    $interval = $_POST['interval'] ?? '2';

    if (empty($name) || ctype_space($name)) {
        $errors[] = "Bitte geben Sie einen gültigen Namen an.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
    } elseif (is_temp_email($email)) {
        $errors[] = "Wegwerf-E-Mail-Adressen sind nicht erlaubt.";
    }
    if (!$datenschutz) {
        $errors[] = "Bitte stimmen Sie den Datenschutzbestimmungen zu.";
    }

    $languages = ['1' => 'English', '2' => 'Deutsch', '3' => 'Indonesisch'];
    $language = $languages[$interval] ?? 'Deutsch';

    if (empty($errors)) {
        // Speichern der Anmeldung in der Datenbank
        /*INSERT INTO newsletter_anmeldungen (name, email, language) VALUES ('$name', '$email', '$language')";
         *(mysqli_query($link, $query)) { $success_message = "Ihre Anmeldung war erfolgreich! Vielen Dank."; }
         * else { $errors[] = "Es gab einen Fehler beim Speichern Ihrer Daten."; }
         */
        $stmt = $link->prepare("INSERT INTO newsletter_anmeldungen (name, email, language) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $language);
        if ($stmt->execute()) {
            $success_message = "Ihre Anmeldung war erfolgreich! Vielen Dank.";
        } else {
            $errors[] = "Es gab einen Fehler beim Speichern Ihrer Daten.";
        }
        $stmt->close();
    }
}


$result = mysqli_query($link, "SELECT COUNT(*) as count FROM newsletter_anmeldungen");
$newsletter_count = mysqli_fetch_assoc($result)['count'];
mysqli_free_result($result);
?>

<!DOCTYPE html>
<!--- Praktikum DBWT. Autoren:
- Kenvara Solivo, Lwie, 3660821
- Hanif Aulia, Ibrahim, 3657278
-->

<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        body{
            border: 1px solid black;
            font-family: "Comic Sans MS";

            margin-right:10%;
            margin-left: 10%;
        }
        .top{
            display: grid;
            grid-template-columns: 15% auto;
            border-bottom: 1px solid black;
        }
        #head{
            border: 1px solid black;
            margin-left: 10px;
        }

        main{
            margin-left:20%;
            margin-right:20%;

        }

        #choices{
            display: grid;
            list-style: none;
            grid-template-columns: 145px 100px 100px 100px 200px;
            border: 1px solid black;
            margin-right:400px;

        }

        .paragraph{
            display:flex;
            border: solid black 1px;
            text-align: center;
            p{
                font-size: 30px
            }
        }

        table{
            border: 2px solid black;
            margin: auto;
            border-collapse: collapse;

            height:200px;
            td{
                font-size: 20px;
            }
        }

        .rind{
            border-top:2px solid black;
        }

        td{
            border: 1px solid black;
            text-align: center;
        }


        #zahl{
            list-style: none;

            display: grid;
            grid-template-columns:  auto auto auto ;
            li{
                margin-right:20px;
                font-weight:bold;
                font-size: 20px;
            }
        }

        .c3{
            display:grid;
            grid-template-columns: auto auto 40%;
            justify-content: start;

            .text{
                position: relative;
                margin-right: 10px;
                label{
                    position:absolute;
                    top: -20px;
                }
            }
        }

        .select{
            height:20px;
        }

        .datenschutz{
            margin-right: 10px;
        }

        .beste{
            margin-left: 100px;
        }

        footer{
            border-top: 1px solid black;
        }

        footer ul {
            list-style: none;

            display: grid;
            grid-template-columns: auto auto auto;
            justify-content: center;

        }

        footer li {
            margin: 20px;
        }

        footer li :not(:last-child){
            border-right: 1px solid darkgray;
            padding-right: 20px;
        }

        .besuch{
            display: flex;
            justify-content: center;
        }

    </style>

<body>
<div class="dbwt">
    <head>
        <div class="top">
            <img id="head" src="logo-studentenwerk.png" >
            <ul id="choices">
                <li class="c1"><a href="#ankun">Ankündigung</a></li>
                <li class="c1"><a href="#menu"> Speisen</a> </li>
                <li class=" c1"><a href="#geld">Zahlen</a></li>
                <li class = "c1"><a href="#kontakt">Kontakt</a></li>
                <li id= "c2" class = "c1"><a href="#wichtig">Wichtig für uns</a></li>
            </ul>
        </div>
    </head>
    <main>
        <img src="mensa-fh-aachen.jpg" height="450px">
        <div class="mid">
            <h1 id="ankun">Bald gibt es Essen auch online;)</h1>
        </div>
        <div class="paragraph">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad dignissimos dolore doloremque facere nulla quae repellendus voluptatibus. Amet, assumenda at eum ex fuga libero nobis quod, quos repellendus sapiente, voluptatibus!</p>
        </div>
        <div class="par">
            <h1 id="menu">Köstlichkeiten, die sie erwarten</h1>
        </div>
        <div class="sortierung">
            <a href="?order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">
                Sortiere nach Name: <?php echo $order === 'ASC' ? 'Absteigend' : 'Aufsteigend'; ?>
            </a>
        </div>
        <table>
            <tr>
                <th>Gerichte</th>
                <th>Preis intern</th>
                <th>Preis extern</th>
                <th>Allergene</th>
            </tr>

            <?php
            // Fetch dishes and allergens
            $sql1 = "SELECT id, name, preisintern, preisextern FROM gericht ORDER BY name $order";
            $result1 = mysqli_query($link, $sql1);

            $displayed_allergens = [];

            for ($i=0;$i<5;$i++) {
                $row1 = mysqli_fetch_assoc($result1);
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row1['name']) . '</td>';
                echo '<td>' . number_format($row1['preisintern'], 2, ',', '.') . '</td>';
                echo '<td>' . number_format($row1['preisextern'], 2, ',', '.') . '</td>';

                // Fetch allergens for the dish
                $sql2 = "SELECT DISTINCT code FROM gericht_hat_allergen WHERE gericht_id = " . intval($row1['id']);
                $result2 = mysqli_query($link, $sql2);

                $allergen_list = [];
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $allergen_list[] = $row2['code'];
                    $displayed_allergens[$row2['code']] = true;
                }
                echo '<td>' . implode(', ', $allergen_list) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <ul id="allergen">
            <?php
            if (!empty($displayed_allergens)) {
                $allergen_codes = "'" . implode("','", array_keys($displayed_allergens)) . "'";
                $sql3 = "SELECT code, name, typ FROM allergen WHERE code IN ($allergen_codes) ORDER BY typ";
                $result3 = mysqli_query($link, $sql3);

                echo '<ul id="allergen">';
                $current_typ = null;
                while ($row3 = mysqli_fetch_assoc($result3)) {
                    if ($current_typ !== $row3['typ']) {
                        if ($current_typ !== null) {
                            echo '<br>';
                        }
                        echo '<strong>' . $row3['typ'] . ':</strong><br>';
                        $current_typ = $row3['typ'];
                    }
                    echo '<li>' . $row3['code'] . ': ' . $row3['name'] . '</li>';
                }
                echo '</ul>';
            }
            ?>
        </ul>
        <br>
        <a href="./wunschgericht.php">Wunschgericht erfassen</a>
        <br>

        <div class="zahlen">
            <h1 id="geld">E-Mensa in Zahlen</h1>
        </div>
        <ul id="zahl">
            <li><?php echo $visitor_count; ?> Besuche</li>
            <li><?php echo $newsletter_count; ?> Anmeldungen zum Newsletter</li>
            <li><?php
                $sql1= "SELECT COUNT(id) FROM gericht ";
                $result1= mysqli_query($link,$sql1);
                $row4=mysqli_fetch_array($result1);
                $AnzahlSpeisen=$row4[0];
                echo $AnzahlSpeisen;
                ?> Speisen</li>
        </ul>

        <div class="kontakt">
            <h1 id = "kontakt">Interesse geweckt? wir informieren sie!</h1>
        </div>

        <form action="index.php"  method="post">
            <div class ="c3">
                <p class="text">
                    <label for="name">Ihr Name:</label>
                    <input type="text" id="name" name="user" placeholder="Vorname" class="inp">
                </p>
                <p class="text">
                    <label for="E-mail">Ihre E-mail:</label>
                    <input type="text" id="E-mail" name="email" class="inp" >
                </p>
                <p class = "text">
                    <label for = "la">Newsletter bitte ein:</label>
                    <select id = "la" class="select" name="interval">
                        <option value="1" <?php echo ($interval === '1') ? 'selected' : ''; ?>>English</option>
                        <option value="2" <?php echo ($interval === '2') ? 'selected' : ''; ?>>Deutsch</option>
                        <option value="3" <?php echo ($interval === '3') ? 'selected' : ''; ?>>Indonesisch</option>
                    </select>
                </p>
            </div>


            <input type ="checkbox" id="datenschutz" name="datenschutz">
            <label for="datenschutz" class = "datenschutz">Den Datenschutzbestimmungen Stimme ich zu</label>
            <input type="submit" value="Zum Newsletter anmelden">
            <br>

            <?php if (!empty($success_message)): ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <ul style="color: red;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </form>


        <div class="wichtig">
            <h1 id="wichtig">Das ist uns wichtig</h1>
        </div>
        <div class="beste">
            <ul class="das ist wichtig">
                <li>Beste frische saisonale Zutaten</li>
                <li >Ausgewagene abwechslungsreiche Gerichte</li>
                <li>Sauberkeit</li>
            </ul>
        </div>
        <h1 class = "besuch">  Wir freuen uns auf ihren besuch!</h1>
        <footer>
            <ul>
                <li>(c) E-Mensa GmbH</li>
                <li> &ltIhre Namen hier&gt</li>
                <li>Impressum</li>
            </ul>
        </footer>
    </main>
</div>
</body>
</html>

<?php
mysqli_close($link);
?>