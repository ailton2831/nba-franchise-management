<?php 
//if (session_status()== PHP_SESSION_NONE){
//    session_start();
//}
$url_base = "http://localhost/nba/" ;
//if(!isset($_SESSION['user'])){
//    header("Location:".$url_base."login.php");
//    exit();

//}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>ProjetoFinal</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"/>

        <script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous">
        </script>
        
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" />

        <script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
    </head>

    
    

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <nav class="navbar navbar-expand navbar-light bg-light">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">NBA Franchise</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo $url_base;?>index.php" aria-current="page"
                    >Home page <span class="visually-hidden">(current)</span></a
                >
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/jogadores">Jogadores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/staff">Staff</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/jogos">Jogos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/stats">Stats</a>
            </li>
            <?php //if(isset($_SESSION['tipo']) && $_SESSION['tipo']== 'admin'){ ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/utilizadores">Utilizadores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/contratos">Contratos</a>
            </li>
            <?php //if(isset($_SESSION['tipo']) && $_SESSION['tipo']== 'analista'){ ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>pages/finanças">Finanças</a>
            </li>
            
            <?php //} ?>
            <?php //} ?>
                        
                        <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base;?>logout.php">Logout</a>
            </li>
        </ul>
    </nav>
        <main class="container">
            <br/>
            <?php if(isset($erro_validacao)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro!</strong> <?= $erro_validacao ?>
                    <button type="text" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>