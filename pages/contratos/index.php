<?php 
include("../../db.php");

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("DELETE FROM contrato WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    //$mensagem="Registo eliminado com sucesso";
    //header("Location:index.php?mensagem=".$mensagem);
}


$sentencia=$conexion->prepare("SELECT c.id, c.data_inicio AS inicio, c.data_final AS fim, c.salario, c.status, c.tipo,
                                COALESCE(j.nome, s.nome) AS nome
                                FROM contrato c
                                LEFT JOIN jogadores j ON c.id_jogador = j.id
                                LEFT JOIN staff s ON c.id_staff = s.id");
$sentencia->execute();
$lista_contrato=$sentencia->fetchAll(PDO::FETCH_ASSOC);

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
            >Criar Contrato de Jogador</a
        >
    </div>
    <div class="card-header">
        <a
            name=""
            id=""
            class="btn btn-primary"
            href="createStaff.php"
            role="button"
            >Criar Contrato de Staff</a
        >
    </div>
    <div class="card-body">
        <div
            class="table-responsive"
        >
            <table
                class="table" id="table_id"
            >
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fim</th>
                        <th scope="col">Salario</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_contrato as $registo) {?> 
                    <tr class="">
                        <td scope="row"><?php echo $registo['id'] ;?></td>
                        <td><?php echo $registo['nome'] ;?></td>
                        <td><?php //echo $registo['tipo'] ;?></td>
                        <td><?php echo $registo['inicio'] ;?></td>
                        <td><?php echo $registo['fim'] ;?></td>
                        <td><?php echo $registo['salario'] ;?></td>
                        <td><?php echo $registo['status'] ;?></td>
                        <td>
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