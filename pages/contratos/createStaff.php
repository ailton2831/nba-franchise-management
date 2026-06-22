<?php 
include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if($_POST){
    $inicio = (isset($_POST["inicio"])?$_POST["inicio"]:"");
    $fim = (isset($_POST["fim"])?$_POST["fim"]:"");
    $salario = (isset($_POST["salario"])?$_POST["salario"]:"");
    $id_staff = (isset($_POST["id_staff"])?$_POST["id_staff"]:"");
    $status = (isset($_POST["status"])?$_POST["status"]:"");

    $sentencia=$conexion->prepare("INSERT INTO contratostaff (id,data_inicio,data_final,salario,status,tipo,id_staff) VALUES (null, :inicio,:fim,:salario,:status,'staff', :id_staff )");
    $sentencia-> bindParam(":inicio", $inicio);
    $sentencia-> bindParam(":fim", $fim);
    $sentencia-> bindParam(":salario", $salario);
    $sentencia-> bindParam(":status", $status);
    $sentencia-> bindParam(":id_staff", $id_staff);
    $sentencia->execute();

    $_SESSION['alerta'] = [
        'icon'  => 'success',
        'title' => 'Adicionado!',
        'text'  => 'Novo registo criado com sucesso.'
    ];
    
    header("Location:indexstaff.php");
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


$sentencia=$conexion->prepare("SELECT * FROM `staff`");
$sentencia->execute();
$lista_staff=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../template/header.php");?>

<br/>
<div class="card">
    <div class="card-header">Criar Contrato</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_staff" class="form-label">Escolher Staff</label>
                <select
                    class="form-select form-select-lg"
                    name="id_staff"
                    id="id_staff"
                >
                    <option selected>Select one</option>
                    <?php foreach ($lista_staff as $registo){ ?>
                    <option value="<?php echo $registo['id'];?>">
                    <?php echo $registo['nome'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Data de inicio</label>
                <input
                    type="date"
                    class="form-control"
                    name="inicio"
                    id="inicio"
                    aria-describedby="helpId"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Data de fim</label>
                <input
                    type="date"
                    class="form-control"
                    name="fim"
                    id="fim"
                    aria-describedby="helpId"
                />
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Salario</label>
                <input
                    type="double"
                    class="form-control"
                    name="salario"
                    id="salario"
                    aria-describedby="helpId"
                    placeholder="Milhoes por ano"
                />
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $opcoesStatus = getEnumValues($conexion, 'contratostaff', 'status');
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