<?php
/* RECEBENDO DADOS DO FORMULÁRIO VIA POST */
$id       = 5 /* $_POST["id"] */      ;
$usuario  = 5 /* $_POST["usuario"] */ ;
$postagem = $_POST["postagem"];
$imagem   = "afs" /* $_POST["imagem"]  */ ;
$curtidas = 10 /* $_POST["curtidas"] */;
echo ($postagem);


/* ESTABELECENDO CONEXÃO */

function conectDB(){    
    $dns  = "mysql:host=localhost; dbname=posts";
    $user = "root";
    $key  = ""; 
    try {                
        $conexao = new PDO($dns, $user, $key);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->exec("set names utf8");
        echo "conected";
        return $conexao;
    } catch (PDOException $erro) {
        echo "Erro na conexão:" . $erro->getMessage();
    }     

}


/* INSERINDO NO BANCO OS DADOS*/


function insertPost($conexao, $user, $post, $img, $like){    
    $dataAtual = date("d/m/Y");
    echo("oi");
    try {
        $stmt = $conexao->prepare("INSERT INTO POSTAGENS ( ID_USUARIO, CONTEUDO_POST, IMAGEM_POST, DATA_POST, LIKES) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $post);
        $stmt->bindParam(3, $img);
        $stmt->bindParam(4, $dataAtual);
        $stmt->bindParam(5, $like);
    
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {    
                echo 
                "<script>                         
                    document.location.href='../post.html' 
                    alert('Cadastrado com sucesso!');
                </script>";
            
            } else {                
                echo 
                "<script>                         
                    document.location.href='../post.html' 
                    alert('Erro ao tentar efetivar cadastro!');
                </script>";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo 
        "<script>                         
            document.location.href='../post.html' 
            alert('Erro: ".$erro->getMessage()."!');
        </script>";        
    }
}


// isset retorna true para caso o campo existir, caso a input não exista o isset retorna falso
if(isset($postagem) && $postagem != null){
    if(isset($usuario) && $usuario != null){
        if(isset($imagem) && $imagem != null){
            insertPost(conectDB(), $usuario, $postagem, $imagem, $curtidas);
        }else{            
            echo 
            "<script>                         
                document.location.href='../post.html' 
                alert('Preencha corretamente!!!');
            </script>"; 
        }        
    }else{        
        echo 
        "<script>                         
            document.location.href='../post.html' 
            alert('Preencha corretamente!!!');
        </script>"; 
    }
}else{    
    echo 
    "<script>                         
        document.location.href='../post.html' 
        alert('Preencha corretamente!!!');
    </script>"; 
}


?>