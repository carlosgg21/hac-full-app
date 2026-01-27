<?php
/**
 * H.A.C. Renovation - JsonHelper
 * Funciones reutilizables para trabajar con JSON (especialmente campos JSON de MySQL)
 */

class JsonHelper
{
    /**
     * Decodifica un valor JSON a array/objeto con valor por defecto
     * 
     * @param string|null $jsonValue Valor JSON a decodificar
     * @param mixed $default Valor por defecto si el JSON es inválido o vacío
     * @param bool $assoc Si true, retorna array asociativo; si false, retorna objeto
     * @return mixed Array, objeto o valor por defecto
     */
    public static function decode($jsonValue, $default = [], $assoc = true)
    {
        // Si es null o string vacío, retornar valor por defecto
        if ($jsonValue === null || $jsonValue === '') {
            return $default;
        }

        // Si ya es un array u objeto, retornarlo directamente
        if (is_array($jsonValue) || is_object($jsonValue)) {
            return $assoc ? (array)$jsonValue : $jsonValue;
        }

        // Si no es string, intentar codificar primero
        if (!is_string($jsonValue)) {
            $jsonValue = json_encode($jsonValue);
        }

        // Decodificar JSON
        $decoded = json_decode($jsonValue, $assoc);

        // Si hay error en la decodificación, retornar valor por defecto
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $default;
        }

        // Si el resultado es null y no es el valor por defecto esperado, retornar valor por defecto
        if ($decoded === null && $default !== null) {
            return $default;
        }

        return $decoded;
    }

    /**
     * Codifica un valor a JSON string
     * 
     * @param mixed $value Valor a codificar (array, objeto, etc.)
     * @param int $flags Flags opcionales para json_encode
     * @return string|null JSON string o null si hay error
     */
    public static function encode($value, $flags = JSON_UNESCAPED_UNICODE)
    {
        // Si ya es string y es JSON válido, retornarlo
        if (is_string($value)) {
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }
        }

        // Si es null, retornar null
        if ($value === null) {
            return null;
        }

        // Codificar a JSON
        $encoded = json_encode($value, $flags);

        // Si hay error, retornar null
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $encoded;
    }

    /**
     * Decodifica múltiples campos JSON de un array de datos
     * 
     * @param array $data Array de datos que contiene campos JSON
     * @param array $fields Array de nombres de campos a decodificar
     * @param mixed $default Valor por defecto para campos inválidos
     * @return array Array con los campos JSON decodificados
     */
    public static function decodeFields($data, $fields, $default = [])
    {
        if (!is_array($data) || !is_array($fields)) {
            return $data;
        }

        $result = $data;

        foreach ($fields as $field) {
            if (isset($result[$field])) {
                $result[$field] = self::decode($result[$field], $default);
            }
        }

        return $result;
    }

    /**
     * Codifica múltiples campos a JSON en un array
     * 
     * @param array $data Array de datos con campos a codificar
     * @param array $fields Array de nombres de campos a codificar
     * @return array Array con los campos codificados a JSON
     */
    public static function encodeFields($data, $fields)
    {
        if (!is_array($data) || !is_array($fields)) {
            return $data;
        }

        $result = $data;

        foreach ($fields as $field) {
            if (isset($result[$field]) && (is_array($result[$field]) || is_object($result[$field]))) {
                $result[$field] = self::encode($result[$field]);
            }
        }

        return $result;
    }
}
