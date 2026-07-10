<?php  

include("../../db.php");
include("../../verificao_sessao.php");
if($_SESSION['tipo'] !== "admin"){
    header("Location:../../index.php");
    exit();
}

if($_POST){ 

    $username = (isset($_POST["user"]) ?$_POST["user"] : ""); 
    $email = (isset($_POST["email"]) $_POST["email"] : ""); 
    $password = (isset($_POST["password"]) $_POST["password"] : ""); 
    $tipo = (isset($_POST["tipo"]) ?$_POST["tipo"] : ""); 

    if(!empty($username) && !empty($email) && !empty($password) && !empty($tipo)){ 
        $passwordSecure = password_hash($password, PASSWORD_BCRYPT); 
        $sentencia = $conexion->prepare("INSERT INTO users (user, password, email, tipo) VALUES (:user, :password, :email, :tipo)"); 

        
        $sentencia->bindParam(":user", $username); 
        $sentencia->bindParam(":password", $passwordSecure); 
        $sentencia->bindParam(":email", $email); 
        $sentencia->bindParam(":tipo", $tipo); 

        if($sentencia->execute()){ 
            header("Location: index.php"); 
            exit(); 
        } else { 
            // 2. Mostrar o erro real que o banco de dados devolveu
            $erroDB = $sentencia->errorInfo();
            $erro = "Erro no banco de dados: " . $erroDB[2]; 
        } 
    } else { 
        $erro = "Por favor, preencha todos os campos corretamente."; 
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

    <div class="card-header">Criar Novo Utilizador</div> 

    <div class="card-body"> 


        <?php if(isset($erro)){ ?> 

            <div class="alert alert-danger" role="alert"> 

                <?php echo $erro; ?> 

            </div> 

        <?php } ?> 

        <form action="" method="post"> 

            <div class="mb-3"> 

                <label for="username" class="form-label">Username</label> 

                <input type="text" class="form-control" name="user" id="user" required /> 

            </div> 

            <div class="mb-3"> 

                <label for="email" class="form-label">Email</label> 

                <input type="email" class="form-control" name="email" id="email" required /> 

            </div> 

            <div class="mb-3"> 

                <label for="password" class="form-label">Senha</label> 

                <input type="password" class="form-control" name="password" id="password" required /> 

            </div> 

            <div class="mb-3"> 

                <label for="tipo" class="form-label">Tipo de Utilizador</label> 

                <select class="form-select" name="tipo" id="tipo" required> 

                    <option value="">Selecione o tipo...</option> 

                    <?php  

                    $opcoesTipo = getEnumValues($conexion, 'users', 'tipo'); 

                    foreach ($opcoesTipo as $opcao) { 

                        echo "<option value='$opcao'>$opcao</option>"; 

                    } 

                    ?> 

                </select> 

            </div> 

            <button type="submit" class="btn btn-success">Submeter</button> 

            <a class="btn btn-danger" href="index.php" role="button">Cancelar</a> 

        </form> 

    </div> 

</div> 

<?php include("../../template/footer.php");?> 