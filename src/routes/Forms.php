<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Get de usuarios
$app->get('/api/usuarios', function(Request $request, Response $response){
    $sql = "SELECT * FROM usuarios";

    try{
        // DB objeto
        $db = new db();
        //conexão
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($usuarios);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';

    }
});
    //Get single de usuario
$app->get('/api/usuario/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "SELECT * FROM usuarios WHERE id = $id";

    try{
        // DB objeto
        $db = new db();
        //conexão
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($usuario);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';

    }
});

    //Adicionar usuario
    $app->post('/api/usuario/add', function(Request $request, Response $response){
        $nome = $request->getParam('nome');
        $email = $request->getParam('email');
        $senha = $request->getParam('senha');
        $id_tipo_usuario = $request->getParam('id_tipo_usuario');
        
        $sql = "INSERT INTO usuarios (nome, email, senha, id_tipo_usuario) VALUES
        (:nome,:email,:senha, :id_tipo_usuario)";
        
    
        try{
            // DB objeto
            $db = new db();
            //conexão
            $db = $db->connect();
    
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam('id_tipo_usuario', $id_tipo_usuario);
        $stmt->execute();

        echo '{"notice": {"text": "Usuario adicionado!"}';

        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
    
        }
    });

     //Atualizar usuario
     $app->put('/api/usuario/update/{id}', function(Request $request, Response $response){
        $id = $request->getAttribute('id');
        $nome = $request->getParam('nome');
        $email = $request->getParam('email');
        $senha = $request->getParam('senha');
        $id_tipo_usuario = $request->getParam('id_tipo_usuario');

        
        $sql = "UPDATE usuarios SET
        nome = :nome,
        email = :email,
        senha = :senha,
        id_tipo_usuario = :id_tipo_usuario
        WHERE id = $id";
        
        try{
            // DB objeto
            $db = new db();
            //conexão
            $db = $db->connect();
    
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':id_tipo_usuario', $id_tipo_usuario);

        $stmt->execute();

        echo '{"notice": {"text": "Usuario atualizado!"}';

        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
    
        }
    });

    //Delete usuario
$app->delete('/api/usuario/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    
    $sql = "DELETE FROM usuarios WHERE id = $id";

    try{
        // DB objeto
        $db = new db();
        //conexão
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Usuario deletado!"}'; 
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';

    }
});