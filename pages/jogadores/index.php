<?php
include("../../db.php");
include("../../verificao_sessao.php");

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT foto FROM `jogadores` WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    $registo_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);
    
    if(isset($registo_recuperado["foto"]) && $registo_recuperado["foto"]!=""){
        if(file_exists("./".$registo_recuperado["foto"])){
            unlink("./".$registo_recuperado["foto"]);
        }
    }
    
    
    $sentencia=$conexion->prepare("DELETE FROM jogadores WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    
    
    //alerta de sucesso
    $_SESSION['alerta'] = [
        'icon'  => 'success',
        'title' => 'Eliminado!',
        'text'  => 'O registo foi removido com sucesso.'
    ];
    
    header("Location:index.php");
    exit();
}

$sentencia=$conexion->prepare("SELECT * FROM `jogadores`");
$sentencia->execute();
$lista_jogadores=$sentencia->fetchAll(PDO::FETCH_ASSOC);




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
            >Adicionar Jogadores</a
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
                        <th scope="col">Fotografia</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Numero</th>
                        <th scope="col">Posição</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Altura</th>
                        <th scope="col">Idade</th>
                        <th scope="col"></th>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <th scope="col">Ação</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_jogadores as $registo) {
                        $idade = "N/A";
                        if (!empty($registo['data_nascimento'])) {
                            $nascimento = new DateTime($registo['data_nascimento']);
                            $hoje = new DateTime();
                            $idade = $hoje->diff($nascimento)->y;
                        }?> 
                    <tr class="">
                        <td scope="row"><?php echo $registo['id'] ;?></td>
                        <td>
                            <img
                                src="<?php echo $registo['foto'] ;?>"
                                class="img-fluid rounded-top"
                                alt=""
                                width = "50"
                            />
                            
                            </td>
                        <td><?php echo $registo['nome'] ;?></td>
                        <td><?php echo $registo['numero'] ;?></td>
                        <td><?php echo $registo['posicao'] ;?></td>
                        <td><?php echo $registo['peso'] ;?></td>
                        <td><?php echo $registo['altura'] ;?></td>
                        <td><?php echo $idade ;?></td>
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
            text: "Esta ação irá remover o jogador permanentemente!",
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