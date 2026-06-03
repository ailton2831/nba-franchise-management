<?php 
include("../../db.php");

if($_POST){
    $nome = (isset($_POST["nome"])?$_POST["nome"]:"");
    $numero = (isset($_POST["numero"])?$_POST["numero"]:"");
    $foto = (isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
    $data_nascimento = (isset($_POST["data_nascimento"])?$_POST["data_nascimento"]:"");
    $peso = (isset($_POST["peso"])?$_POST["peso"]:"");
    $altura = (isset($_POST["altura"])?$_POST["altura"]:"");
    $posicao = (isset($_POST["posicao"])?$_POST["posicao"]:"");



    $sentencia=$conexion->prepare("INSERT INTO jogadores (id,nome, numero ,foto, data_nascimento, altura, posicao, peso) VALUES (null, :nome, :numero, :foto, :data_nascimento, :altura,:posicao, :peso)");
    
    $data_ = new DateTime();

    $nomeficheiro_foto=($foto!='')?$data_->getTimestamp()."_".$_FILES["foto"]['name']:"";
    $tmp_foto=$_FILES["foto"]['tmp_name'];
    if($tmp_foto!=''){move_uploaded_file($tmp_foto,"./".$nomeficheiro_foto);}
    
    $sentencia-> bindParam(":nome", $nome);
    $sentencia-> bindParam(":numero", $numero);
    $sentencia-> bindParam(":foto", $nomeficheiro_foto);
    $sentencia-> bindParam(":data_nascimento", $data_nascimento);
    $sentencia-> bindParam(":altura", $altura);
    $sentencia-> bindParam(":peso", $peso);
    $sentencia-> bindParam(":posicao", $posicao);
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
    <div class="card-header">Registar Jogadores</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="" class="form-label">Nome</label>
                <input
                    type="text"
                    class="form-control"
                    name="nome"
                    id="nome"
                    aria-describedby="helpId"
                    placeholder="Nome"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Escolher Fotografia</label>
                <input
                    type="file"
                    class="form-control"
                    name="foto"
                    id="foto"
                    aria-describedby="helpId"
                    placeholder="Foto"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Numero</label>
                <input
                    type="number"
                    class="form-control"
                    name="numero"
                    id="numero"
                    aria-describedby="helpId"
                    placeholder="Numero da camisa"
                />
            </div>
            <div class="mb-3">
                <label for="posicao" class="form-label">Posição</label>
                <select class="form-select" name="posicao" id="posicao" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $opcoesPosicao = getEnumValues($conexion, 'jogadores', 'posicao');
                    foreach ($opcoesPosicao as $opcao) {
                        echo "<option value='$opcao'>$opcao</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Peso</label>
                <input
                    type="number"
                    class="form-control"
                    name="peso"
                    step="0.01"
                    id="peso"
                    aria-describedby="helpId"
                    placeholder="Peso(kg)"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Altura</label>
                <input
                    type="number"
                    class="form-control"
                    name="altura"
                    step="0.01"
                    id="altura"
                    aria-describedby="helpId"
                    placeholder="altura(m)"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Data de Nascimento</label>
                <input
                    type="date"
                    class="form-control"
                    name="data_nascimento"
                    id="data_nascimento"
                    aria-describedby="helpId"
                    placeholder="Data de Nascimento"
                />
            </div>
            <button
                type="submit"
                class="btn btn-success"
            >
                Adicionar Jogador
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