<?php  

include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if($_POST){ 

    $nome = (isset($_POST["nome"]) ? $_POST["nome"] : ""); 
    $cargo = (isset($_POST["cargo"]) ? $_POST["cargo"] : ""); 
    $sentencia = $conexion->prepare("INSERT INTO staff (id, nome, cargo) VALUES (null, :nome, :cargo)"); 
    $sentencia->bindParam(":nome", $nome); 
    $sentencia->bindParam(":cargo", $cargo); 
    $sentencia->execute(); 
    header("Location: index.php"); 
    exit(); 

} 

?> 



<?php include("../../template/header.php");?> 

<br/> 

<div class="card"> 

    <div class="card-header">Registar Membro do Staff</div> 

    <div class="card-body"> 

        <form action="" method="post"> 

            <div class="mb-3"> 

                <label for="nome" class="form-label">Nome</label> 

                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do membro" required /> 

            </div> 

            <div class="mb-3"> 

                <label for="nome" class="form-label">Cargo</label> 

                <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Cargo" required /> 

            </div> 

            <button type="submit" class="btn btn-success">Adicionar Staff</button> 

            <a class="btn btn-danger" href="index.php" role="button">Cancelar</a> 

        </form> 

    </div> 

</div> 

<?php include("../../template/footer.php");?> 

