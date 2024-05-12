<?php 

class Extensions {

    /**
     * Tenta di convertire il valore fornito in un intero e lo restituisce per riferimento.
     * 
     * Questo metodo verifica se il valore fornito è numerico e tenta di convertirlo in un intero.
     * Se la conversione riesce, viene restituito il valore intero. In caso contrario, la funzione restituisce null.
     * 
     * @param string $value Il valore da convertire in un intero.
     * @return int | null Restituisce il valore intero se la conversione ha successo, altrimenti restituisce null.
     */
    public static function tryParseInt(string $value) {
        return is_numeric($value) ? (int)$value : null;
    }

    /**
     * Formatta una data da un formato all'altro.
     * 
     * @param string $date La data da formattare.
     * @param string $formatFrom Il formato attuale della data.
     * @param string $formatTo Il formato desiderato della data.
     * @return string La data formattata nel nuovo formato.
     * @throws Exception Se la data fornita non è nel formato specificato.
     *
     * **Esempio di utilizzo:**
     * 
     *try {
     *
     *     $newDate = Extensions::formatDate("2022-01-15", "Y-m-d", "d/m/Y");
     * 
     *     echo "Data formattata: $newDate"; // Output: Data formattata: 15/01/2022
     * 
     * } catch (Exception $e) {
     * 
     *     echo "Errore: " . $e->getMessage();
     * 
     * }
     */
    public static function formatDate($date, $formatFrom, $formatTo) {
        $dateTime = DateTime::createFromFormat($formatFrom, $date);
        if ($dateTime === false)
            throw new Exception("Formato della data non valido: $formatFrom");

        return $dateTime->format($formatTo);
    }

    /**
     * Controlla se una stringa sia vuota oppure null.
     * @param string|null $value La stringa da controllare.
     */
    public static function isNullOrEmptyString(string|null $value): bool
    {
        return $value == null || strlen($value) == 0;
    }
}