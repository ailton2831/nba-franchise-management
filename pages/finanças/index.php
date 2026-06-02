<?php 
include("../../db.php");


if(date('m') < 10){
    $temporada_atual = (date('Y') - 1) . '/' . date('Y');
} else {
    $temporada_atual = date('Y') . '/' . (date('Y') + 1);
}

if(isset($_GET['temporada'])){
    $temporada_atual = $_GET['temporada'];
}

if($_POST){
    $temporada = (isset($_POST["temporada"])?$_POST["temporada"]:"");
    $salary_cap = (isset($_POST["salary_cap"])?$_POST["salary_cap"]:"");
    $teto_salarial = (isset($_POST["teto_salarial"])?$_POST["teto_salarial"]:"");

    if (!preg_match('/^\d{4}\/\d{4}$/', $temporada)) {
        $erro_validacao = "Formato de temporada inválido! Use o formato XXXX/XXXX (ex: 2024/2025).";
    } else {
        $sentencia = $conexion->prepare("SELECT id FROM financas WHERE temporada = :temporada");
        $sentencia->bindParam(":temporada", $temporada);
        $sentencia->execute();
        $existe = $sentencia->fetch();
        if($existe){
            $sentencia = $conexion->prepare("UPDATE financas SET salary_cap=:salary_cap, teto_salarial=:teto_salarial WHERE temporada=:temporada");
            } else {
            $sentencia = $conexion->prepare("INSERT INTO financas (temporada, salary_cap, teto_salarial) VALUES (:temporada, :salary_cap, :teto_salarial)");
            }
            $sentencia->bindParam(":temporada", $temporada);
            $sentencia->bindParam(":salary_cap", $salary_cap);
            $sentencia->bindParam(":teto_salarial", $teto_salarial);
            $sentencia->execute();
            $temporada_atual = $temporada;
    }
    
}


$sentencia = $conexion->prepare("SELECT * FROM financas WHERE temporada = :temporada");
$sentencia->bindParam(":temporada", $temporada_atual);
$sentencia->execute();
$financas = $sentencia->fetch(PDO::FETCH_ASSOC);

$salary_cap    = $financas['salary_cap'] ?? 0;
$teto_salarial = $financas['teto_salarial'] ?? 0;


//total de salarios ativos
$sentencia = $conexion->prepare("SELECT SUM(salario) AS total FROM contrato WHERE status = 'ativo'");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
$total_comprometido = $resultado['total'] ?? 0;

$espaco_disponivel = $teto_salarial - $total_comprometido;
$teto_excedido     = $espaco_disponivel < 0;


// Top 5 maiores salários
$sentencia = $conexion->prepare("
    SELECT j.nome, c.salario, 'Jogador' AS tipo
    FROM contrato c
    JOIN jogadores j ON c.id_jogador = j.id
    WHERE c.status = 'ativo' AND c.id_jogador IS NOT NULL

    UNION ALL

    SELECT s.nome, c.salario, 'Staff' AS tipo
    FROM contrato c
    JOIN staff s ON c.id_staff = s.id
    WHERE c.status = 'ativo' AND c.id_staff IS NOT NULL

    ORDER BY salario DESC
    LIMIT 5
");
$sentencia->execute();
$top5 = $sentencia->fetchAll(PDO::FETCH_ASSOC);


$sentencia = $conexion->prepare("SELECT temporada FROM financas ORDER BY temporada DESC");
$temporadas = $sentencia->fetchAll(PDO::FETCH_ASSOC);




?>

<?php include("../../template/header.php"); ?>

<br/>

<?php if($teto_excedido): ?>
<div class="alert alert-danger">
    Teto salarial excedido em <strong>$<?= number_format(abs($espaco_disponivel), 0, ',', '.') ?></strong>. Reveja os contratos ativos.
</div>
<?php endif; ?>

<form method="GET" action="" class="d-flex gap-2 align-items-center mb-4">
    <label class="form-label mb-0">Temporada:</label>
    <select name="temporada" class="form-select" style="width:auto">
        <?php foreach($temporadas as $registo): ?>
            <option value="<?= $registo['temporada'] ?>" 
                <?= $registo['temporada'] === $temporada_atual ? 'selected' : '' ?>>
                <?= $registo['temporada'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-outline-primary">Apply</button>
</form>

<!-- Cards de métricas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Salary cap</p>
            <h5 class="text-primary mb-0">$<?= number_format($salary_cap, 0, ',', '.') ?></h5>
            <small class="text-muted">limite suave</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Luxury Tax</p>
            <h5 class="mb-0">$<?= number_format($teto_salarial, 0, ',', '.') ?></h5>
            <small class="text-muted">limite máximo</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <p class="text-muted mb-1" style="font-size:13px">Total comprometido</p>
            <h5 class="text-warning mb-0">$<?= number_format($total_comprometido, 0, ',', '.') ?></h5>
            <small class="text-muted">contratos ativos</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3 <?= $teto_excedido ? 'border-danger' : 'border-success' ?>">
            <p class="text-muted mb-1" style="font-size:13px">Espaço disponível</p>
            <h5 class="<?= $teto_excedido ? 'text-danger' : 'text-success' ?> mb-0">
                $<?= number_format(abs($espaco_disponivel), 0, ',', '.') ?>
            </h5>
            <small class="text-muted"><?= $teto_excedido ? 'excedido' : 'disponível' ?></small>
        </div>
    </div>
</div>

<!-- Formulário + Top 5 -->
<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-3">
            <p class="text-uppercase text-muted mb-3" style="font-size:12px;font-weight:600">Definir cap da temporada</p>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Temporada</label>
                    <input type="text" name="temporada" class="form-control"
                            value="<?= $financas['temporada'] ?? '' ?>"
                            placeholder="ex: 2024/2025" 
                            pattern="\d{4}/\d{4}" required>
    
                </div>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <label class="form-label">Salary cap ($)</label>
                        <input type="number" name="salary_cap" class="form-control"
                            value="<?= $salary_cap ?>" min="0" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Luxury Tax ($)</label>
                        <input type="number" name="teto_salarial" class="form-control"
                            value="<?= $teto_salarial ?>" min="0" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-3">
            <p class="text-uppercase text-muted mb-3" style="font-size:12px;font-weight:600">Top 5 maiores salários</p>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th class="text-end">Salário anual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($top5 as $i => $registo): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($registo['nome']) ?></td>
                        <td>
                            <span class="badge" style="font-size:11px;background:<?= $registo['tipo'] === 'Jogador' ? '#e7f1fb' : '#f0f7ea' ?>;color:<?= $registo['tipo'] === 'Jogador' ? '#0c447c' : '#27500a' ?>">
                                <?= $registo['tipo'] ?>
                            </span>
                        </td>
                        <td class="text-end fw-semibold">$<?= number_format($registo['salario'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../template/footer.php"); ?>