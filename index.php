<?php
include("db.php");

if(date('m') < 10){
    $temporada_real = (date('Y') - 1) . '/' . date('Y');
} else {
    $temporada_real = date('Y') . '/' . (date('Y') + 1);
}


$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_jogador FROM jogadores");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$totaljogador = $total['total_jogador'] ?? 0;

$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_vitoria FROM jogo
                                    WHERE placar > placar_vs");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$total_vitoria = $total['total_vitoria'] ?? 0;

$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_derrota FROM jogo
                                    WHERE placar < placar_vs");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$total_derrota = $total['total_derrota'] ?? 0;


?>

<?php include("template/header.php");?>

<div class="text-center my-4">
    <h3>Temporada <?php echo $temporada_real ?></h3>
</div>

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Total Jogadores</p>
            <h5 class="text-primary mb-0"><?= $totaljogador ?></h5>
            <small class="text-muted">na equipe</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Vitórias</p>
            <h5 class="mb-0"><?= $total_vitoria ?></h5>
            <small class="text-muted">na temporada</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Derrotas</p>
            <h5 class="text-warning mb-0"><?= $total_derrota ?></h5>
            <small class="text-muted">na temporada</small>
        </div>
    </div>
</div>



<?php include("template/footer.php");?>

