<?php
if(session_id() == ''){
    session_start();
    $_SESSION['root'] = __DIR__ . "/..";
    $protocollo = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $_SESSION['web_root'] = "{$protocollo}://{$_SERVER['SERVER_NAME']}/ErasmusReview";
}
include_once "{$_SESSION['root']}/View/include/struttura.php";

$html = creaHeader("Login");
$html .= creaBarraMenu($_SESSION['email_utente'] ?? null, $_SESSION['tipo_utente'] ?? "");
$html .= "<h2>Login</h2>\n";
if(isset($_GET['errore']) && $_GET['errore'] == 1) {
    $html .=<<<testo
        <script>
            alert("Le credenziali inserite non sono valide!");
        </script>
    testo;
}
$html .= <<<testo
        <form method="POST" action="{$_SESSION['web_root']}/index.php?comando=login">
            <fieldset>
                <legend>Accedi al tuo account</legend>
                <label for="email">Indirizzo email</label><br>
                <input type="email" name="email" placeholder="Indirizzo email" autofocus required><br>
                <label for="password">Password</label><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="submit" value="Accedi">
            </fieldset>        
        </form>
testo;

$html .= creaFooter();
echo $html;
?>