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

if($_POST){
    $data = (isset($_POST["data"])?$_POST["data"]:"");
    $adv = (isset($_POST["adversario"])?$_POST["adversario"]:"");
    $local = (isset($_POST["local"])?$_POST["local"]:"");
    $placar = (isset($_POST["placar"])?$_POST["placar"]:0);
    $placar_adv = (isset($_POST["placar_adv"])?$_POST["placar_adv"]:0);
    
    $dataObjeto = new DateTime($data); 
    $temporada = definirTemporada($dataObjeto);

    $sentencia=$conexion->prepare("INSERT INTO jogo (id,data,adversario,local,placar,placar_vs,temporada) VALUES (null, :data,:adversario,:local,:placar,:placar_adv,:temporada )");
    $sentencia-> bindParam(":data", $data);
    $sentencia-> bindParam(":adversario", $adv);
    $sentencia-> bindParam(":local", $local);
    $sentencia-> bindParam(":placar", $placar);
    $sentencia-> bindParam(":placar_adv", $placar_adv);
    $sentencia-> bindParam(":temporada", $temporada);
    $sentencia->execute();
    header("Location:index.php");
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
    <div class="card-header">Registar Jogo</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="" class="form-label">Data</label>
                <input
                    type="date"
                    class="form-control"
                    name="data"
                    id="data"
                    aria-describedby="helpId"
                    placeholder="data"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Adversario</label>
                <input
                    type="text"
                    class="form-control"
                    name="adversario"
                    id="adversario"
                    aria-describedby="helpId"
                    placeholder="Adversario"
                />
            </div>
            <div class="mb-3">
                <label for="posicao" class="form-label">Local</label>
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
                    type="number"
                    class="form-control"
                    name="placar"
                    id="placar"
                    min="0"
                    aria-describedby="helpId"
                    placeholder="Placar"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Placar adversario</label>
                <input
                    type="number"
                    class="form-control"
                    name="placar_adv"
                    id="placar_adv"
                    min="0"
                    aria-describedby="helpId"
                    placeholder="Placar adversario"
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