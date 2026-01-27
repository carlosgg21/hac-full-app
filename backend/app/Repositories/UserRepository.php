<?php
/**
 * H.A.C. Renovation - UserRepository
 */

class UserRepository extends BaseRepository
{
    protected $table = 'users';

    /**
     * Buscar usuario por username
     */
    public function findByUsername($username)
    {
        return $this->findOne(['username' => $username]);
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email)
    {
        return $this->findOne(['email' => $email]);
    }

    /**
     * Actualizar último login
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Obtener usuarios activos
     */
    public function findActive()
    {
        return $this->findAll(['is_active' => 1], 'created_at DESC');
    }
}