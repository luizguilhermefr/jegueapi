<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'email',
        'password',
        'username',
        'remember_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @param string $email
     * @return bool
     */
    public static function emailExists($email)
    {
        return self::where('email', $email)
                ->count() > 0;
    }

    /**
     * @param string $username
     * @return bool
     */
    public static function usernameExists($username)
    {
        return self::where('username', $username)
                ->count() > 0;
    }

    /**
     * @param string $email
     * @param string $password
     * @return Model|null|static
     */
    public static function findByEmailAndPassword($email, $password)
    {
        return self::where('email', $email)
            ->where('password', hash('sha256', $password))->first();
    }

    /**
     * @return string
     */
    public function getChannelUrl()
    {
        return "/users/{$this->username}";
    }

    /**
     * @param string|null $token
     * @return $this
     */
    public function setToken($token = null)
    {
        if (is_null($token)) {
            $token = bin2hex(random_bytes(255));
        }

        $this->remember_token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function saveAndGenerateToken()
    {
        return $this->setToken()
            ->save();
    }

    /**
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }
}
