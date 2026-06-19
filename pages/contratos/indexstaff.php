<?php 
include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin" && $_SESSION['tipo'] !== "GM" ){
    header("Location:../../index.php");
    exit();
}

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("DELETE FROM contratostaff WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();

    $_SESSION['alerta'] = [
        'icon'  => 'success',
        'title' => 'Eliminado!',
        'text'  => 'O registo foi removido com sucesso.'
    ];
    
    header("Location:index.php");
    exit();
}


$sentencia=$conexion->prepare("SELECT c.id, c.data_inicio AS inicio, c.data_final AS fim, c.salario, c.status, c.tipo,
                                COALESCE(s.nome) AS nome
                                FROM contratostaff c
                                LEFT JOIN staff s ON c.id_staff = s.id");
$sentencia->execute();
$lista_contrato=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../template/header.php");?>


<br/>
<div class="card">
    <div class="card-header">
        <?php if($_SESSION['tipo'] == "admin"){ ?>
        <a
            name=""
            id=""
            class="btn btn-primary"
            href="createStaff.php"
            role="button"
            >Criar Contrato de Staff</a
        >
        <?php } ?>
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
                        <th scope="col">Salario anual</th>
                        <th scope="col">Status</th>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <th scope="col">Ação</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_contrato as $registo) {?> 
                    <tr class="">
                        <td scope="row"><?php echo $registo['id'] ;?></td>
                        <td><?php echo $registo['nome'] ;?></td>
                        <td><?php echo $registo['tipo'] ;?></td>
                        <td><?php echo $registo['inicio'] ;?></td>
                        <td><?php echo $registo['fim'] ;?></td>
                        <td><?php echo $registo['salario'] ;?>M</td>
                        <td><?php echo $registo['status'] ;?></td>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
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
                                href="javascript:eliminar(<?php echo $registo['id'];?>);"
                                role="button"
                                >Delete</a
                                >
                            </td>
                        <?php endif; ?>
                    </tr>

                    <?php }?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<script>
    function eliminar(id){
        Swal.fire({
            title: "Tem a certeza?",
            text: "Esta ação irá remover o contrato permanentemente!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DA1A32", 
            cancelButtonColor: "#17408B",  
            confirmButtonText: "Sim, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed){
                window.location="index.php?txtID="+id;
            }
        });
    }
</script>


<?php include("../../template/footer.php");?>