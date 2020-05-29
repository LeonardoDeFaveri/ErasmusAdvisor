<?php
if(session_id() == ''){
    session_start();
    $_SESSION['root'] = __DIR__ . "/../..";
    $protocollo = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $_SESSION['web_root'] = "{$protocollo}://{$_SERVER['SERVER_NAME']}/ErasmusReview";
}
include_once "{$_SESSION['root']}/View/include/struttura.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Scuola.php";

$html = creaHeader("Home Admin");
$html .= creaBarraMenu($_SESSION['email_utente'] ?? "", $_SESSION['tipo_utente'] ?? "");
if(isset($_GET['errore']) || !isset($_SESSION['tipo_utente']) || $_SESSION['tipo_utente'] != 'admin'){
    $html .=<<<testo
        <h2>Devi aver eseguito l'accesso come admin per poter vedere questa pagina</h2>
        <a href="{$_SESSION['web_root']}/View/login.php">Accedi</a>
    testo;
}
else{
    $scuole=unserialize($_SESSION["scuole"]);

    $html.=<<<testo
        <h2>Tutti gli account scuola</h2>
        <div class="contenitore-centrato">
            <table>
                <thead>
                    <tr>
                        <th>Codice Meccanografico</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Citt&agrave;</th>
                        <th>Indirizzo</th>
                        <th>Modifica</th>
                    </tr>
                </thead>
                <tbody>
    testo;

    foreach($scuole as $elemento){  
        $html .=<<<testo
        <tr>
            <td><a href="{$_SESSION['web_root']}/index.php?comando=mostra-scuola&codice_meccanografico={$elemento->getId()}">{$elemento->getId()}</a></td>
            <td>{$elemento->getNome()}</td>
            <td>{$elemento->getEmail()}</td>
            <td>{$elemento->getCitta()}</td>
            <td>{$elemento->getIndirizzo()}</td>
            <td><a href="{$_SESSION['web_root']}/index.php?comando=modifica-account-scuola&codice_meccanografico={$elemento->getId()}"><i class="material-icons">mode_edit</i></a></td>
        </tr>\n
    testo;
    }
    $html.=<<<testo
                </tbody>
            </table>
        </div>
        <form action="{$_SESSION['web_root']}/index.php?comando=crea-scuola" method="POST">
            <button type="submit">Aggiungi scuola</button>
        </form>
    testo;

    if(isset($_GET["errore"])){
        if($_GET["errore"] == '2'){
            $html .=<<<testo
                <script>
                    alert("Qualcosa &ègrave; andato storto durante l'inserimento");
                </script>
            testo;
        }
    }
}

$html .= creaFooter();
echo $html;