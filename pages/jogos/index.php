<?php 
include("../../db.php");

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("DELETE FROM jogo WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    //$mensagem="Registo eliminado com sucesso";
    //header("Location:index.php?mensagem=".$mensagem);
}




$sentencia=$conexion->prepare("SELECT * FROM `jogo`");
$sentencia->execute();
$lista_jogos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

$hoje = date('Y-m-d');


?>




<?php include("../../template/header.php");?>


<br/>
<div class="card">
    <div class="card-header">
        <a
            name=""
            id=""
            class="btn btn-primary"
            href="create.php"
            role="button"
            >Adicionar Jogos</a
        >
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
                        <th scope="col">Id</th>
                        <th scope="col">Data</th>
                        <th scope="col">Adversario</th>
                        <th scope="col">Local</th>
                        <th scope="col">Resultado</th>
                        <th scope="col"></th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_jogos as $registo) {?> 
                    <tr class="">
                        <td scope="row"><?php echo $registo['id'] ;?></td>
                        <td><?php echo $registo['data'] ;?></td>
                        <td><?php echo $registo['adversario'] ;?></td>
                        <td><?php echo $registo['local'] ;?></td>
                        <td><?php echo $registo['placard'] . ' - ' . $registo['placard_adv']; ?> ;?></td>
                        <td>
                        <?php if ($registo['data'] <= $hoje ): ?>
                            <a href="boxscore.php?txtID=<?php echo $registo['id'];?>"
                                class="btn btn-primary"
                                role="button">
                                Box score
                            </a>
                        <?php endif; ?>
                        <a
                            name=""
                            id=""
                            class="btn btn-success"
                            href="update.php?txtID=<?php echo $registo['id'];?>"
                            role="button"
                            >Update</a
                        >
                        <a
                            name=""
                            id=""
                            class="btn btn-danger"
                            href="index.php?txtID=<?php echo $registo['id'];?>"
                            role="button"
                            >Delete</a
                        >
                        
                    </td>
                    </tr>

                    <?php }?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>


<?php include("../../template/footer.php");?>