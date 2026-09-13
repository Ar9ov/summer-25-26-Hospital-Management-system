<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    public function signup()
    {
        $name = $_POST["name"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if ($name == "" || $email == "" || $password == "") {

            echo json_encode([
                "success" => false,
                "message" => "Please fill in all fields."
            ]);

            return;
        }

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $userModel = new User($GLOBALS["conn"]);

        $result = $userModel->createUser(
            $name,
            $email,
            $hashedPassword
        );

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Account created successfully!"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);

        }
    }


    public function login()
    {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if ($email == "" || $password == "") {

            echo json_encode([
                "success" => false,
                "message" => "Please fill in all fields."
            ]);

            return;
        }

        $userModel = new User($GLOBALS["conn"]);

        $user = $userModel->findByEmail($email);

        if (!$user) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid email or password."
            ]);

            return;
        }

        if (!password_verify($password, $user["password"])) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid email or password."
            ]);

            return;
        }

        session_start();

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        echo json_encode([
            "success" => true,
            "message" => "Login successful!",
            "role" => $user["role"]
        ]);
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $controller = new AuthController();

    $action = $_POST["action"] ?? "";

    if ($action === "signup") {

        $controller->signup();

    }
    elseif ($action === "login") {

        $controller->login();

    }
    else {

        echo json_encode([
            "success" => false,
            "message" => "Invalid action."
        ]);

    }
}

?>
