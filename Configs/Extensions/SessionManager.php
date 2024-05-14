<?php
require_once "GenericExtensions.php";

class SessionManager {
    /**
     * Avvia una nuova sessione con le opzioni specificate.
     * 
     * @param string $session_name Il nome della sessione.
     * @param int $lifetime La durata della sessione in secondi.
     * @param string|null $path Il percorso del cookie della sessione.
     * @param string|null $domain Il dominio del cookie della sessione.
     * @param bool|null $secure Indica se il cookie deve essere inviato solo su una connessione HTTPS.
     * @param bool|null $httponly Indica se il cookie deve essere accessibile solo tramite HTTP.
     */
    public static function startSession(string $session_name = null, int $lifetime = 3600, string|null $path = '/', string|null $domain = null, bool|null $secure = null, bool|null $httponly = null): bool
    {
        try
        {
            if(!GenericExtensions::isNullOrEmptyString($session_name))
                session_name($session_name);
    
            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
            session_start();
            session_regenerate_id(true);
            return true;
        } 
        catch(Exception $e)
        {
            Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
            return false;
        }
    }

    /**
     * Imposta un valore nella sessione.
     * 
     * @param string $key La chiave del valore da impostare.
     * @param mixed $value Il valore da impostare.
     */
    public static function set(string $key, mixed $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Ottiene un valore dalla sessione.
     * 
     * @param string $key La chiave del valore da ottenere.
     * @return mixed Il valore corrispondente alla chiave, o null se non esiste.
     */
    public static function get($key) {
        return self::has($key) ? $_SESSION[$key] : null;
    }

    /**
     * Verifica se una chiave esiste nella sessione.
     * 
     * @param string $key La chiave da verificare.
     * @return bool True se la chiave esiste nella sessione, altrimenti false.
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina un valore dalla sessione.
     * 
     * @param string $key La chiave del valore da eliminare.
     */
    public static function delete($key) {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Termina la sessione corrente.
     */
    public static function endSession() {
        $_SESSION = array();
        session_destroy();
    }
}