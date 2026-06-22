
<?php 

include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
} 


if(isset($_GET['txtID'])){ 

    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : ""; 
    $sentencia = $conexion->prepare("SELECT * FROM staff WHERE id = :id"); 
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute(); 
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC); 
    if(!$registro) { 
        header("Location: index.php"); 
        exit(); 
    } 
    $nome = $registro['nome']; 
    $cargo = $registro['cargo']; 

} 


if($_POST){ 

    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : ""; 
    $nome = (isset($_POST["nome"]) ? $_POST["nome"] : ""); 
    $cargo = (isset($_POST["cargo"]) ? $_POST["cargo"] : ""); 
    $sentencia = $conexion->prepare("UPDATE staff SET nome = :nome, cargo = :cargo WHERE id = :id"); 
    $sentencia->bindParam(":nome", $nome); 
    $sentencia->bindParam(":cargo", $cargo); 
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute(); 
    header("Location: index.php"); 
    exit(); 

} 


?> 


<?php include("../../template/header.php");?> 

<br/> 

<div class="card"> 

    <div class="card-header">Editar Dados do Staff</div> 

    <div class="card-body"> 

        <form action="" method="post"> 

            <input type="hidden" name="txtID" value="<?php echo $txtID; ?>"> 


            <div class="mb-3"> 

                <label for="nome" class="form-label">Nome Completo</label> 

                <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $nome; ?>" required /> 

            </div> 

            <div class="mb-3"> 

                <label for="nome" class="form-label">Cargo</label> 

                <input type="text" class="form-control" name="cargo" id="cargo" value="<?php echo $cargo; ?>" required /> 

            </div> 

            <button type="submit" class="btn btn-success">Guardar Alterações</button> 

            <a class="btn btn-danger" href="index.php" role="button">Cancelar</a> 

        </form> 

    </div> 

</div> 

<?php include("../../template/footer.php");?>