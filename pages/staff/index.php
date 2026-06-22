<?php 

include("../../db.php"); 
include("../../verificao_sessao.php");

if(isset($_GET['txtID'])){ 

    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : ""; 
    $sentencia = $conexion->prepare("DELETE FROM staff WHERE id = :id"); 
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute(); 
    header("Location: index.php"); 
    exit(); 

} 


$sentencia = $conexion->prepare("SELECT * FROM `staff` "); 

$sentencia->execute(); 

$lista_staff = $sentencia->fetchAll(PDO::FETCH_ASSOC); 

?> 


<?php include("../../template/header.php");?> 

<br/> 

<div class="card"> 

    <div class="card-header d-flex justify-content-between align-items-center"> 

        <span>Administração do Staff da Equipa</span> 
        <?php if($_SESSION['tipo'] == "admin"){ ?>
        <a name="" id="" class="btn btn-primary" href="create.php" role="button">Adicionar Membro</a> 
        <?php } ?>

    </div> 

    <div class="card-body"> 

        <div class="table-responsive-md"> 

            <table class="table table-hover"> 

                <thead class="table-dark"> 

                    <tr> 

                        <th scope="col">ID</th> 
                        <th scope="col">Nome</th> 
                        <th scope="col">Cargo</th> 
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                        <th scope="col">Ações</th> 
                        <?php endif; ?>

                    </tr> 

                </thead> 

                <tbody> 

                    <?php foreach($lista_staff as $registro) { ?> 

                    <tr> 

                        <td><?php echo $registro['id']; ?></td> 

                        <td><?php echo $registro['nome']; ?></td> 

                        <td><?php echo $registro['cargo']; ?></td> 
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
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
                        <?php endif; ?>
                    </tr> 

                    <?php } ?> 

                </tbody> 

            </table> 

        </div> 

    </div> 

</div> 

<?php include("../../template/footer.php");?> 