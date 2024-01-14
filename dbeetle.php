<?php
/*
 DBeetle - Lightweight standalone PHP script for quickly access databases.
 (c) 2024 FranklyRocks (https://github.com/franklyrocks)
*/

$PASSWORD = "";
$LOG_PATH = "queries.json";
$PDO = new PDO("sqlite:app.db");

if(isset($_POST["action"]) && $_POST["action"] == "login") {
    if(isset($_POST["password"]) && $_POST["password"] == $PASSWORD) {
        setcookie("auth", $PASSWORD);
        print_home();
        exit;
    }
    
    $error_message = "Bad password.";
}

if($PASSWORD != "" && (!isset($_COOKIE["auth"]) || $_COOKIE["auth"] != $PASSWORD)) {
    print_login();
    exit;
}

if(isset($_POST["action"])) {
    if($_POST["action"] == "logout") {
        setcookie("auth", "", time() - 1);
        print_login();
        exit;
    }
    if($_POST["action"] == "clear") {
        file_put_contents($LOG_PATH, json_encode([]));
        print_home();
        exit;
    }
    if($_POST["action"] == "query" && isset($_POST["sql"])) {
        $queries = file_exists($LOG_PATH) ? json_decode(file_get_contents($LOG_PATH)) : [];

        try {
            ($stmt = $PDO->prepare($_POST["sql"]))->execute();
            $queries[] =  [
                "date" => date("Y-m-d H:m:i"),
                "sql" => $_POST["sql"],
                "data" => $stmt->fetchAll(PDO::FETCH_OBJ)
            ];
        } catch (Exception $err) {
            $queries[] =  [
                "date" => date("Y-m-d H:m:i"),
                "sql" => $_POST["sql"],
                "data" => $err
            ];
        }

        file_put_contents($LOG_PATH, json_encode($queries));
        print_home();
        exit;
    }
} 

print_home();

function print_login() {
    ?>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Login - DBeetle (1.0.0)</title>
            </head>
            <body style="font-family: sans-serif; display:flex; flex-flow:column; height: 100%; margin: 0 .25rem;">
                <h1 style="text-align: center;">DBeetle <small style="font-size: small; font-weight: normal;">(1.0.0)</small></h1>
                <form method="POST" style="display: flex; justify-content: center; gap: .25rem;">
                    <input name="password" placeholder="Password">
                    <button name="action" value="login">Login</button>
                </form>
                <small style="color: red; text-align: center;"><?= $GLOBALS["error_message"] ?? "" ?></small>
            </body>
        </html>
    <?php
}

function print_home() {
    $queries = file_exists($GLOBALS["LOG_PATH"]) ? json_decode(file_get_contents($GLOBALS["LOG_PATH"])) : [];
    ?>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>DBeetle (1.0.0)</title>
            </head>
            <body style="font-family: sans-serif; display:flex; flex-flow:column; height: 100%; margin: 0 .25rem;">
                <h1 style="text-align: center;">DBeetle <small style="font-size: small; font-weight: normal;">(1.0.0)</small></h1>
                <form method="POST" style="text-align: center; margin-bottom: 0;">
                    <?php if($GLOBALS["PASSWORD"] != "") { ?>
                        <button name="action" value="logout">Logout</button> 
                    <?php } ?>
                    <button name="action" value="clear">Clear</button> 
                </form>
                <pre id="log" style="flex-grow: 1; overflow: auto; border: 1px solid grey; border-radius: .15rem; margin-bottom: .5rem;"><code><?= json_encode($queries, JSON_PRETTY_PRINT); ?></code></pre>
                <form method="POST" style="display: flex; margin-bottom: 0; padding-bottom: .5rem;">
                    <textarea style="max-height: 500px; min-height: 3em; width: 100%; margin-right: .25rem;" name="sql" placeholder="SELECT * FROM users" oninput="resize()"></textarea>
                    <button name="action" value="query">Execute</button>
                </form>
                <script>
                    function resize() {
                        event.target.style.height = 0;
                        event.target.style.height = (event.target.scrollHeight) + "px";
                    }

                    const log = document.getElementById("log");
                    log.scrollTo(0, log.scrollHeight);
                </script>
            </body>
        </html>
    <?php
}

?>