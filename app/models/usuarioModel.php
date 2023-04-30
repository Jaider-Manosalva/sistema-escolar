<?php 

class usuarioModel extends Model
{
   public static function by_email($email){
     $sql = 'SELECT * FROM tb_usuarios WHERE email = :email LIMIT 1';

     return ($rows = parent::query($sql, ['email' => $email])) ? $rows[0] : [];
   }
}  