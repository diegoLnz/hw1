<?php

class DbMonad {
    private $value;
    private $errors = [];

    /**
     * Costruttore privato per creare una nuova istanza di DbMonad.
     * @param mixed $value Il valore da incapsulare e manipolare.
     */
    private function __construct($value) {
        $this->value = $value;
    }

    /**
     * Crea un nuovo Monad che incapsula il valore fornito.
     * @param mixed $value Il valore da incapsulare e manipolare.
     * @return DbMonad Il nuovo Monad con il valore fornito.
     */
    public static function unit($value) {
        return new self($value);
    }

    /**
     * Applica una funzione al valore incapsulato nel Monad e restituisce un nuovo Monad con il risultato.
     * @param callable $function La funzione da applicare al valore incapsulato.
     * @return DbMonad Il nuovo Monad con il risultato della funzione applicata.
     */
    public function bind(callable $function) {
        if ($this->hasErrors())
            return $this;

        try
        {
            return new self($function($this->value));
        } 
        catch (Exception $e) 
        {
            $this->errors[] = $e->getMessage();
            return $this;
        }
    }

    /**
     * Restituisce il valore incapsulato nel Monad.
     * @return mixed Il valore incapsulato nel Monad.
     * @throws Exception Se sono presenti errori nel Monad.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Verifica se ci sono errori nel Monad.
     * @return bool True se ci sono errori, altrimenti False.
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Restituisce un array contenente gli errori nel Monad.
     * @return array Gli errori presenti nel Monad.
     */
    public function getErrors() {
        return $this->errors;
    }
}