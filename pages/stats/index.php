<?php 
include("../../db.php");
include("../../verificao_sessao.php");


$sentencia=$conexion->prepare("SELECT j.nome, 
                                COUNT(stat.id_jogo) AS jogos,
                                ROUND(AVG(stat.minutos), 1) AS media_min,
                                ROUND(AVG(stat.pontos), 1) AS media_pts,
                                ROUND(AVG(stat.assist), 1) AS media_ass,
                                ROUND(AVG(stat.ressalto), 1) AS media_reb,
                                ROUND(AVG(stat.bloqueios), 1) AS media_blk,
                                ROUND(AVG(stat.roubos), 1) AS media_stl
                                FROM estatistica stat
                                JOIN jogadores j ON j.id = stat.id_jogador
                                WHERE stat.minutos > 0
                                GROUP BY stat.id_jogador, j.nome
                                ORDER BY media_pts DESC");
$sentencia->execute();
$lista_stats=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include("../../template/header.php");?>




<br/>
<div class="card">
    <div class="card-header">
        
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
                        <th scope="col">Jogos</th>
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
                        <td><?php echo $registo['jogos'] ;?></td>
                        <td><?php echo $registo['media_min'] ;?></td>
                        <td><?php echo $registo['media_pts'] ;?></td>
                        <td><?php echo $registo['media_ass'] ;?></td>
                        <td><?php echo $registo['media_reb'] ;?></td>
                        <td><?php echo $registo['media_blk'] ;?></td>
                        <td><?php echo $registo['media_stl'] ;?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php include("../../template/footer.php");?>