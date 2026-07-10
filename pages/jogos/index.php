<?php 
include("../../db.php");
include("../../verificao_sessao.php");

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("DELETE FROM jogo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
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
$num_colunas = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') ? 8 : 7;
?>

<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-header">
        <?php if($_SESSION['tipo'] == "admin"){ ?>
        <a class="btn btn-primary" href="create.php" role="button">Adicionar Jogos</a>
        <?php } ?>
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
                        <th scope="col">Estatistica</th>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                            <th scope="col" style="text-align: right;">Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($lista_jogos)): ?>
                        <tr>
                            <td colspan="<?= $num_colunas ?>" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <span style="font-size:40px">🏀</span>
                                    <p class="mb-0 small">Nenhum jogo adicionado</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lista_jogos as $registo): ?>
                        <tr>
                            <td scope="row"><?= $registo['id'] ?></td>
                            <td><?= date('d/m/Y', strtotime($registo['data'])) ?></td>
                            <td><?= $registo['adversario'] ?></td>
                            <td><?= $registo['local'] ?></td>

                            <td>
                                <?php if ($registo['data'] <= $hoje && $registo['placar'] !== null): ?>
                                    <?= $registo['placar'] . ' - ' . $registo['placar_vs'] ?>
                                <?php else: ?>
                                    <span class="text-muted">TBD</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($registo['data'] <= $hoje && $registo['placar'] !== null): ?>
                                    <?php if ($registo['placar'] > $registo['placar_vs']): ?>
                                        <span class="badge badge-winner">W</span>
                                    <?php else: ?>
                                        <span class="badge badge-loser">L</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark">Agendado</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $registo['temporada'] ?></td>
                            <td>
                            <?php if ($registo['data'] <= $hoje && $registo['placar'] !== null): ?>
                                    <a href="boxscore.php?txtID=<?= $registo['id'] ?>" class="btn btn-primary btn-sm" role="button">
                                            Box score
                                    </a>
                            </td>
                                <td style="text-align: right;">
                                    <?php endif; ?>
                                    <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                                    <a class="btn btn-success btn-sm" href="update.php?txtID=<?= $registo['id'] ?>" role="button">Update</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:eliminar(<?= $registo['id'] ?>);" role="button">Delete</a>
                                    <?php endif; ?>
                                </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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