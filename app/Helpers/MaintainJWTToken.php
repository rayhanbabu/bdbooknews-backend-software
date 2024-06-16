<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
 

class MaintainJWTToken
{
    public static function CreateToken($name,$email,$maintain_id,$role,$university_assign)
    {
        $key =env('JWT_KEY');
        $payload=[
            'iss'=>'rayhan-token',
            'iat'=>time(),
            'exp'=>time()+60*60*24*7,
            'email'=>$email,
            'maintain_username'=>$name,
            'maintain_id'=>$maintain_id,
            'role'=>$role,
            'university_assign'=>$university_assign
        ];
        return JWT::encode($payload,$key,'HS256');
    }

    public static function ReadToken($token)
    {
        try {
            if($token==null){
                return 'unauthorized';
            }
            else{
                $key =env('JWT_KEY');
                return JWT::decode($token,new Key($key,'HS256'));
            }
        }
        catch (Exception $e){
            return 'unauthorized';
        }
    }
}

?>