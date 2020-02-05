<?php

$dbServername = 'Servers name';
$dbUsername = 'Database username';
$dbPassword = 'Your Password';
$dbName = 'Database name'; 

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    if(isset($_POST['login'])){
        $name=$_POST['username'];
        $pass=$_POST['password'];
        login($name, $pass,$conn);
    }
    else if(isset($_POST['createAccount'])){
        $name=$_POST['newUsername'];
        $pass=$_POST['newPassword'];
        createAccount($name, $pass,$conn);
    }

    function login($name, $pass,$conn){
        try{
            
            $sql= $conn ->prepare("SELECT * FROM user WHERE name = ? LIMIT 1");

            $sql->bind_param("s", $name);
            $sql->execute();
            $sqlResult = $sql->get_result();

            $row= $sqlResult->fetch_assoc();
            $hashedPass= $row["password"];

            if(password_verify($pass, $hashedPass)){
                
                echo "Welcome, $name!<br />";
            }
            else{
                echo"Login Failed";
            }
            
            $sql->close();
        }
            catch(PDOException $e){
                echo"connection error";
            
            }


        $conn->close();
    }

    function createAccount($name, $pass,$conn){
        try{
            if($name!="" && strlen($pass)>7){
                $hashedPass= password_hash($pass, PASSWORD_DEFAULT);

                $sql= $conn ->prepare("INSERT INTO user(name, password)   VALUES (?, ?)");
                $sql->bind_param("ss", $name, $hashedPass);
                $sql->execute();
                $sql->store_result();

                echo "Welcome to our site, $name!<br />";
                $sql->close();
                
            }
            else{
                echo"Check to see if you filled in username and password";
            }
            
        }
            catch(PDOException $e){
                echo"connection error";
            
            }

        $conn->close();
    }
       
?>