<?php
/**
 * H.A.C. Renovation - Database Class
 * Singleton para manejo de conexión PDO
 */

class Database
{
    private static $instance = null;
    private $connection = null;
    private $config = null;

    /**
     * Constructor privado para singleton
     */
    private function __construct()
    {
        $this->config = require CONFIG_PATH . '/database.php';
        $this->connect();
    }

    /**
     * Obtener instancia única
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establecer conexión a la base de datos
     */
    private function connect()
    {
        try {
            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $this->config['driver'],
                $this->config['host'],
                $this->config['port'],
                $this->config['database'],
                $this->config['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('Error de conexión a la base de datos');
        }
    }

    /**
     * Obtener conexión PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Ejecutar query y retornar resultados
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Database query error: ' . $e->getMessage());
            error_log('SQL: ' . $sql);
            error_log('Params: ' . print_r($params, true));
            throw new Exception('Error al ejecutar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Obtener todos los registros
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un registro
     */
    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Insertar registro y retornar ID
     */
    public function insert($table, $data)
    {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $this->query($sql, $data);
        return $this->connection->lastInsertId();
    }

    /**
     * Actualizar registro
     */
    public function update($table, $data, $where, $whereParams = [])
    {
        if (empty($data)) {
            return 0;
        }

        $set = [];
        foreach (array_keys($data) as $field) {
            // Escapar nombre de columna con backticks
            $set[] = '`' . str_replace('`', '``', $field) . '` = :' . $field;
        }

        // Escapar nombre de tabla con backticks
        $escapedTable = '`' . str_replace('`', '``', $table) . '`';

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $escapedTable,
            implode(', ', $set),
            $where
        );

        $params = array_merge($data, $whereParams);
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Eliminar registro
     */
    public function delete($table, $where, $params = [])
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Revertir transacción
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }

    /**
     * Prevenir clonación
     */
    private function __clone() {}

    /**
     * Prevenir deserialización
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}