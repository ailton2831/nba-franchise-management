<?php 

include("../../db.php"); 
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if(isset($_GET['txtID'])){ 
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : ""; 
    $sentencia = $conexion->prepare("DELETE FROM users WHERE id = :id"); 
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute(); 
    header("Location: index.php"); 
    exit(); 
} 


$sentencia = $conexion->prepare("SELECT * FROM `users`"); 

$sentencia->execute(); 

$lista_utilizadores = $sentencia->fetchAll(PDO::FETCH_ASSOC); 

?> 


<?php include("../../template/header.php");?> 

<br/> 

<div class="card"> 

    <div class="card-header d-flex justify-content-between align-items-center"> 

        <span>Gestão de Utilizadores do Sistema</span> 

        <a class="btn btn-primary" href="create.php" role="button">Criar Utilizador</a> 

    </div> 

    <div class="card-body"> 

        <div class="table-responsive-md"> 

            <table class="table table-hover"> 

                <thead class="table-dark"> 

                    <tr> 

                        <th scope="col">Username</th> 

                        <th scope="col">Email</th> 

                        <th scope="col">Tipo de Conta</th> 

                        <th scope="col">Ações</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php foreach($lista_utilizadores as $registro) { ?> 
                    <tr> 

                        <td><?php echo $registro['user']; ?></td> 

                        <td><?php echo $registro['email']; ?></td> 

                        <td><span class="badge bg-info text-dark"><?php echo $registro['tipo']; ?></span></td> 

                        <td> 
                            <a
                                name=""
                                id=""
                                class="btn btn-success"
                                href="update.php?txtID=<?php echo $registro['id'];?>"
                                role="button"
                                >Update</a
                                > 

                            <a
                                name=""
                                id=""
                                class="btn btn-danger"
                                href="javascript:eliminar(<?php echo $registro['id'];?>);"
                                role="button"
                                >Delete</a
                                >

                        </td> 

                    </tr> 

                    <?php } ?> 

                </tbody> 

            </table> 

        </div> 

    </div> 

</div> 

<?php include("../../template/footer.php");?> 