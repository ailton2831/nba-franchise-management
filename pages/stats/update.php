<?php 
include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

$txtID = (isset($_GET['txtID']) ? $_GET['txtID'] : "");


$sentencia = $conexion->prepare("SELECT stat.*, j.nome FROM estatistica stat
                                JOIN jogadores j ON j.id = stat.id_jogador
                                WHERE stat.id_jogo = :id_jogo");
$sentencia->bindParam(":id_jogo", $txtID);
$sentencia->execute();
$lista_stats = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['id_jogador'])){
    $sentencia = $conexion->prepare("UPDATE estatistica SET
                        pontos=:pts, assist=:ass, ressalto=:reb, roubos=:stl, bloqueios=:blk, minutos=:min 
                        WHERE id_jogo=:id_jogo AND id_jogador=:id_jogador");
    foreach ($_POST['id_jogador'] as $index => $id_jogador) {
        $min = (isset($_POST['min'][$index]) ? $_POST['min'][$index] : 0);
        $pts = (isset($_POST['pts'][$index]) ? $_POST['pts'][$index] : 0);
        $ass = (isset($_POST['ass'][$index]) ? $_POST['ass'][$index] : 0);
        $reb = (isset($_POST['reb'][$index]) ? $_POST['reb'][$index] : 0);
        $blk = (isset($_POST['blk'][$index]) ? $_POST['blk'][$index] : 0);
        $stl = (isset($_POST['stl'][$index]) ? $_POST['stl'][$index] : 0);


        $sentencia->bindParam(":id_jogo", $txtID);
        $sentencia->bindParam(":id_jogador", $id_jogador);
        $sentencia->bindParam(":pts", $pts);
        $sentencia->bindParam(":ass", $ass);
        $sentencia->bindParam(":reb", $reb);
        $sentencia->bindParam(":stl", $stl);
        $sentencia->bindParam(":blk", $blk);
        $sentencia->bindParam(":min", $min);
        $sentencia->execute();
    }
    header("Location: ../jogos/index.php");
}

?>


<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-body">
        <form method="POST" action="">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Jogador</th>
                    <th>MIN</th>
                    <th>PTS</th>
                    <th>AST</th>
                    <th>REB</th>
                    <th>BLK</th>
                    <th>STL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_stats as $index => $stats): ?>
                    <tr>
                        <td class="align-middle fw-semibold">
                            <?php echo $stats['nome']; ?>
                            <input type="hidden" name="id_jogador[]" value="<?php echo $stats['id_jogador']; ?>">
                        </td>
                        <td><input type="number" name="min[]" class="form-control" min="0" max="48" value="<?php echo $stats['minutos']; ?>"></td>
                        <td><input type="number" name="pts[]" class="form-control" min="0" value="<?php echo $stats['pontos']; ?>"></td>
                        <td><input type="number" name="ass[]" class="form-control" min="0" value="<?php echo $stats['assist']; ?>"></td>
                        <td><input type="number" name="reb[]" class="form-control" min="0" value="<?php echo $stats['ressalto']; ?>"></td>
                        <td><input type="number" name="blk[]" class="form-control" min="0" value="<?php echo $stats['bloqueios']; ?>"></td>
                        <td><input type="number" name="stl[]" class="form-control" min="0" value="<?php echo $stats['roubos']; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-2">Guardar box score</button>
</form>
    </div>
</div>

<?php include("../../template/footer.php");?>