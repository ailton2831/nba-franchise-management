<?php
include("db.php");

if(date('m') < 10){
    $temporada_real = (date('Y') - 1) . '/' . date('Y');
} else {
    $temporada_real = date('Y') . '/' . (date('Y') + 1);
}

//total jogadores
$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_jogador FROM jogadores");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$totaljogador = $total['total_jogador'] ?? 0;



//total win
$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_vitoria FROM jogo
                                    WHERE placar > placar_vs");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$total_vitoria = $total['total_vitoria'] ?? 0;


//total derrota
$sentencia = $conexion -> prepare ("SELECT COUNT(*) AS total_derrota FROM jogo
                                    WHERE placar < placar_vs");
$sentencia -> execute();
$total = $sentencia->fetch(PDO::FETCH_ASSOC);
$total_derrota = $total['total_derrota'] ?? 0;

//proximo jogo
$sentencia = $conexion->prepare("SELECT * FROM jogo 
                                    WHERE data >= CURDATE() 
                                    ORDER BY data ASC 
                                    LIMIT 1");
$sentencia->execute();
$proximo_jogo = $sentencia->fetch(PDO::FETCH_ASSOC); 

// Últimos 5 jogos
$sentencia = $conexion->prepare("SELECT * FROM jogo 
                                WHERE data < CURDATE() 
                                ORDER BY data DESC 
                                LIMIT 5");
$sentencia->execute();
$ultimos_jogos = $sentencia->fetchAll(PDO::FETCH_ASSOC);


// Top 1 pontos
$sentencia = $conexion->prepare("SELECT j.nome, ROUND(AVG(stat.pontos), 1) AS media
                                FROM estatistica stat
                                JOIN jogadores j ON j.id = stat.id_jogador
                                WHERE stat.minutos > 0
                                GROUP BY stat.id_jogador, j.nome
                                ORDER BY media DESC
                                LIMIT 1");
$sentencia->execute();
$top_pts = $sentencia->fetch(PDO::FETCH_ASSOC);

// Top 1 assist
$sentencia = $conexion->prepare("SELECT j.nome, ROUND(AVG(stat.assist), 1) AS media
                                FROM estatistica stat
                                JOIN jogadores j ON j.id = stat.id_jogador
                                WHERE stat.minutos > 0
                                GROUP BY stat.id_jogador, j.nome
                                ORDER BY media DESC
                                LIMIT 1");
$sentencia->execute();
$top_ast = $sentencia->fetch(PDO::FETCH_ASSOC);

// Top 1 ressaltos
$sentencia = $conexion->prepare("SELECT j.nome, ROUND(AVG(stat.ressalto), 1) AS media
                                FROM estatistica stat
                                JOIN jogadores j ON j.id = stat.id_jogador
                                WHERE stat.minutos > 0
                                GROUP BY stat.id_jogador, j.nome
                                ORDER BY media DESC
                                LIMIT 1");
$sentencia->execute();
$top_reb = $sentencia->fetch(PDO::FETCH_ASSOC);

// Percentagem de vitórias
$win_pct = ($total_vitoria + $total_derrota) > 0
           ? round(($total_vitoria / ($total_vitoria + $total_derrota)) * 100, 1)
            : 0;

?>

<?php include("template/header.php");?>

<div class="text-center my-4">
    <h3>Temporada <?php echo $temporada_real ?></h3>
</div>

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-6 col-md-3">
        <div class="card card-accent-neutral shadow-sm p-3">
            <p class="text-muted mb-1" style="font-size:13px">Total Jogadores</p>
            <h5 class="text-dark fw-bold mb-0"><?= $totaljogador ?></h5>
            <small class="text-muted">na equipe</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-accent-success shadow-sm p-3">
            <p class="text-muted mb-1" style="font-size:13px">Vitórias</p>
            <h5 class="text-success fw-bold mb-0"><?= $total_vitoria ?></h5>
            <small class="text-muted">na temporada</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-accent-danger shadow-sm p-3">
            <p class="text-muted mb-1" style="font-size:13px">Derrotas</p>
            <h5 class="text-danger fw-bold mb-0"><?= $total_derrota ?></h5>
            <small class="text-muted">na temporada</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-accent-primary shadow-sm p-3">
            <p class="text-muted mb-1" style="font-size:13px">% Vitórias</p>
            <h5 class="text-primary fw-bold mb-0"><?= $win_pct ?>%</h5>
            <small class="text-muted">win rate</small>
        </div>
    </div>
</div>

<!-- Próximo jogo e Últimos 5 resultados dos jogos -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <p class="text-uppercase text-muted mb-3" style="font-size:12px;font-weight:600">Próximo jogo</p>
            <?php if($proximo_jogo): ?>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">vs <?= htmlspecialchars($proximo_jogo['adversario']) ?></h5>
                        <p class="text-muted mb-0" style="font-size:13px">
                            📅 <?= date('d/m/Y', strtotime($proximo_jogo['data'])) ?>
                        </p>
                    </div>
                    <span class="badge <?= $proximo_jogo['local'] === 'casa' ? 'bg-success' : 'bg-secondary' ?>" style="font-size:13px;padding:6px 12px">
                        <?= $proximo_jogo['local'] === 'casa' ? '🏠 Casa' : '✈️ Fora' ?>
                    </span>
                </div>
            <?php else: ?>
                <p class="text-muted">Nenhum jogo agendado.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <p class="text-uppercase text-muted mb-3" style="font-size:12px;font-weight:600">Últimos 5 resultados</p>
            <?php if(empty($ultimos_jogos)): ?>
                <p class="text-muted">Nenhum jogo disputado ainda.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach($ultimos_jogos as $jogo): ?>
                        <?php $vitoria = $jogo['placar'] > $jogo['placar_adv']; ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge <?= $vitoria ? 'badge-winner' : 'badge-loser' ?>" 
                                    style="width:30px;text-align:center">
                                <?= $vitoria ? 'V' : 'D' ?>
                            </span>
                            <span class="fw-semibold" style="font-size:13px">
                                <?= $jogo['placar'] ?>-<?= $jogo['placar_adv'] ?>
                            </span>
                            <span class="text-muted" style="font-size:13px">
                                vs <?= htmlspecialchars($jogo['adversario']) ?>
                            </span>
                            <span class="text-muted ms-auto" style="font-size:12px">
                                <?= date('d/m', strtotime($jogo['data'])) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Líderes da temporada -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card lider-pts shadow-sm p-3 text-center">
            <p class="text-uppercase text-muted mb-2" style="font-size:12px;font-weight:600">Líder em pontos</p>
            <?php if($top_pts): ?>
                <h5 class="mb-1"><?= htmlspecialchars($top_pts['nome']) ?></h5>
                <h3 class="text-primary fw-bold mb-0"><?= $top_pts['media'] ?></h3>
                <small class="text-muted">pts por jogo</small>
            <?php else: ?>
                <p class="text-muted">Sem dados</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card lider-ast shadow-sm p-3 text-center">
            <p class="text-uppercase text-muted mb-2" style="font-size:12px;font-weight:600">Líder em assistências</p>
            <?php if($top_ast): ?>
                <h5 class="mb-1"><?= htmlspecialchars($top_ast['nome']) ?></h5>
                <h3 class="text-success fw-bold mb-0"><?= $top_ast['media'] ?></h3>
                <small class="text-muted">ast por jogo</small>
            <?php else: ?>
                <p class="text-muted">Sem dados</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card lider-reb shadow-sm p-3 text-center">
            <p class="text-uppercase text-muted mb-2" style="font-size:12px;font-weight:600">Líder em ressaltos</p>
            <?php if($top_reb): ?>
                <h5 class="mb-1"><?= htmlspecialchars($top_reb['nome']) ?></h5>
                <h3 class="text-warning fw-bold mb-0"><?= $top_reb['media'] ?></h3>
                <small class="text-muted">reb por jogo</small>
            <?php else: ?>
                <p class="text-muted">Sem dados</p>
            <?php endif; ?>
        </div>
    </div>
</div>



<?php include("template/footer.php");?>

