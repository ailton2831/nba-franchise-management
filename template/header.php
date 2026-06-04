<?php 

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

$url_base = "http://localhost/nba/";
$uri = $_SERVER['REQUEST_URI'];
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Gestão de Franquia NBA</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="<?= $url_base ?>style.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand navbar-dark" style="background-color: #17408B;">
            <ul class="nav navbar-nav w-100">
                <li class="nav-item">
                    <img src="<?= $url_base ?>imagens/nbalogo2.png" height="40" class="ms-3 me-2">
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($uri, 'index.php') !== false || $uri === '/nba/') ? 'active' : '' ?>" href="<?php echo $url_base;?>index.php">Home page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($uri, 'jogadores') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/jogadores">Jogadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($uri, 'staff') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/staff">Staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($uri, 'jogos') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/jogos">Jogos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($uri, 'stats') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/stats">Stats</a>
                </li>
                
                <?php if(isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($uri, 'utilizadores') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/utilizadores">Utilizadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($uri, 'financas') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/financas">Finanças</a>
                    </li>
                <?php } ?>

                <?php if(isset($_SESSION['tipo']) && ($_SESSION['tipo'] === 'analista' || $_SESSION['tipo'] === 'admin')){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($uri, 'contratos') !== false) ? 'active' : '' ?>" href="<?php echo $url_base;?>pages/contratos">Contratos</a>
                    </li>
                <?php } ?>
                                
                <li class="nav-item ms-auto me-3">
                    <a class="nav-link" href="<?php echo $url_base;?>logout.php">Logout</a>
                </li>
            </ul>
        </nav>
        
        <main class="container">
            <br/>
            <?php if(isset($erro_validacao) && !empty($erro_validacao)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro!</strong> <?= $erro_validacao ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>