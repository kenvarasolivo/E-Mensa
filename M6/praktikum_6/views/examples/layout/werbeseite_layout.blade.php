<!DOCTYPE html>
<!-- layout/werbeseite_layout.blade.php -->

<!--- Praktikum DBWT. Autoren:
- Kenvara Solivo, Lwie, 3660821
- Hanif Aulia, Ibrahim, 3657278
-->

<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/index.css">
<body>
<div class="dbwt">
    <head>

        @section('header')
            <div class="top">
                <img id="headphoto" src="img/logo-studentenwerk.png" >
                <ul id="choices">
                    <li class="c1"><a href="#ankun">Ankündigung</a></li>
                    <li class="c1"><a href="#menu"> Speisen</a> </li>
                    <li class=" c1"><a href="#geld">Zahlen</a></li>
                    <li class = "c1"><a href="#kontakt">Kontakt</a></li>
                    <li class = "c1"><a href="#wichtig">Wichtig für uns</a></li>
                    <li class="c1" id="anmeldung">
                        <?php if (isset($_SESSION['user_name'])): ?>
                        <span>Angemeldet als: <strong>{{ htmlspecialchars($_SESSION['user_name']) }}</strong></span>
                        <a href="/abmeldung">Abmelden</a>
                        <?php else: ?>
                        <a href="/anmeldung">Anmelden</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        @show

    </head>

    <main>
        <div class="glass-wrapper">
        @yield('text')

        @yield('main')

        <br><br><br>

        @section('footer')
            <footer>
                <ul>
                    <li>(c) E-Mensa GmbH</li>
                    <li> &ltKenvara Solivo lwie&gt</li>
                    <li>Impressum</li>
                </ul>
            </footer>
        @show
        </div>
    </main>
</div>
</body>
</html>
