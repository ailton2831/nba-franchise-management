<?php 
session_start();
include("db.php");
if($_POST){
    $sentencia=$conexion->prepare("SELECT * FROM `users` WHERE user=:user LIMIT 1");
    $user=$_POST["user"];
    $password=$_POST["password"];
    $sentencia->bindParam(":user", $user);
    $sentencia->execute();
    $registo = $sentencia->fetch(PDO::FETCH_LAZY);

    if($registo){
        if (password_verify($password, $registo["password"])){
            $_SESSION['user']=$registo['user'];
            $_SESSION['tipo']=$registo['tipo'];
            header("Location:index.php");
        }else{
            $mensagem= "Error: Utilizador ou password estão incorretos";
        }
    }else{
        $mensagem= "Error: Utilizador nao existe";
    }

}

?>


<!doctype html>
<html lang="en" data-bs-theme="light">
    <head>
        <title>Gestão de Franquia NBA</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <link href="style.css?v=<?= time() ?>" rel="stylesheet">
    </head>

    <body class="login-page">
        <header>
            <!-- place navbar here -->
        </header>
        <main class="container">
            <div class="row justify-content-center min-vh-100 align-items-center">
                <div class="col-md-4">
                    <div class="text-center mb-4">
                        <img src="imagens/nbalogo2.png" height="60">
                        <h4 class="mt-2 fw-bold">NBA Franchise</h4>
                        <p class="text-muted" style="font-size:13px">Gestão de franquia</p>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="card">
                                <div class="card-header">Login</div>
                                <div class="card-body">
                                    <?php if(isset($mensagem)){?>
                                    <div
                                        class="alert alert-danger"
                                        role="alert"
                                    >
                                        <strong><?php echo $mensagem?></strong> 
                                    </div>
                                    <?php } ?>
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Username</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="user"
                                                id="user"
                                                aria-describedby="helpId"
                                                placeholder="Digite o username"
                                            />

                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">Password</label>
                                            <input
                                                type="password"
                                                class="form-control"
                                                name="password"
                                                id="password"
                                                aria-describedby="helpId"
                                                placeholder="Digite o password"
                                            />
                                        </div>
                                        <button
                                            type="submit"
                                            class="btn btn-primary d-block mx-auto"
                                        >
                                            Entrar no sistema
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Bundle (includes Popper) -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>

