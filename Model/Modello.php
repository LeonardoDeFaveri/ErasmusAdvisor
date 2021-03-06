<?php
if(session_id() == ''){
    session_start();
    $_SESSION['root'] = __DIR__ . "/..";
}
include_once "{$_SESSION['root']}/Model/Soggetti/Agenzia.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Azienda.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Docente.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Famiglia.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Scuola.php";
include_once "{$_SESSION['root']}/Model/Soggetti/Studente.php";
include_once "{$_SESSION['root']}/Model/Aspetto.php";
include_once "{$_SESSION['root']}/Model/Classe.php";
include_once "{$_SESSION['root']}/Model/Esperienza.php";
include_once "{$_SESSION['root']}/Model/Percorso.php";
include_once "{$_SESSION['root']}/Model/Valutazione.php";
include_once "{$_SESSION['root']}/Model/SchedaDiValutazione.php";
include_once "{$_SESSION['root']}/Model/ModelloSchedaDiValutazione.php";
include_once "{$_SESSION['root']}/Model/Utili/ContenitoreSoggettoDate.php";

class Modello {
    private $connessione;

    public function __construct(){
        $this->connessione = new mysqli("localhost", "root", "", "erasmus_review");
        if ($this->connessione->connect_errno != 0) {
            throw new Exception("Server non raggungibile");
        }
        if ($this->connessione->errno != 0) {
            throw new Exception("Database non raggiungibile");
        }
    }

    /**
     * getAziendaDaId estrae dal database l'azienda associata all'id specificato.
     *
     * @param  int $id id dell'azienda da estrarre
     * @return Azienda se è stata trovata, altirmenti null
     */
    public function getAgenziaDaId($id) {
        $query = "SELECT * FROM agenzie WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $agenzia = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $agenzia = new Agenzia(
                $id,
                $ris['nome'],
                $ris['email_utente'],
                $ris['stato'],
                $ris['citta'],
                $ris['telefono'],
                $ris['indirizzo']
            );
        }
        return $agenzia;
    }

    /**
     * getAgenziaDaEmail estrae dal database l'agenzia associata all'email specificata.
     *
     * @param  string $email email dell'agenzia da estrarre
     * @return Agenzia se è stata trovata, altrimenti null
     */
    public function getAgenziaDaEmail($email) {
        $query = "SELECT * FROM agenzie WHERE email_utente = '{$email}'";
        $ris = $this->connessione->query($query);
        $agenzia = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $agenzia = new Agenzia(
                $ris['id'],
                $ris['nome'],
                $email,
                $ris['stato'],
                $ris['citta'],
                $ris['telefono'],
                $ris['indirizzo']
            );
        }
        return $agenzia;
    }

    /**
     * getAgenzie estrae dal database tutte le agenzie.
     *
     * 
     * @return $agenzie, un'array di tutte le agenzie del db
     */
    public function getAgenzie() {
        $query = "SELECT * FROM agenzie";
        $ris = $this->connessione->query($query);
        $agenzie = array();
        if($ris){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $agenzia){
                $agenzie[] = new Agenzia(
                    $agenzia['id'],
                    $agenzia['nome'],
                    $agenzia['email_utente'],
                    $agenzia['stato'],
                    $agenzia['citta'],
                    $agenzia['telefono'],
                    $agenzia['indirizzo']
                );
            }
        }
        return $agenzie;
    }

    /**
     * getAziendaDaid estrae dal database l'azienda associata all'id specificato.
     *
     * @param  int $id id dell'azienda da estrarre
     * @return Azienda se è stata trovata, altirmenti null
     */
    public function getAziendaDaId($id) {
        $query = "SELECT * FROM aziende WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $azienda = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $azienda = new Azienda(
                $id,
                $ris['nome'],
                $ris['email_utente'],
                $ris['stato'],
                $ris['citta'],
                $ris['indirizzo'],
                $ris['telefono']
            );
        }
        return $azienda;
    }

    /**
     * getAziendaDaEmail estrae dal database l'azienda associata all'email specificata.
     *
     * @param  string $email email dell'azienda da estrarre
     * @return Azienda se è stata trovata, altirmenti null
     */
    public function getAziendaDaEmail($email) {
        $query = "SELECT * FROM aziende WHERE email_utente = '{$email}'";
        $ris = $this->connessione->query($query);
        $azienda = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $azienda = new Azienda(
                $ris['id'],
                $ris['nome'],
                $email,
                $ris['stato'],
                $ris['citta'],
                $ris['indirizzo'],
                $ris['telefono']
            );
        }
        return $azienda;
    }

    /**
     * getAziende estrae dal database tutte le aziende.
     *
     * 
     * @return $aziende un array di tutte le aziende disponibili nel db
     */
    public function getAziende() {
        $query = "SELECT * FROM aziende";
        $ris = $this->connessione->query($query);
        $aziende = array();
        if($ris){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $azienda){
                $aziende[] = new Azienda(
                    $azienda['id'],
                    $azienda['nome'],
                    $azienda['email_utente'],
                    $azienda['stato'],
                    $azienda['citta'],
                    $azienda['indirizzo'],
                    $azienda['telefono']
                );
            }
        }
        return $aziende;
    }

    /**
     * getDocenteDaId estrae dal database il docente associato all'id specificato.
     *
     * @param  int $id id del docente da estrarre
     * @return Docente se è stato trovato, altrimenti null
     */
    public function getDocenteDaId($id) {
        $query = "SELECT * FROM docenti WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $docente = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $docente = new Docente(
                $id,
                $ris['nome'],
                $ris['cognome'],
                $ris['email_utente']
            );
        }
        return $docente;
    }

    /**
     * getDocenteDaEmail estrae dal database il docente associato all'email specificata.
     *
     * @param  string $email email del docente da estrarre
     * @return Docente se è stato trovato, altrimenti null
     */
    public function getDocenteDaEmail($email) {
        $query = "SELECT * FROM docenti WHERE email_utente = '{$email}'";
        $ris = $this->connessione->query($query);
        $docente = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $docente = new Docente(
                $ris['id'],
                $ris['nome'],
                $ris['cognome'],
                $email
            );
        }
        return $docente;
    }

    /**
     * getDocentiDaClasse estrae dal database tutti i docenti associati
     * as una classe.
     *
     * @param  int $idClasse id della classe per la quale estrarre i docenti
     * @return ContenitoreSoggettoDate[] contnente i docenti se ne sono stati trovati,
     * altrimenti un array vuoto
     */
    public function getDocentiDaClasse($idClasse) {
        $query=<<<testo
        SELECT D.*, CD.dal, CD.al FROM docenti D
            INNER JOIN classi_docenti CD 
            ON D.id = CD.id_docente
            INNER JOIN classi C
            ON C.id = CD.id_classe
        WHERE id_classe = {$idClasse}
        testo;
        $ris = $this->connessione->query($query);
        $docenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $elemento){
                $docente = new Docente(
                    $elemento["id"],
                    $elemento["nome"],
                    $elemento["cognome"],
                    $elemento["email_utente"]
                );
                $docenti[] = new ContenitoreSoggettoDate($docente, $elemento['dal'], $elemento['al']);
            }
        }
        return $docenti;
    }
    
    /**
     * getDocentiDaScuola estrae dal database tutti i docenti di una scuola.
     *
     * @param  Scuola $scuola scuola della quale estrarre gli studenti
     * @return Docente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getDocentiDaScuola($scuola) {
        $query =<<<testo
        SELECT D.* FROM docenti D
            INNER JOIN docenti_scuole DS
            ON DS.id_docente = D.id
        WHERE DS.codice_scuola = '{$scuola->getId()}'
        ORDER BY D.cognome, D.nome
        testo;
        $ris = $this->connessione->query($query);
        $docenti = array();
        if ($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $docente){
                $docenti[] = new Docente(
                    $docente['id'],
                    $docente['nome'],
                    $docente['cognome'],
                    $docente['email_utente']
                );
            }
        }
        return $docenti;
    }

    /**
     * getDocentiAttiviDaScuola estrae dal database tutti i docenti attivi in questo momento di una scuola.
     *
     * @param  Scuola $scuola scuola della quale estrarre gli studenti
     * @return Docente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getDocentiAttiviDaScuola($scuola) {
        $query =<<<testo
        SELECT D.* FROM docenti D
            INNER JOIN docenti_scuole DS
            ON DS.id_docente = D.id
        WHERE DS.codice_scuola = '{$scuola->getId()}' AND DS.al IS NULL
        ORDER BY D.cognome, D.nome
        testo;
        $ris = $this->connessione->query($query);
        $docenti = array();
        if ($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $docente){
                $docenti[] = new Docente(
                    $docente['id'],
                    $docente['nome'],
                    $docente['cognome'],
                    $docente['email_utente']
                );
            }
        }
        return $docenti;
    }

    /**
     * getFamigliaDaId estrae dal database la famiglia associato all'id specificato.
     *
     * @param  int $id id della famiglia da estrarre
     * @return Famiglia se è stata trovata, altirmenti null
     */
    public function getFamigliaDaId($id) {
        $query = "SELECT * FROM famiglie WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $famiglia = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $famiglia = new Famiglia(
                $id,
                $ris['nome'],
                $ris['cognome'],
                $ris['stato'],
                $ris['citta'],
                $ris['indirizzo']
            );
        }
        return $famiglia;
    }

    /**
     * getFamiglie estrae tutte le famiglie dal database.
     *
     * 
     * @return Famiglia[], un'array di tutte le famiglie del db
     */
    public function getFamiglie() {
        $query = "SELECT * FROM famiglie";
        $ris = $this->connessione->query($query);
        $famiglie = array();
        if($ris){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $famiglia){
                $famiglie[] = new Famiglia(
                    $famiglia['id'],
                    $famiglia['nome'],
                    $famiglia['cognome'],
                    $famiglia['stato'],
                    $famiglia['citta'],
                    $famiglia['indirizzo']
                );
            }
        }
        return $famiglie;
    }

    /**
     * getStudenteDaId estrae dal database lo studente associato all'id specificato.
     *
     * @param  int $id id dello studente da estrarre
     * @return Studente se è stato trovato, altirmenti null
     */
    public function getStudenteDaId($id) {
        $query = "SELECT * FROM studenti WHERE id = $id";
        $ris = $this->connessione->query($query);
        $studente = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $studente = new Studente(
                $id,
                $ris['nome'],
                $ris['cognome'],
                $ris['email_utente'],
                $ris['data_nascita']
            );
        }
        return $studente;
    }

    /**
     * getStudenteDaEmail estrae dal database lo studente associato all'email specificata.
     *
     * @param  string $email email dello studente da estrarre
     * @return Studente se è stato trovato, altirmenti null
     */
    public function getStudenteDaEmail($email) {
        $query = "SELECT * FROM studenti WHERE email_utente = '{$email}'";
        $ris = $this->connessione->query($query);
        $studente = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $studente = new Studente(
                $ris['id'],
                $ris['nome'],
                $ris['cognome'],
                $email,
                $ris['data_nascita']
            );
        }
        return $studente;
    }

    /**
     * getStudentiDaClasse estrae dal database tutti gli studenti di una classe.
     *
     * @param  int $idClasse id della classe dalla quale estrarre gli studenti
     * @return Studente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getStudentiDaClasse($idClasse) {
        $query =<<<testo
        SELECT DISTINCT S.*
        FROM classi_studenti CS
            INNER JOIN studenti S
            ON CS.id_studente = S.id
        WHERE CS.id_classe = {$idClasse}
        ORDER BY S.cognome, S.nome;
        testo;
        $ris = $this->connessione->query($query);
        $studenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $studente) {
                $studenti[] = new Studente(
                    $studente['id'],
                    $studente['nome'],
                    $studente['cognome'],
                    $studente['email_utente'],
                    $studente['data_nascita']
                );
            }
        }
        return $studenti;
    }

    public function getContenitoreStudentiDaClasse($idClasse) {
        $query =<<<testo
        SELECT S.*, CS.dal, CS.al
        FROM classi_studenti CS
            INNER JOIN studenti S
            ON CS.id_studente = S.id
        WHERE CS.id_classe = {$idClasse}
        ORDER BY S.cognome, S.nome;
        testo;
        $ris = $this->connessione->query($query);
        $studenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $studente) {
                $soggetto = new Studente(
                    $studente['id'],
                    $studente['nome'],
                    $studente['cognome'],
                    $studente['email_utente'],
                    $studente['data_nascita']
                );
                $studenti[] = new ContenitoreSoggettoDate($soggetto, $studente['dal'], $studente['al']);
            }
        }
        return $studenti;
    }

    /**
     * getStudentiDaDocente estrae dal database tutti gli studenti che AL MOMENTO DELLA QUERY
     * fanno parte delle classi di un docente.
     *
     * @param  int $idDocente id del docente dalla quale estrarre gli studenti
     * @return Studente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getStudentiDaDocente($idDocente) {
        $query =<<<testo
        SELECT DISTINCT S.* 
        FROM studenti S INNER JOIN classi_studenti CL 
        ON CL.id_studente=S.id 
        INNER JOIN classi_docenti CD 
        ON CD.id_docente = {$idDocente} WHERE CD.al > NOW()
        testo;
        $ris = $this->connessione->query($query);
        $studenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $studente) {
                $studenti[] = new Studente(
                    $studente['id'],
                    $studente['nome'],
                    $studente['cognome'],
                    $studente['email_utente'],
                    $studente['data_nascita']
                );
            }
        }
        return $studenti;
    }
    
    /**
     * getStudentiDaScuola estrae dal database tutti gli studenti di una scuola.
     * Vengono estratti anche gli studenti che non sono più iscritti.
     *
     * @param  string $codiceMeccanografico codice meccanografico della scuola
     * per la quale estrarre gli studenti
     * @return Studente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getStudentiDaScuola($codiceMeccanografico) {
        $query =<<<testo
        SELECT DISTINCT S.* FROM studenti S
            INNER JOIN studenti_scuole SS ON
            S.id = SS.id_studente
            INNER JOIN scuole SC ON
            SC.codice_meccanografico = SS.codice_scuola
        WHERE SS.codice_scuola = '{$codiceMeccanografico}'
        ORDER BY S.cognome, S.nome
        testo;
        $ris = $this->connessione->query($query);
        $studenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $studente) {
                $studenti[] = new Studente(
                    $studente['id'],
                    $studente['nome'],
                    $studente['cognome'],
                    $studente['email_utente'],
                    $studente['data_nascita']
                );
            }
        }
        return $studenti;
    }
    
    /**
     * getStudentiAttiviDaScuola estrae dal database tutti gli studenti
     * ancora iscritti alla scuoola.
     *
     * @param  string $codiceMeccanografico codice meccanografico della scuola
     * per la quale estrarre gli studenti
     * @return Studente[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getStudentiAttiviDaScuola($codiceMeccanografico){
        $query =<<<testo
        SELECT S.* FROM studenti S
            INNER JOIN studenti_scuole SS ON
            S.id = SS.id_studente
            INNER JOIN scuole SC ON
            SC.codice_meccanografico = SS.codice_scuola
        WHERE SS.codice_scuola = 'TVTF007017' AND SS.al IS NULL OR SS.al > NOW()
        ORDER BY S.cognome, S.nome
        testo;
        $ris = $this->connessione->query($query);
        $studenti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $studente) {
                $studenti[] = new Studente(
                    $studente['id'],
                    $studente['nome'],
                    $studente['cognome'],
                    $studente['email_utente'],
                    $studente['data_nascita']
                );
            }
        }
        return $studenti;
    }
    
    /**
     * getClasseDaId estrae dal database una classe e i relativi studenti.
     *
     * @param  int $id id della classe da estrarre
     * @return Classe se è stata trovata, altrimenti null
     */
    public function getClasseDaId($id) {
        $query = "SELECT * FROM classi WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $classe = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $classe = new Classe(
                $id,
                $this->getScuolaDaCodice($ris['codice_scuola']),
                $ris['numero'],
                $ris['sezione'],
                $ris['anno_scolastico'],
                $this->getStudentiDaClasse($ris['id'])
            );
        }
        return $classe;
    }

    /**
     * getClassiDaScuola restitusice tutte le classi di una scuola.
     *
     * @param  Scuola $scuola scuola per la quale estrarre le classi
     * @return Classe[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getClassiDaScuola($scuola) {
        $query = "SELECT * FROM classi WHERE codice_scuola = '{$scuola->getId()}' ORDER BY anno_scolastico DESC";
        $ris = $this->connessione->query($query);
        $classi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $classe){
                $classi[] = new Classe(
                    $classe['id'],
                    $scuola,
                    $classe['numero'],
                    $classe['sezione'],
                    $classe['anno_scolastico'],
                    $this->getStudentiDaClasse($classe['id'])
                );
            }
        }
        return $classi;
    }

    /**
     * getClassiDaDocente restitusice tutte le classi di un docente.
     *
     * @param  Docente $docente docente per il quale estrarre le classi
     * @return Classe[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getClassiDaDocente($docente) {
        $query =<<<testo
        SELECT C.* FROM classi C
            INNER JOIN classi_docenti CD
            ON CD.id_classe = C.id
        WHERE CD.id_docente = {$docente->getId()}
        ORDER BY C.anno_scolastico DESC
        testo;
        $ris = $this->connessione->query($query);
        $classi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $classe){
                $classi[] = new Classe(
                    $classe['id'],
                    $this->getScuolaDaCodice($classe['codice_scuola']),
                    $classe['numero'],
                    $classe['sezione'],
                    $classe['anno_scolastico'],
                    $this->getStudentiDaClasse($classe['id'])
                );
            }
        }
        return $classi;
    }

    /**
     * getClassiDaDocenteEScuola restitusice tutte le classi di un docente
     * di una determinata scuola.
     *
     * @param  Docente $docente docente per il quale estrarre le classi
     * @param  Scuola $scuola scuola per la quale restringere le classi da estrarre
     * @return Classe[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getClassiDaDocenteEScuola($docente, $scuola) {
        $query =<<<testo
        SELECT C.* FROM classi C
            INNER JOIN classi_docenti CD
            ON CD.id_classe = C.id
        WHERE CD.id_docente = {$docente->getId()} 
            AND C.codice_scuola = {$scuola->getId()}
        ORDER BY C.anno_scolastico DESC
        testo;
        $ris = $this->connessione->query($query);
        $classi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $classe){
                $classi[] = new Classe(
                    $classe['id'],
                    $this->getScuolaDaCodice($classe['codice_scuola']),
                    $classe['numero'],
                    $classe['sezione'],
                    $classe['anno_scolastico'],
                    $this->getStudentiDaClasse($classe['id'])
                );
            }
        }
        return $classi;
    }
    
    /**
     * getClassiDaStudente estrae dal database tutte le classi
     * associate ad uno studente.
     *
     * @param  Studente $studente studente per il quale estrarre la classi
     * @return Classe[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getClassiDaStudente($studente) {
        $query =<<<testo
        SELECT C.* FROM classi C
            INNER JOIN classi_studenti CS
            ON C.id = CS.id_classe
        WHERE CS.id_studente = {$studente->getId()}
        ORDER BY C.anno_scolastico DESC
        testo;
        $ris = $this->connessione->query($query);
        $classi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $classe){
                $classi[] = new Classe(
                    $classe['id'],
                    $this->getScuolaDaCodice($classe['codice_scuola']),
                    $classe['numero'],
                    $classe['sezione'],
                    $classe['anno_scolastico'],
                    $this->getStudentiDaClasse($classe['id'])
                );
            }
        }
        return $classi;
    }
    
    /**
     * getScuolaDaCodice estrae dal database una scuola.
     *
     * @param  string $codiceMeccanografico codice meccanografico della scuola da estrarre
     * @return Scuola se è stata trovata, altrimenti null
     */
    public function getScuolaDaCodice($codiceMeccanografico) {
        $query = "SELECT * FROM scuole WHERE codice_meccanografico = '{$codiceMeccanografico}'";
        $ris = $this->connessione->query($query);
        $scuola = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $scuola = new Scuola(
                $codiceMeccanografico,
                $ris['nome'],
                $ris['email_utente'],
                $ris['citta'],
                $ris['indirizzo']
            );
        }
        return $scuola;
    }

    /**
     * getScuolaDaEmail estrae dal database una scuola.
     *
     * @param  string $email email del responsabile della scuola da estrarre
     * @return Scuola se è stata trovata, altrimenti null
     */
    public function getScuolaDaEmail($email) {
        $query = "SELECT * FROM scuole WHERE email_utente = '{$email}'";
        $ris = $this->connessione->query($query);
        $scuola = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $scuola = new Scuola(
                $ris['codice_meccanografico'],
                $ris['nome'],
                $email,
                $ris['citta'],
                $ris['indirizzo']
            );
        }
        return $scuola;
    }

    /**
     * getScuole estrae dal database tutte le scuole.
     *
     * @return Scuola[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getScuole(){
        $query = "SELECT * FROM scuole";
        $ris = $this->connessione->query($query);
        $scuole = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $scuola){
                $scuole[] = new Scuola(
                    $scuola["codice_meccanografico"],
                    $scuola["nome"],
                    $scuola["email_utente"],
                    $scuola["citta"],
                    $scuola["indirizzo"]
                );
            }
        }
        return $scuole;
    }

    /**
     * getEsperienzaDaId estrae dal database l'esperienza associata all'id dato.
     * 
     * @param  int $id id dell'esperienza da estrarre
     * @return Esperienza se è stata trovata, altrimenti null
     */
    public function getEsperienzaDaId($id) {
        $query = "SELECT * FROM esperienze WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $esperienza = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $esperienza = new Esperienza(
                $id, 
                $this->getStudenteDaId($ris['id_studente']), 
                $this->getPercorsoDaId($ris['id_percorso']), 
                $this->getAziendaDaId($ris['id_azienda']), 
                $this->getAgenziaDaId($ris['id_agenzia']),
                $this->getFamigliaDaId($ris['id_famiglia']),
                $ris['dal'],
                $ris['al']
            );
        }
        return $esperienza;
    }
    
    /**
     * getEsperienzeDaAgenzia estrae dal database tutte le esperienze
     * associate ad un'agenzia.
     * 
     * @param  Agenzia $agenzia agenzia per la quale estrarre le esperienze
     * @return Agenzia[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getEsperienzeDaAgenzia($agenzia){
        $query="SELECT * FROM esperienze WHERE id_agenzia = {$agenzia->getId()} ORDER BY dal DESC";
        $ris = $this->connessione->query($query);
        $esperienze = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach($ris as $esperienza){
                $esperienze[] = new Esperienza(
                    $esperienza['id'],
                    $this->getStudenteDaId($esperienza['id_studente']),
                    $this->getPercorsoDaId($esperienza['id_percorso']),
                    $this->getAziendaDaId($esperienza['id_azienda']),
                    $agenzia,
                    $this->getFamigliaDaId($esperienza['id_famiglia']),
                    $esperienza['dal'],
                    $esperienza['al']
                );
            }
        } 
        return $esperienze;
    }

    /**
     * getEsperienzeDaStudente estrae dal database tutte le esperienze associate a uno studente.
     *
     * @param  Studente $studente studente del quale estrarre le esperienze
     * @return Esperienza[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getEsperienzeDaStudente($studente) {
        $query = "SELECT * FROM esperienze WHERE id_studente = {$studente->getId()} ORDER BY dal DESC";
        $ris = $this->connessione->query($query);
        $esperienze = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $esperienza) {
                $esperienze[] = new Esperienza(
                    $esperienza['id'],
                    $studente,
                    $this->getPercorsoDaId($esperienza['id_percorso']),
                    $this->getAziendaDaId($esperienza['id_azienda']),
                    $this->getAgenziaDaId($esperienza['id_agenzia']),
                    $this->getFamigliaDaId($esperienza['id_famiglia']),
                    $esperienza['dal'],
                    $esperienza['al']
                );
            }
        }
        return $esperienze;
    }

    /**
     * getEsperienzeDaAzienda estrae dal database tutte le esperienze associate ad un'azienda.
     *
     * @param  Azienda $azienda azienda della quale estrarre le esperienze
     * @return Esperienza[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getEsperienzeDaAzienda($azienda) {
        $query = "SELECT * FROM esperienze WHERE id_azienda = {$azienda->getId()} ORDER BY dal DESC";
        $ris = $this->connessione->query($query);
        $esperienze = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $esperienza) {
                $esperienze[] = new Esperienza(
                    $esperienza['id'],
                    $this->getStudenteDaId($esperienza['id_studente']),
                    $this->getPercorsoDaId($esperienza['id_percorso']),
                    $azienda,
                    $this->getAgenziaDaId($esperienza['id_agenzia']),
                    $this->getFamigliaDaId($esperienza['id_famiglia']),
                    $esperienza['dal'],
                    $esperienza['al']
                );
            }
        }
        return $esperienze;
    }

    /**
     * getPercorsoDaId estrae dal database il percorso associato all'id specificato.
     *
     * @param  int $id id del percorso da estrarre
     * @return Percorso se è stato trovato, altrimenti null
     */
    public function getPercorsoDaId($id) {
        $query = "SELECT * FROM percorsi WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $percorso = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $percorso = new Percorso(
                $id,
                $this->getDocenteDaId($ris['id_docente']),
                $this->getClasseDaId($ris['id_classe']),
                $ris['dal'],
                $ris['al']
            );
        }
        return $percorso;
    }

    /**
     * getPercorsiDaDocente estrae dal database tutti i percorsi di 
     * PCTO ed Erasmus associati a un Docente.
     *
     * @param  Docente $docente docente per il quale estrarre i percorsi
     * @return Percorso[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getPercorsiDaDocente($docente) {
        $query = "SELECT * FROM percorsi WHERE id_docente = {$docente->getId()} ORDER BY dal DESC";
        $ris = $this->connessione->query($query);
        $percorsi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $percorso) {
                $percorsi[] = new Percorso(
                    $percorso['id'],
                    $docente,
                    $this->getClasseDaId($percorso['id_classe']),
                    $percorso['dal'],
                    $percorso['al']
                );
            }
        }
        return $percorsi;
    }
    
    /**
     * getPercorsiStudente estrae dal database tutti i percorsi di 
     * PCTO ed Erasmus ai quali ha pertecipato uno studente.
     *
     * @param  Studente $studente studente per il quale estrarre i percorsi
     * @return Percorso[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getPercorsiDaStudente($studente) {
        $query =<<<testo
        SELECT P.* FROM studenti S
            INNER JOIN classi_studenti CS
                ON S.id = CS.id_studente
            INNER JOIN classi C
                ON CS.id_classe = C.id
            INNER JOIN percorsi P
                ON CS.id_classe = P.id_classe
        WHERE S.id = {$studente->getId()}
        ORDER BY P.dal DESC
        testo;
        $ris = $this->connessione->query($query);
        $percorsi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $percorso) {
                $percorsi[] = new Percorso(
                    $percorso['id'],
                    $this->getDocenteDaId($percorso['id_docente']),
                    $this->getClasseDaId($percorso['id_classe']),
                    $ris['dal'],
                    $ris['al']
                );
            }
        }
        return $percorsi;
    }
    
    /**
     * getPercorsiDaScuola estrae tutti i percorsi effettuati dalle classe di una scuola.
     *
     * @param  Scuola $scuola scuola perla quale estrarre i percorsi
     * @return Percorso[] se ne sono stati trovati, altrimenti un array vuoto
     */
    public function getPercorsiDaScuola($scuola) {
        $query =<<<testo
        SELECT P.* FROM percorsi P
            INNER JOIN classi C
            ON C.id = P.id_classe
        WHERE C.codice_scuola = '{$scuola->getId()}'
        ORDER BY P.dal DESC
        testo;
        $ris = $this->connessione->query($query);
        $percorsi = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $percorso) {
                $percorsi[] = new Percorso(
                    $percorso['id'],
                    $this->getDocenteDaId($percorso['id_docente']),
                    $this->getClasseDaId($percorso['id_classe']),
                    $percorso['dal'],
                    $percorso['al']
                );
            }
        }
        return $percorsi;
    }
        
    /**
     * getAspettoDaId estrae dal database l'aspetto associato
     * all'id specificato.
     *
     * @param  int $id id dell'aspetto da estrarre
     * @return Aspetto se è stato trovato, altrimenti null
     */
    public function getAspettoDaId($id) {
        $query = "SELECT * FROM aspetti WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $aspetto = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $aspetto = new Aspetto(
                $ris['id'],
                $ris['nome']
            );
        }
        return $aspetto;
    }

    /**
     * getAspettiDaModello estrae dal database tutti gli aspetti
     * associati ad un modello.
     *
     * @param  int $idModello id del modello per il quale estarre gli aspetti 
     * @return Aspetto[] se no sono stati trovati, altrimenti un array vuoto
     */
    public function getAspettiDaModello($idModello) {
        $query =<<<testo
        SELECT A.*
        FROM aspetti A
            INNER JOIN modelli_aspetti MA
            ON A.id = MA.id_aspetto
            INNER JOIN modelli M
            ON MA.id_modello = M.id
        WHERE M.id = {$idModello}
        testo;
        $ris = $this->connessione->query($query);
        $aspetti = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $aspetto) {
                $aspetti[] = new Aspetto(
                    $aspetto['id'],
                    $aspetto['nome']
                );
            }
        }
        return $aspetti;
    }

    /**
     * getModelloDaId estrae dal database un modello dal quale
     * è possibile creare una scheda di valutazione.
     *
     * @param  int $id id del modello da estrarre
     * @return ModelloSchedaDiValutazione se è stato trovato, altrimenti null
     */
    public function getModelloDaId($id) {
        $query .= "SELECT * FROM modelli WHERE id = {$id}";
        $ris = $this->connessione->query($query);
        $modello = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $modello = new ModelloSchedaDiValutazione(
                $ris['id'],
                $ris['tipo_recensore'],
                $ris['tipo_recensito'],
                $this->getAspettiDaModello($id)
            );
        }
        return $modello;
    }
    
    /**
     * getModelloDaTipi estrae dal database un modello in base al
     * tipo del soggetto recensore e del soggetto recensito.
     *  
     * @see    tabella tipi_utenti per informazioni circa i valori possibili 
     *          per i parametri 
     * 
     * @param  string $tipoRecensore tipo del soggetto recensore
     * @param  string $tipoRecensito tipo del soggetto recensito
     * 
     * @return ModelloSchedaDiValutazione se è stato trovato, altrimenti null
     */
    public function getModelloDaTipi($tipoRecensore, $tipoRecensito) {
        $query =<<<testo
        SELECT * FROM modelli
        WHERE tipo_recensore = "{$tipoRecensore}"
            AND tipo_recensito = "{$tipoRecensito}"
        testo;
        $ris = $this->connessione->query($query);
        $modello = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $modello = new ModelloSchedaDiValutazione(
                $ris['id'],
                $tipoRecensore,
                $tipoRecensito,
                $this->getAspettiDaModello($ris['id'])
            );
        }
        return $modello;
    }
    
    /**
     * getSchedaDiValutazioneDaId estrae dal database la scheda di
     * valutazione assciata all'id specificato.
     *
     * @param  int $id id della scheda di valutazione da estrarre
     * @return SchedaDiValutazione se è stata trovata, altrimenti null
     */
    public function getSchedaDiValutazioneDaId($id) {
        $query =<<<testo
        SELECT S.*, M.tipo_recensore, M.tipo_recensito
        FROM schede_di_valutazione S
            INNER JOIN modelli M
            ON S.id_modello = M.id
        WHERE S.id = {$id}
        testo;
        $ris = $this->connessione->query($query);
        $schedaDiValutazione = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $schedaDiValutazione = new SchedaDiValutazione(
                $id,
                $ris['tipo_recensore'],
                $ris['id_recensore'],
                $ris['tipo_recensito'],
                $ris['id_recensito'],
                $this->getEsperienzaDaId($ris['id_esperienza']),
                $ris['data_ora'],
                $this->getValutazioniDaSchedaDiValutazione($id)
            );
        }
        return $schedaDiValutazione;
    }

    public function getSchedaDiValutazioneDaSoggetti($modello, $idRecensore, $idRecensito, $esperienza) {
        $query =<<<testo
        SELECT S.*
        FROM schede_di_valutazione S
            INNER JOIN modelli M
            ON S.id_modello = M.id
        WHERE S.id_esperienza = {$esperienza->getId()}
            AND S.id_recensito = {$idRecensito}
            AND S.id_recensore = {$idRecensore}
            AND S.id_modello = {$modello->getId()}
        testo;
        $ris = $this->connessione->query($query);
        $schedaDiValutazione = null;
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc();
            $schedaDiValutazione = new SchedaDiValutazione(
                $ris['id'],
                $modello->getTipoRecensore(),
                $idRecensore,
                $modello->getTipoRecensito(),
                $idRecensito,
                $esperienza,
                $ris['data_ora'],
                $this->getValutazioniDaSchedaDiValutazione($ris['id'])
            );
        }
        return $schedaDiValutazione;
    }
    
    /**
     * getValutazioniDaSchedaDiValutazione estrae dal database tutte le
     * valutazioni associate alla scheda di valutazione specificata.
     *
     * @param  int $id id della scheda di valutazione per la quale estrarre 
     *              le valutazioni
     * @return Valutazione[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getValutazioniDaSchedaDiValutazione($id) {
        $query ="SELECT * FROM valutazioni WHERE id_scheda_di_valutazione = {$id}";
        $ris = $this->connessione->query($query);
        $valutazioni = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $valutazione) {
                $valutazioni[] = new Valutazione(
                    $valutazione['id'],
                    $valutazione['voto'],
                    $this->getAspettoDaId($valutazione['id_aspetto'])
                );
            }
        }
        return $valutazioni;
    }

    /**
     * getValutazioniMedieDiAgenzia estrae dal database le valutazioni
     * medie di un'agenzia.
     *
     * @param  ModelloSchedaDiValutazione $modello modello associato alle
     *                  schede di valutazione per le agenzia.
     * @param  Agenzia $agenzia agenzia per la quale estrarre le valutazioni
     *                  medie
     * @return Valutazione[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getValutazioniMedieDiAgenzia($modello, $agenzia) {
        $query =<<<testo
        SELECT id, id_aspetto, AVG(voto) AS voto_medio FROM (
            SELECT V.id, A.id AS id_aspetto, V.voto FROM valutazioni V
                INNER JOIN schede_di_valutazione S
                ON V.id_scheda_di_valutazione = S.id
                INNER JOIN modelli M
                ON S.id_modello = M.id
                INNER JOIN aspetti A
                ON V.id_aspetto = A.id
            WHERE M.id = {$modello->getId()}
                AND S.id_recensito = {$agenzia->getId()}
        ) AS valutazioni_aspetti
        GROUP BY id_aspetto
        testo;
        $ris = $this->connessione->query($query);
        $valutazioniMedie = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $valutazione) {
                $valutazioniMedie[] = new Valutazione(
                    $valutazione['id'],
                    $valutazione['voto_medio'],
                    $this->getAspettoDaId($valutazione['id_aspetto'])
                );
            }
        }
        return $valutazioniMedie;
    }
    
    /**
     * getValutazioniMedieDiAzienda estrae dal database le valutazioni
     * medie di un'azienda.
     *
     * @param  ModelloSchedaDiValutazione $modello modello associato alle
     *                  schede di valutazione per le aziende.
     * @param  Azienda $azienda azienda per la quale estrarre le valutazioni
     *                  medie
     * @return Valutazione[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getValutazioniMedieDiAzienda($modello, $azienda) {
        $query =<<<testo
        SELECT id, id_aspetto, AVG(voto) AS voto_medio FROM (
            SELECT V.id, A.id AS id_aspetto, V.voto FROM valutazioni V
                INNER JOIN schede_di_valutazione S
                ON V.id_scheda_di_valutazione = S.id
                INNER JOIN modelli M
                ON S.id_modello = M.id
                INNER JOIN aspetti A
                ON V.id_aspetto = A.id
            WHERE M.id = {$modello->getId()}
                AND S.id_recensito = {$azienda->getId()}
        ) AS valutazioni_aspetti
        GROUP BY id_aspetto
        testo;
        $ris = $this->connessione->query($query);
        $valutazioniMedie = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $valutazione) {
                $valutazioniMedie[] = new Valutazione(
                    $valutazione['id'],
                    $valutazione['voto_medio'],
                    $this->getAspettoDaId($valutazione['id_aspetto'])
                );
            }
        }
        return $valutazioniMedie;
    }
    
    /**
     * getValutazioniMedieDiFamiglia estrae dal database le valutazioni
     * medie di una famigia.
     *
     * @param  ModelloSchedaDiValutazione $modello modello associato alle
     *                  schede di valutazione per le famiglie.
     * @param  Famiglia $famiglia famiglia per la quale estrarre le valutazioni
     *                  medie
     * @return Valutazione[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getValutazioniMedieDiFamiglia($modello, $famiglia) {
        $query =<<<testo
        SELECT id, id_aspetto, AVG(voto) AS voto_medio FROM (
            SELECT V.id, A.id AS id_aspetto, V.voto FROM valutazioni V
                INNER JOIN schede_di_valutazione S
                ON V.id_scheda_di_valutazione = S.id
                INNER JOIN modelli M
                ON S.id_modello = M.id
                INNER JOIN aspetti A
                ON V.id_aspetto = A.id
            WHERE M.id = {$modello->getId()}
                AND S.id_recensito = {$famiglia->getId()}
        ) AS valutazioni_aspetti
        GROUP BY id_aspetto
        testo;
        $ris = $this->connessione->query($query);
        $valutazioniMedie = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $valutazione) {
                $valutazioniMedie[] = new Valutazione(
                    $valutazione['id'],
                    $valutazione['voto_medio'],
                    $this->getAspettoDaId($valutazione['id_aspetto'])
                );
            }
        }
        return $valutazioniMedie;
    }
    
    /**
     * getEsperienzeDaPercorso estrae le esperienze associate ad
     * un percorso.
     *
     * @param  int $id id del percorso per il quale estrarre le esperienze
     * @return Esperienza[] se ne sono state trovato, altrimenti un array vuoto
     */
    public function getEsperienzeDaPercorso($id){
        $query=<<<testo
            SELECT id FROM esperienze WHERE id_percorso = {$id}
        testo;
        $ris = $this->connessione->query($query);
        $esperienze = array();
        while(($riga = $ris->fetch_row()) != null){
            $esperienze[] = $this->getEsperienzaDaId($riga[0]);
        }
        return $esperienze;
    }    

    /**
     * getValutazioniMedieDiStudenti estrae dal database le valutazioni
     * medie di uno studente.
     *
     * @param  ModelloSchedaDiValutazione $modello modello associato alle
     *                  schede di valutazione per gli studenti.
     * @param  Studente $studente studente per la quale estrarre le valutazioni
     *                  medie
     * @return Valutazione[] se ne sono state trovate, altrimenti un array vuoto
     */
    public function getValutazioniMedieDiStudente($modello, $studente) {
        $query =<<<testo
        SELECT id, id_aspetto, AVG(voto) AS voto_medio FROM (
            SELECT V.id, A.id AS id_aspetto, V.voto FROM valutazioni V
                INNER JOIN schede_di_valutazione S
                ON V.id_scheda_di_valutazione = S.id
                INNER JOIN modelli M
                ON S.id_modello = M.id
                INNER JOIN aspetti A
                ON V.id_aspetto = A.id
            WHERE M.id = {$modello->getId()}
                AND S.id_recensito = {$studente->getId()}
        ) AS valutazioni_aspetti
        GROUP BY id_aspetto
        testo;
        $ris = $this->connessione->query($query);
        $valutazioniMedie = array();
        if($ris && $ris->num_rows > 0){
            $ris = $ris->fetch_all(MYSQLI_ASSOC);
            foreach ($ris as $valutazione) {
                $valutazioniMedie[] = new Valutazione(
                    $valutazione['id'],
                    $valutazione['voto_medio'],
                    $this->getAspettoDaId($valutazione['id_aspetto'])
                );
            }
        }
        return $valutazioniMedie;
    }

    /**
     * verificaCredenziali verifica che la coppia email e password sia valida.
     *
     * @param  string $email indirizzo email
     * @param  string $password password
     * @return string tipo di utente identificato, altrimenti false
     */
    public function verificaCredenziali($email, $password) {
        $query = "SELECT tipo_utente FROM utenti WHERE email = '{$email}' AND password = '{$password}'";
        $ris = $this->connessione->query($query);

        if($ris != false && $ris->num_rows == 1){
            $ris = $ris->fetch_assoc()['tipo_utente'];
        }else{
            $ris = false;
        }
        return $ris;
    }

    /**
     * modificaPassword modifica la password di un utente.
     *
     * @param  string $email email associate all'utente del quale cambiare la password
     * @param  string $password digest della password modificata
     * @return bool true se la modifica è andata a buon fine, altrimenti false
     */
    public function modificaPassword($email, $password) {
        $query = "UPDATE utenti SET password='{$password}' WHERE email='$email'";
        return $this->connessione->query($query);
    }
        
    /**
     * modificaEmail modifica l'email associata ad un utente.
     *
     * @param  string $vecchiaEmail email associata all'utente del quale cambiare l'email
     * @param  string $nuovaEmail nuova email da associare
     * @return bool true se la modifica è andata a buon fine, altrimenti false
     */
    public function modificaEmail($vecchiaEmail, $nuovaEmail) {
        $query = "UPDATE utenti SET email='{$nuovaEmail}' WHERE email='{$vecchiaEmail}'";
        return $this->connessione->query($query);
    }
    
    public function modificaScuola($scuola) {
        $query =<<<testo
            UPDATE scuole 
            SET 
                nome = "{$scuola->getNome()}",
                citta = "{$scuola->getCitta()}",     
                indirizzo = "{$scuola->getIndirizzo()}",     
            WHERE codice_meccanografico = "{$scuola->getId()}";
        testo;
        return $this->connessione->query($query);
    }

    public function modificaPercorso($percorso) {
        $query =<<<testo
            UPDATE percorsi 
            SET 
                id_docente = "{$percorso->getDocente()->getId()}",
                id_classe = "{$percorso->getClasse()->getId()}",     
                dal = "{$percorso->getDal()}",
                al = "{$percorso->getAl()}"     
            WHERE id = "{$percorso->getId()}"
        testo;
        return $this->connessione->query($query);
    }
    public function modificaEsperienza($esperienza){
    }
    /**
     * insertAgenzia inserisce un'agenzia nel database.
     *
     * @param  Agenzia $agenzia istanza della classe agenzia da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertAgenzia($agenzia) {
        $query =<<<testo
        INSERT INTO agenzie (nome, email_utente, stato, citta, indirizzo, telefono) VALUES (
            "{$agenzia->getNome()}",
            "{$agenzia->getEmail()}",
            "{$agenzia->getStato()}",
            "{$agenzia->getCitta()}",
            "{$agenzia->getIndirizzo()}",
            "{$agenzia->getTelefono()}"
        testo;
        return $this->connessione->query($query);
    }

    /**
     * insertAzienda inserisce un'azienda nel database.
     *
     * @param  Azienda $azienda istanza della classe azienda da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertAzienda($azienda) {
        $query =<<<testo
        INSERT INTO agenzie (nome, email_utente, stato, citta, indirizzo, telefono) VALUES (
            "{$azienda->getNome()}",
            "{$azienda->getEmail()}",
            "{$azienda->getStato()}",
            "{$azienda->getCitta()}",
            "{$azienda->getIndirizzo()}",
            "{$azienda->getTelefono()}"
        )
        testo;
        return $this->connessione->query($query);
    }

    /**
     * insertDocente inserisce un docente nel database.
     *
     * @param  Docente $docente istanza della classe docente da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertDocente($docente, $scuola, $dal, $al) {
        $digest = hash('sha256', $docente->getNome());
        $query =<<<testo
        SELECT inserisci_docente (
            "{$scuola->getId()}",
            "{$docente->getEmail()}",
            "{$digest}",
            "{$docente->getNome()}",
            "{$docente->getCognome()}",
            "{$dal}",\n
        testo;
        if($al == null){
            $query .=<<<testo
                NULL
            );
            testo;
        }else{
            $query .=<<<testo
                "{$al}"
            );    
            testo;
        }

        $ris = $this->connessione->query($query);
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_row();
            if($ris[0] == 0){
                return true;
            }
        }
        return false;
    }

    /**
     * insertFamiglia inserisce una famiglia nel database.
     *
     * @param  Famiglia $famiglia istanza della classe famiglia da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertFamiglia($famiglia) {
        $query =<<<testo
        INSERT INTO agenzie (nome, cognome, stato, citta, indirizzo) VALUES (
            "{$famiglia->getNome()}",
            "{$famiglia->getCognome()}",
            "{$famiglia->getStato()}",
            "{$famiglia->getCitta()}",
            "{$famiglia->getIndirizzo()}"
        )
        testo;
        return $this->connessione->query($query);
    }

    /**
     * insertStudente inserisce uno studente nel database.
     *
     * @param  Studente $studente istanza della classe studente da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertStudente($studente) {
        $query =<<<testo
        INSERT INTO agenzie (nome, cognome, email_utente, data_nascita) VALUES (
            "{$studente->getNome()}",
            "{$studente->getCognome()}",
            "{$studente->getEmail()}",
            "{$studente->getDataNascita()}"
        )
        testo;
        return $this->connessione->query($query);
    } 
    
    /**
     * insertScuola inserisce una scuola nel database.
     *
     * @param  Scuola $scuola istanza della classe scuola da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertScuola($scuola) {
        $digest = hash('sha256', $scuola->getNome());
        $query =<<<testo
        SELECT inserisci_scuola (
            "{$scuola->getId()}",
            "{$scuola->getEmail()}",
            "{$scuola->getNome()}",
            "{$scuola->getCitta()}",
            "{$scuola->getIndirizzo()}",
            "{$digest}"
        );
        testo;

        $ris = $this->connessione->query($query);
        if($ris && $ris->num_rows == 1){
            $ris = $ris->fetch_row();
            if($ris[0] == 0){
                return true;
            }
        }
        return false;
    }
    
    /**
     * insertSchedaDiValutazione inserisce una scheda di valutazione 
     * compilata nel database.
     *
     * @param  SchedaDiValutazione $scheda scheda di valutazione da inserire
     * @param  ModelloSchedaDiValutazione $modello modello di riferimento per la schedaa
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertSchedaDiValutazione($scheda, $modello) {
        $query =<<<testo
        INSERT INTO schede_di_valutazione (
            id_modello, 
            id_recensore, 
            id_recensito, 
            id_esperienza, 
            data_ora
        ) VALUES (
            {$modello->getId()},
            {$scheda->getIdRecensore()},
            {$scheda->getIdRecensito()},
            {$scheda->getEsperienza()->getId()},
            '{$scheda->getDataOra()}'
        );
        testo;
        $ris = $this->connessione->query($query);
        if(!$ris){
            return false;
        }

        $query =<<<testo
        SELECT id FROM schede_di_valutazione 
        WHERE id_modello = {$modello->getId()}
            AND id_recensore = {$scheda->getIdRecensore()}
            ANd id_recensito = {$scheda->getIdRecensito()}
            AND id_esperienza = {$scheda->getEsperienza()->getId()}
        testo;
        $ris = $this->connessione->query($query);
        if(!$ris || $ris->num_rows != 1){
            return false;
        }

        $id = intval($ris->fetch_row()[0]);
        $query =<<<testo
        INSERT INTO valutazioni (
            id_scheda_di_valutazione,
            voto,
            id_aspetto
        ) VALUES
        testo;
        foreach ($scheda->getValutazioni() as $valutazione) {
            $query .=<<<testo
            (
                {$id},
                {$valutazione->getVoto()},
                {$valutazione->getAspetto()}
            ),
            testo;
        }
        $query = substr($query, 0, strlen($query) - 1);
        return $this->connessione->query($query);
    }

    /**
     * insertUtenteScuola inserisce l'utente scuola nella tabella utenti del db
     * @param email email dell'utente
     * @param password la password dell'utente
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertUtente($email,$password,$tipoUtente) {
        $query =<<<testo
        INSERT INTO utenti (
            email,
            password,
            tipo_utente
        ) VALUES (
            "{$email}",
            "{$password}",
            "$tipoUtente"
        )
        testo;
        return $this->connessione->query($query);
    }    
    /**
     * insertClasse inserisce una nuova classe nel database
     * 
     * @param  Classe $classe classe da inserire
     * @return mixed l'id della classe se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertClasse($classe){
        $query =<<<testo
        INSERT INTO classi (codice_scuola, numero, sezione, anno_scolastico) VALUES (
            "{$classe->getScuola()}",
            {$classe->getNumero()},
            "{$classe->getSezione()}",
            "{$classe->getAnnoScolastico()}"
        )
        testo;

        $ris = $this->connessione->query($query);
        if($ris){
            $query=<<<testo
            SELECT id FROM classi WHERE codice_scuola="{$classe->getScuola()}"
                AND numero = {$classe->getNumero()}
                AND sezione = "{$classe->getSezione()}"
                AND anno_scolastico="{$classe->getAnnoScolastico()}"
            testo;
            $ris = $this->connessione->query($query);
            if($ris && $ris->num_rows == 1){
                $ris = intval($ris->fetch_row()[0]);
            }
        } 
        return $ris;
    }
    
    /**
     * insertStudenteInClasse inserisce uno studente in una classe
     *
     * @param  int $idClasse id della classe nella quale inserire lo studente
     * @param  int $idStudente id dello studente da inserire
     * @param  string $dal data in formato YYYY-MM-DD
     * @param  string $al data in formato YYYY-MM-DD
     * @return bool true se lo studente è stato inserito altrimenti false
     */
    public function insertStudenteInClasse($idClasse, $idStudente, $dal, $al) {
        $query =<<<testo
        INSERT INTO classi_studenti (id_studente,id_classe,dal,al) VALUES (
            {$idStudente}, {$idClasse}, "{$dal}", 
        testo;
        if($al == null){
            $query .= "NULL)";
        }else{
            $query .= "'{$al}'";
        }
        return $this->connessione->query($query);
    }

    /**
     * insertDocenteInClasse assegna un docente a una classe
     *
     * @param  int $idClasse id della classe alla quale assegnare il docente
     * @param  int $idDocente id del docente da assegnare
     * @param  string $dal data in formato YYYY-MM-DD
     * @param  string $al data in formato YYYY-MM-DD
     * @return bool true se il docente è stato inserito altrimenti false
     */
    public function insertiDocenteInClasse($idClasse, $idDocente, $dal, $al) {
        $query =<<<testo
        INSERT INTO classi_docenti (id_docente, id_classe, dal, al) VALUES (
            {$idDocente}, {$idClasse}, "{$dal}", 
        testo;
        if($al == null){
            $query .= "NULL)";
        }else{
            $query .= "'{$al}')";
        }
        return $this->connessione->query($query);
    }

    /**
     * insertPercorso inserisce un percorso nel database.
     *
     * @param  Percorso $percorso istanza della classe percorso da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertPercorso($percorso) {
        $query =<<<testo
        INSERT INTO percorsi (id_docente, id_classe, dal, al) VALUES (
            "{$percorso->getDocente()->getId()}",
            "{$percorso->getClasse()->getId()}",
            "{$percorso->getDal()}",
            "{$percorso->getAl()}"
        )
        testo;
        return $this->connessione->query($query);
    }
    /**
     * insertEsperienza inserisce un'esperienza nel database.
     *
     * @param  Esperienza $esperienza istanza della classe esperienza da inserire
     * @return bool true se l'inserimento è andato a buon fine, altrimenti false
     */
    public function insertEsperienza($esperienza) {
        $query =<<<testo
        INSERT INTO esperienze (id_studente, id_azienda, id_percorso, id_agenzia, id_famiglia, dal, al) VALUES (
            "{$esperienza->getStudente()}",
            "{$esperienza->getAzienda()}",
            "{$esperienza->getPercorso()}",
        testo;
        if($esperienza->getAgenzia() != "null" && $esperienza->getFamiglia() != "null"){
            $query.=<<<testo
            "{$esperienza->getAgenzia()}",
            "{$esperienza->getFamiglia()}",
            testo;
        }else{
            $query.=<<<testo
            null,
            null,
            testo;
        }
        $query.=<<<testo
            "{$esperienza->getDal()}",
            "{$esperienza->getAl()}"
        )
        testo;
        return $this->connessione->query($query);
    }
}
?>