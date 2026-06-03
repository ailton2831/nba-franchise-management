<?php 
include("../../db.php");
if(isset($_GET['txtID'])){
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $sentencia=$conexion->prepare("SELECT * FROM jogadores WHERE id=:id");
    $sentencia-> bindParam(":id", $txtID);
    $sentencia->execute();
    $registo=$sentencia->fetch(PDO::FETCH_LAZY);

    $nome=$registo['nome'];
    $numero=$registo['apelido'];
    $foto=$registo['foto'];
    $data_nascimento=$registo['data_ingresso'];
    $altura=$registo['altura'];
    $peso=$registo['peso'];
    $posicao=$registo['posicao'];

}

if($_POST){
    print_r($_POST);
    print_r($_FILES);
    $txtID = (isset($_GET['txtID'])?$_GET['txtID']:"");
    $nome = (isset($_POST["nome"])?$_POST["nome"]:"");
    $numero = (isset($_POST["numero"])?$_POST["numero"]:"");
    $foto = (isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
    $data_nascimento = (isset($_POST["data_nascimento"])?$_POST["data_nascimento"]:"");
    $peso = (isset($_POST["peso"])?$_POST["peso"]:"");
    $altura = (isset($_POST["altura"])?$_POST["altura"]:"");
    $posicao = (isset($_POST["posicao"])?$_POST["posicao"]:"");



    $sentencia=$conexion->prepare("UPDATE jogadores SET nome=:nome, numero=:numero, posicao=:posicao, altura=:altura, peso=:peso, data_nascimento=:data_nascimento WHERE id=:id ");
    
    $sentencia-> bindParam(":id",$txtID);
    $sentencia-> bindParam(":nome", $nome);
    $sentencia-> bindParam(":numero", $numero);
    $sentencia-> bindParam(":data_nascimento", $data_nascimento);
    $sentencia-> bindParam(":altura", $altura);
    $sentencia-> bindParam(":peso", $peso);
    $sentencia-> bindParam(":posicao", $posicao);
    $sentencia->execute();
    
    $foto = (isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
    
    $data_ = new DateTime();

    $nomeficheiro_foto=($foto!='')?$data_->getTimestamp()."_".$_FILES["foto"]['name']:"";
    $tmp_foto=$_FILES["foto"]['tmp_name'];
    if($tmp_foto!=''){
        move_uploaded_file($tmp_foto,"./".$nomeficheiro_foto);
        $sentencia=$conexion->prepare("SELECT foto FROM jogadores WHERE id=:id");
        $sentencia-> bindParam(":id",$txtID);
        $sentencia->execute();
        $registo_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

        if(isset($registo_recuperado["foto"]) && $registo_recuperado["foto"]!=""){
            if(file_exists("./".$registo_recuperado["foto"])){
                unlink("./".$registo_recuperado["foto"]);
            }
        }
        $sentencia=$conexion->prepare("UPDATE jogadores SET foto=:foto WHERE id=:id");
        $sentencia-> bindParam(":id",$txtID);
        $sentencia-> bindParam(":foto", $nomeficheiro_foto);
        $sentencia->execute();
    }
    
    
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
    <div class="card-header">Atualizar Jogadores</div>
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
                <label for="" class="form-label">Nome</label>
                <input
                    type="text"
                    class="form-control"
                    name="nome"
                    value="<?php echo $nome?>"
                    id="nome"
                    aria-describedby="helpId"
                    placeholder="Nome"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Escolher Fotografia</label>
                <img
                                src="<?php echo $registo['foto'] ;?>"
                                class="img-fluid rounded-top"
                                alt=""
                                width = "75"
                            />
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
                <label for="" class="form-label">Número</label>
                <input
                    type="text"
                    class="form-control"
                    name="numero"
                    value="<?php echo $numero?>"
                    id="numero"
                    aria-describedby="helpId"
                    placeholder="Numero"
                />
            </div>
            <div class="mb-3">
                <label for="posicao" class="form-label">Posição</label>
                <select class="form-select" name="posicao" id="posicao" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $opcoesPosicao = getEnumValues($conexion, 'jogadores', 'posicao');
                    foreach ($opcoesPosicao as $opcao) {
                        $selected = ($opcao == $posicao) ? "selected" : "";
                        echo "<option value='$opcao' $selected>$opcao</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Altura</label>
                <input
                    type="double"
                    class="form-control"
                    name="altura"
                    value="<?php echo $altura?>"
                    id="altura"
                    aria-describedby="helpId"
                    placeholder="Altura(m)"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Peso</label>
                <input
                    type="double"
                    class="form-control"
                    name="peso"
                    value="<?php echo $peso?>"
                    id="peso"
                    aria-describedby="helpId"
                    placeholder="Peso(kg)"
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
                Adicionar Funcionário
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