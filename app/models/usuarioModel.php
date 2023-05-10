<?php 

class usuarioModel extends Model
{
   static $t1 = 'tb_usuarios';
   
   public static function by_email($email){
     $sql = 'SELECT * FROM tb_usuarios WHERE email = :email LIMIT 1';
     return ($rows = parent::query($sql, ['email' => $email])) ? $rows[0] : [];
   }

  public static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM tb_usuarios WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }
}  