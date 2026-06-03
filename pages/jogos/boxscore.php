<?php 
include("../../db.php");

$lista_stats = []; 
$tem_stats = false;

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT stats.*, j.nome 
                                    FROM estatistica stats
                                    JOIN jogadores j ON j.id = stats.id_jogador
                                    WHERE stats.id_jogo = :id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    $lista_stats=$sentencia->fetchAll(PDO::FETCH_ASSOC);


    $sentencia = $conexion->prepare("SELECT COUNT(*) as total FROM estatistica WHERE id_jogo = :id_jogo");
    $sentencia->bindParam(":id_jogo", $txtID);
    $sentencia->execute();
    $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
    $tem_stats = $resultado['total'] > 0;

}



?>



<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-header">
        <?php //if ($_SESSION['perfil'] === 'admin'): ?>
            <?php if ($tem_stats): ?>
                <a
                name=""
                id=""
                class="btn btn-primary"
                href="../stats/update.php?txtID=<?php echo $txtID ;?>"
                role="button">Editar Estatistica</a>
            <?php else: ?>
                <a
                name=""
                id=""
                class="btn btn-primary"
                href="../stats/create.php?txtID=<?php echo $txtID;?>"
                role="button">Adicionar Estatistica</a>
            <?php endif; ?>
        <?php //endif; ?>
    </div>
    <div class="card-body">
        <div
            class="table-responsive"
        >
            <table
                class="table" id="table"
            >
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Minutos</th>
                        <th scope="col">Pontos</th>
                        <th scope="col">Assistencias</th>
                        <th scope="col">Ressaltos</th>
                        <th scope="col">Bloqueios</th>
                        <th scope="col">Roubos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_stats as $registo) {?> 
                    <tr class="">
                        <td scope="row"><?php echo $registo['nome'] ;?></td>
                        <td><?php echo $registo['min'] ;?></td>
                        <td><?php echo $registo['pts'] ;?></td>
                        <td><?php echo $registo['ass'] ;?></td>
                        <td><?php echo $registo['reb'] ;?></td>
                        <td><?php echo $registo['blk'] ;?></td>
                        <td><?php echo $registo['stl'] ;?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include("../../template/footer.php");?>