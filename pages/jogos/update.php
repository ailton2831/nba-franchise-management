<?php 
include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

function definirTemporada(DateTime $data) {

    if ((int)$data->format('m') < 10) {
        $temporada = ($data->format('Y') - 1) . '/' . $data->format('Y');
    } else {
        $temporada = $data->format('Y') . '/' . ($data->format('Y') + 1);
    }
    return $temporada; 
}

if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT * FROM jogo WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    $registo=$sentencia->fetch(PDO::FETCH_LAZY);

    $adversario=$registo['adversario'];
    $local=$registo['local'];
    $placar=$registo['placar'];
    $placar_adv=$registo['placar_adv'];
}

if($_POST){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $data = (isset($_POST["data"])?$_POST["data"]:"");
    $local = (isset($_POST["local"])?$_POST["local"]:"");
    $placar = (isset($_POST["placar"])?$_POST["placar"]:"");
    $placar_adv = (isset($_POST["placar_adv"])?$_POST["placar_adv"]:"");
    $adv = (isset($_POST["adversario"])?$_POST["adversario"]:"");
    $dataObjeto = new DateTime($data); 
    $temporada = definirTemporada($dataObjeto);

    $sentencia=$conexion->prepare("UPDATE jogo SET data=:data, local=:local, placar=:placar, adversario=:adversario, placar_vs=:placar_adv, temporada=:temporada WHERE id=:id");
    $sentencia-> bindParam(":data", $data);
    $sentencia-> bindParam(":adversario", $adv);
    $sentencia-> bindParam(":local", $local);
    $sentencia-> bindParam(":placar", $placar);
    $sentencia-> bindParam(":placar_adv", $placar_adv);
    $sentencia-> bindParam(":id",$txtID);
    $sentencia-> bindParam(":temporada", $temporada);
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
    <div class="card-header">Atualizar Jogo</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                    <label for="" class="form-label">ID</label>
                    <input
                        type="text"
                        value="<?php echo $txtID; ?>"
                        class="form-control"
                        readonly
                        name="txtID"
                        id="txtID"
                        aria-describedby="helpId"
                        placeholder=""
                    />
                </div>
                        <div class="mb-3">
                <label for="" class="form-label">Data</label>
                <input
                    type="date"
                    class="form-control"
                    name="data"
                    id="data"
                    aria-describedby="helpId"
                    placeholder="Data"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Adversario</label>
                <input
                    type="text"
                    class="form-control"
                    name="adversario"
                    value="<?php echo $adversario?>"
                    id="adversario"
                    aria-describedby="helpId"
                    placeholder="adversario"
                />
            </div>
            <div class="mb-3">
                <label for="escolaridade" class="form-label">Local</label>
                <select class="form-select" name="local" id="local" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $opcoesLocal = getEnumValues($conexion, 'jogo', 'local');
                    foreach ($opcoesLocal as $opcao) {
                        echo "<option value='$opcao'>$opcao</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Placar</label>
                <input
                    type="int"
                    class="form-control"
                    name="placar"
                    value="<?php echo $placar?>"
                    id="placar"
                    aria-describedby="helpId"
                    placeholder="placar"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Placar adversario</label>
                <input
                    type="int"
                    class="form-control"
                    name="placar_adv"
                    value="<?php echo $placar_adv?>"
                    id="placar_adv"
                    aria-describedby="helpId"
                    placeholder="placar adversario"
                />
            </div>
            <button
                type="submit"
                class="btn btn-success"
            >
                Adicionar Jogo
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