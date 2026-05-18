<?php 
session_start();
include("db.php");
if($_POST){
    print_r($_POST);



    $sentencia=$conexion->prepare("SELECT *, count(*) as n_user FROM `users` WHERE user=:user AND password=:password");
    $user=$_POST["user"];
    $password=$_POST["password"];
    $sentencia->bindParam(":user", $user);
    $sentencia->bindParam(":password", $password);
    $sentencia->execute();
    $registo = $sentencia->fetch(PDO::FETCH_LAZY);

    print_r($registo);

    if($registo["n_user"]>0){
        $_SESSION['user']=$registo['user'];
        $_SESSION['tipo']=$registo['tipo'];
        //$_SESSION["logado"]=true;
        header("Location:index.php");
    }else{
        $mensagem= "Error: Utilizador ou password estão incorretos";
    }

    print_r($registo);

}

?>


<!doctype html>
<html lang="en" data-bs-theme="light">
    <head>
        <title>Title</title>
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
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main class="container">
        <div class="row">
            <div class="col-md-4      " >
                
            </div>
            <div class="col-md-4      " >
            </br></br>
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <?php if(isset($mensagem)){?>
                        <div
                            class="alert alert-primary"
                            role="alert"
                        >
                            <strong><?php echo $mensagem?></strong> 
                        </div>
                        <?php } ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="" class="form-label">Utilizador</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="user"
                                    id="user"
                                    aria-describedby="helpId"
                                    placeholder="Digite o nome do utlizador"
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
                                    placeholder=""
                                />

                            </div>
                            
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Entrar no sistema
                            </button>
                            
                        </form>
                        
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

