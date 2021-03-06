<?php
if(session_id() == ''){
    session_start();
    $_SESSION['root'] = __DIR__ . "/..";
    $protocollo = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $_SESSION['web_root'] = "{$protocollo}://{$_SERVER['SERVER_NAME']}/ErasmusReview";
}
include_once "{$_SESSION['root']}/View/include/struttura.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Agenzia.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Azienda.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Docente.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Scuola.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Studente.php";

$html = creaHeader("Gestione Account");
$html .= creaBarraMenu($_SESSION['email_utente'] ?? "", $_SESSION['tipo_utente'] ?? "");
//controllo se ha effettuato l'accesso
if(!isset($_SESSION['email_utente'])) {
    $html .=<<<testo
        <h2>Devi aver eseguito l'accesso con un account per poter vedere questa pagina</h2>
        <a href="{$_SESSION['web_root']}/View/login.php">Accedi</a>
    testo;
}else{
    if(isset($_GET["errore"])){
        if($_GET["errore"]==2){
            $html .= "<h2>Errore generico, non sono riuscito a cambiare la password</h2>\n";
        }
    }

    $html .=<<<testo
            <h2>Gestione account</h2>
            <div id="gestione-account">\n
    testo;

    if($_SESSION["tipo_utente"] != "admin"){
        $soggetto = unserialize($_SESSION[$_SESSION["tipo_utente"]]);
        
        $html .=<<<testo
                <div>
                    <h3>I tuoi dati</h3>\n
        testo;

        switch($_SESSION["tipo_utente"]){
            case 'azienda':
                $html.=<<<testo
                        <p><strong>Email: </strong>{$soggetto->getEmail()}</p>
                        <p><strong>Nome: </strong>{$soggetto->getNome()}</p>
                        <p><strong>Stato: </strong>{$soggetto->getStato()}</p>
                        <p><strong>Citt&agrave;: </strong>{$soggetto->getCitta()}</p>
                        <p><strong>Indirizzo: </strong>{$soggetto->getIndirizzo()}</p>
                        <p><strong>Telefono: </strong>{$soggetto->getTelefono()}</p>\n
                testo;
            break;
            case 'agenzia':
                $html.=<<<testo
                        <p><strong>Email: </strong>{$soggetto->getEmail()}</p>
                        <p><strong>Nome: </strong>{$soggetto->getNome()}</p>
                        <p><strong>Stato: </strong>{$soggetto->getStato()}</p>
                        <p><strong>Citt&agrave;: </strong>{$soggetto->getCitta()}</p>
                        <p><strong>Indirizzo: </strong>{$soggetto->getIndirizzo()}</p>
                        <p><strong>Telefono: </strong>{$soggetto->getTelefono()}</p>\n
                testo;
            break;
            case 'studente':
                $html.=<<<testo
                        <p><strong>Email: </strong>{$soggetto->getEmail()}</p>
                        <p><strong>Nome: </strong>{$soggetto->getNome()}</p>
                        <p><strong>Cognome: </strong>{$soggetto->getCognome()}</p>
                        <p><strong>Data di nascita: </strong>{$soggetto->getDataNascita()}</p>\n
                testo;
            break;
            case 'docente':
                $html.=<<<testo
                        <p><strong>Email: </strong>{$soggetto->getEmail()}</p>
                        <p><strong>Nome: </strong>{$soggetto->getNome()}</p>
                        <p><strong>Cognome: </strong>{$soggetto->getCognome()}</p>\n
                testo;
            break;
            case 'scuola':
                $html .=<<<testo
                        <p><strong>Email: </strong>{$soggetto->getEmail()}</p>
                        <p><strong>Codice Meccanografico: </strong>{$soggetto->getId()}</p>
                        <p><strong>Nome: </strong>{$soggetto->getNome()}</p>
                        <p><strong>Citt&agrave;: </strong>{$soggetto->getCitta()}</p>
                        <p><strong>Indirizzo: </strong>{$soggetto->getIndirizzo()}</p>\n
                testo;
            break;
        }
        $html .= "\t\t</div>\n";
    }else{
        $html.=creaFormCambioEmail();
    }

    $html.=creaFormCambioPassword();
    $html .= "\t</div>\n";
}

$html.=creaFooter();
echo $html;

function creaFormCambioPassword(){
    $html=<<<testo
            <div>
                <form method="POST" action="{$_SESSION['web_root']}/index.php?comando=cambio-password" onsubmit="return controlloPassword()">
                    <fieldset>
                        <legend>Modifica password</legend>
                        <label>Cambia password:</label><br>
                        <input type="password" name="password" id="password" autofocus required><br>
                        <label>Conferma password</label><br>
                        <input type="password" name="passwordConferma" id="passwordConferma" required><br>
                        <input type="submit">
                    </fieldset>
                </form>
            </div>\n
    testo;
    return $html;
}

function creaFormCambioEmail(){
    $html=<<<testo
            <div>
                <form method="POST" action="{$_SESSION['web_root']}/index.php?comando=cambio-email" onsubmit="return controlloEmail()">
                    <fieldset>
                        <legend>Modifica email</legend>
                        <label>Cambia email:</label><br>
                        <input type="email" name="email" id="email" required><br>
                        <label>Conferma email:</label><br>
                        <input type="email" name="emailConferma" id="emailConferma" required><br>
                        <input type="submit">
                    </fieldset>
                </form>
            </div>\n
    testo;
    return $html;
}