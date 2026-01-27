<?php
/**
 * H.A.C. Renovation - User Model
 */

class User
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * Obtener todos los usuarios
     */
    public static function all()
    {
        $repo = new UserRepository();
        return $repo->findAll([], 'created_at DESC');
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new UserRepository();
        return $repo->findById($id);
    }

    /**
     * Buscar por username
     */
    public static function findByUsername($username)
    {
        $repo = new UserRepository();
        return $repo->findByUsername($username);
    }

    /**
     * Buscar por email
     */
    public static function findByEmail($email)
    {
        $repo = new UserRepository();
        return $repo->findByEmail($email);
    }

    /**
     * Crear usuario
     */
    public static function create($data)
    {
        $repo = new UserRepository();
        
        // Hashear contraseña si existe
        if (isset($data['password'])) {
            $data['password'] = Auth::hashPassword($data['password']);
        }
        
        return $repo->create($data);
    }

    /**
     * Actualizar usuario
     */
    public static function update($id, $data)
    {
        $repo = new UserRepository();
        
        // Hashear contraseña si se está actualizando
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Auth::hashPassword($data['password']);
        } else {
            unset($data['password']);
        }
        
        return $repo->update($id, $data);
    }

    /**
     * Eliminar usuario
     */
    public static function delete($id)
    {
        $repo = new UserRepository();
        return $repo->delete($id);
    }

    /**
     * Autenticar usuario
     */
    public static function authenticate($username, $password)
    {
        $user = self::findByUsername($username);
        
        if (!$user || !$user['is_active']) {
            return false;
        }

        if (Auth::verifyPassword($password, $user['password'])) {
            // Actualizar último login
            $repo = new UserRepository();
            $repo->updateLastLogin($user['id']);
            
            return $user;
        }

        return false;
    }
}