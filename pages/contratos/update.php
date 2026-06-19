<?php 
include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT * FROM contratoj WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    $registo=$sentencia->fetch(PDO::FETCH_LAZY);
    $salario=$registo['salario'];
    $id_jogador = $registo['id_jogador'];

    $sentencia=$conexion->prepare("SELECT * FROM `jogadores`");
    $sentencia->execute();
    $lista_jogadores=$sentencia->fetchAll(PDO::FETCH_ASSOC);

}

if($_POST){
    $inicio = (isset($_POST["inicio"])?$_POST["inicio"]:"");
    $fim = (isset($_POST["fim"])?$_POST["fim"]:"");
    $salario = (isset($_POST["salario"])?$_POST["salario"]:"");
    $id_jogador = (isset($_POST["id_jogador"])?$_POST["id_jogador"]:"");
    $status = (isset($_POST["status"])?$_POST["status"]:"");

    $sentencia=$conexion->prepare("UPDATE contratoj SET id_jogador=:idjogador data_inicio=:inicio, data_final=:fim,salario=:salario,status=:status WHERE id=:id");
    $sentencia-> bindParam(":inicio", $inicio);
    $sentencia-> bindParam(":fim", $fim);
    $sentencia-> bindParam(":salario", $salario);
    $sentencia-> bindParam(":status", $status);
    $sentencia-> bindParam(":idjogador", $id_jogador);
    $sentencia-> bindParam(":id", $txtID); 
    $sentencia->execute();

    $_SESSION['alerta'] = [
        'icon'  => 'success',
        'title' => 'Atualizado!',
        'text'  => 'As alterações foram guardadas com sucesso.'
    ];
    
    header("Location:index.php");
    exit();
}




function getEnumValues($pdo, $tabela, $coluna) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM {$tabela} LIKE '{$coluna}'");
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado && preg_match('/enum\((.*)\)/', $resultado['Type'], $matches)) {
        return str_getcsv($matches[1], ',', "'");
    }
    return [];
}

?>

<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-header">Criar Contrato</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_jogador" class="form-label">Escolher Jogador</label>
                <select
                    class="form-select form-select-lg"
                    name="id_jogador"
                    id="id_jogador"
                >
                    <option selected>Select one</option>
                    <?php foreach ($lista_jogadores as $jogadores){ ?>
                    <option value="<?php echo $jogadores['id'];?>
                    "<?php echo ($jogadores['id'] == $id_jogador) ? 'selected' : ''; ?>>
                    <?php echo $jogadores['nome'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Data inicio</label>
                <input
                    type="date"
                    class="form-control"
                    name="inicio"
                    id="inicio"
                    aria-describedby="helpId"
                    placeholder="data"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Data fim</label>
                <input
                    type="date"
                    class="form-control"
                    name="fim"
                    id="fim"
                    aria-describedby="helpId"
                    placeholder="data"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Salario</label>
                <input
                    type="double"
                    class="form-control"
                    name="salario"
                    value="<?php echo $salario?>"
                    id="salario"
                    aria-describedby="helpId"
                    placeholder="Salario por ano"
                />
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $opcoesStatus = getEnumValues($conexion, 'contrato', 'status');
                    foreach ($opcoesStatus as $opcao) {
                        echo "<option value='$opcao'>$opcao</option>";
                    }
                    ?>
                </select>
            </div>
            <button
                type="submit"
                class="btn btn-success"
            >
                Criar Contrato
            </button>
            <a
                name=""
                id=""
                class="btn btn-danger"
                href="index.php"
                role="button"
                >Cancelar</a
            >
            
        </form>
    </div>
</div>


<?php include("../../template/footer.php");?>