<?php 
include("../../db.php");
include("../../verificao_sessao.php");

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("DELETE FROM jogo WHERE id=:id");
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

$sentencia=$conexion->prepare("SELECT * FROM `jogo`");
$sentencia->execute();
$lista_jogos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

$hoje = date('Y-m-d');
?>

<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="create.php" role="button">Adicionar Jogos</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Data</th>
                        <th scope="col">Adversário</th>
                        <th scope="col">Local</th>
                        <th scope="col">Placar</th>
                        <th scope="col">Resultado</th>
                        <th scope="col">Temporada</th>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <th scope="col" style="text-align: right;">Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_jogos as $registo) { ?> 
                    <tr>
                        <td scope="row"><?php echo $registo['id'] ;?></td>
                        <td><?php echo date('d/m/Y', strtotime($registo['data'])) ;?></td>
                        <td><?php echo $registo['adversario'] ;?></td>
                        <td><?php echo $registo['local'] ;?></td>
                        
                        <td>
                            <?php 
                            if ($registo['data'] <= $hoje && $registo['placar'] !== null) {
                                echo $registo['placar'] . ' - ' . $registo['placar_adv'];
                            } else {
                                echo '<span class="text-muted">TBD</span>';
                            }
                            ?>
                        </td>
                        
                        <td>
                            <?php 
                            if ($registo['data'] <= $hoje && $registo['placar'] !== null) {
                                if ($registo['placar'] > $registo['placar_adv']) { 
                                    echo '<span class="badge badge-winner">W</span>'; 
                                } else {
                                    echo '<span class="badge badge-loser">L</span>'; 
                                }
                            } else {
                                echo '<span class="badge bg-light text-dark">Agendado</span>';
                            }
                            ?> 
                        </td>
                        
                        <td><?php echo $registo['temporada'] ;?></td>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <td style="text-align: right;">
                                <?php if ($registo['data'] <= $hoje && $registo['placar'] !== null): ?>
                                    <a href="boxscore.php?txtID=<?php echo $registo['id'];?>" class="btn btn-primary btn-sm" role="button">
                                        Box score
                                    </a>
                                <?php endif; ?>
                                <a class="btn btn-success btn-sm" href="update.php?txtID=<?php echo $registo['id'];?>" role="button">Update</a>
                                <a class="btn btn-danger btn-sm" href="javascript:eliminar(<?php echo $registo['id'];?>);" role="button">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function eliminar(id){
        Swal.fire({
            title: "Tem a certeza?",
            text: "Esta ação irá remover o jogo permanentemente!",
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