<?php
/**
 * H.A.C. Renovation - Helper
 * Funciones reutilizables para toda la aplicación
 */

class Helper
{
    /**
     * ============================================
     * FUNCIONES DE FORMATEO DE FECHAS
     * ============================================
     */

    /**
     * Formatea una fecha con formato personalizable
     * 
     * @param string|int|null $date Fecha en formato string, timestamp o null
     * @param string $format Formato de fecha (por defecto 'd/m/Y')
     * @param string|null $default Valor por defecto si la fecha es inválida
     * @return string Fecha formateada o valor por defecto
     */
    public static function formatDate($date, $format = 'd/m/Y', $default = null)
    {
        if (empty($date)) {
            return $default ?? '';
        }

        $timestamp = is_numeric($date) ? $date : strtotime($date);
        
        if ($timestamp === false) {
            return $default ?? '';
        }

        return date($format, $timestamp);
    }

    /**
     * Formatea una fecha en formato largo (ej: "Jan 26, 2026")
     * 
     * @param string|int|null $date Fecha en formato string, timestamp o null
     * @param string|null $default Valor por defecto si la fecha es inválida
     * @return string Fecha formateada o valor por defecto
     */
    public static function formatDateLong($date, $default = null)
    {
        return self::formatDate($date, 'M d, Y', $default);
    }

    /**
     * Formatea una fecha en formato corto (ej: "26/01/2026")
     * 
     * @param string|int|null $date Fecha en formato string, timestamp o null
     * @param string|null $default Valor por defecto si la fecha es inválida
     * @return string Fecha formateada o valor por defecto
     */
    public static function formatDateShort($date, $default = null)
    {
        return self::formatDate($date, 'd/m/Y', $default);
    }

    /**
     * Formatea una fecha con hora
     * 
     * @param string|int|null $date Fecha en formato string, timestamp o null
     * @param string $format Formato de fecha con hora (por defecto 'd/m/Y H:i')
     * @param string|null $default Valor por defecto si la fecha es inválida
     * @return string Fecha y hora formateada o valor por defecto
     */
    public static function formatDateTime($date, $format = 'd/m/Y H:i', $default = null)
    {
        return self::formatDate($date, $format, $default);
    }
}
