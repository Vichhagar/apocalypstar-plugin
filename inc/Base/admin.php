<?php
namespace Inc\Base;

class admin extends DB{

    public  function auth  ($user, $pass){
        $req = $this->pdo->prepare("SELECT * FROM admin WHERE user = ? AND password = ?");
        $req->execute(array($user, $pass));
        $data = $req->fetchObject();
        return $data;
    }
    
    public  function addModo  ($user, $pass, $level){
        $req = $this->pdo->prepare("INSERT INTO admin (user, password, level)  VALUES (?, ?, ?)");
        $req->execute(array($user, $pass, $level));
    }


    public function getModo()
    {
        $req = $this->pdo->query("SELECT * FROM admin");
        $data = $req->fetchAll();
        return $data;
    }


    public function deleteModo($id)
    {
        $req = $this->pdo->exec("DELETE FROM admin WHERE id = '$id'");
        return $req;
    }


    public function hash512($pass){
        $pass512 = hash('sha512', $pass);
        return $pass512;
    }


    public function droit($num_droit){
        if ($num_droit == 1 ){
            $name_droit = "Administrateur";
        }
        elseif ($num_droit == 2){
            $name_droit = "Modérateur";
        }
        elseif ($num_droit == 3){
            $name_droit = "Invité";
        }
        return $name_droit;
    }
}