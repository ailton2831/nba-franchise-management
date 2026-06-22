<?php 

include("../../db.php"); 
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if(isset($_GET['txtID'])){ 

    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : ""; 
    $sentencia = $conexion->prepare("SELECT * FROM users WHERE id = :id"); 
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute(); 
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC); 
    if(!$registro) { 
        header("Location: index.php"); 
        exit(); 
    } 
    $username = $registro['user']; 
    $email = $registro['email']; 
    $tipo_atual = $registro['tipo']; 
    $password_atual = $registro['password']; 
} 

if($_POST){ 

    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : ""; 
    $email = (isset($_POST["email"]) ? $_POST["email"] : ""); 
    $username = (isset($_POST["user"]) ? $_POST["user"] : ""); 
    $password_nova = (isset($_POST["password"]) ? $_POST["password"] : ""); 

    $tipo = (isset($_POST["tipo"]) ? $_POST["tipo"] : ""); 


    if(!empty($email) && !empty($tipo)) { 
        if(!empty($password_nova)) { 

            $passwordFinal = password_hash($password_nova, PASSWORD_BCRYPT); 

        } else { 

            $passwordFinal = $password_atual; 

        } 

 

        $sentencia = $conexion->prepare("UPDATE users SET user=:user, email = :email, password = :password, tipo = :tipo WHERE id = :id"); 

        $sentencia->bindParam(":email", $email); 
        $sentencia->bindParam(":user", $username); 
        $sentencia->bindParam(":password", $passwordFinal); 
        $sentencia->bindParam(":tipo", $tipo); 
        $sentencia->bindParam(":id", $txtID); 
        $sentencia->execute(); 

        header("Location: index.php"); 

        exit(); 

    } 

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

    <div class="card-header">Editar Utilizador</div> 

    <div class="card-body"> 

        <form action="" method="post"> 

            <input type="hidden" name="txtID" value="<?php echo $txtID; ?>"> 

            <div class="mb-3"> 

                <label class="form-label">Username</label> 

                <input type="text" class="form-control bg-light" name="user" id="user" value="<?php echo $username; ?>" /> 

            </div> 

            <div class="mb-3"> 

                <label for="email" class="form-label">Email</label> 

                <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" /> 

            </div> 



            <div class="mb-3"> 

                <label for="password" class="form-label">Senha</label> 

                <input type="password" class="form-control" name="password" id="password" placeholder="Nova senha opcional" /> 

            </div> 


            <div class="mb-3"> 

                <label for="tipo" class="form-label">Tipo de Utilizador</label> 

                <select class="form-select" name="tipo" id="tipo" required> 

                    <?php  

                    $opcoesTipo = getEnumValues($conexion, 'users', 'tipo'); 

                    foreach ($opcoesTipo as $opcao) { 

                        $selecionado = ($opcao == $tipo_atual) ? "selected" : ""; 

                        echo "<option value='$opcao' $selecionado>$opcao</option>"; 

                    } 

                    ?> 

                </select> 

            </div> 


            <button type="submit" class="btn btn-success">Submeter Alterações</button> 

            <a class="btn btn-danger" href="index.php" role="button">Cancelar</a> 

        </form> 

    </div> 

</div> 

<?php include("../../template/footer.php");?>