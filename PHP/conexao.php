<?php
/* RECEBENDO DADOS DO FORMULÁRIO VIA POST */

if(isset($_REQUEST["type"]) && $_REQUEST["type"] == 1 ){
    validInputPosts();
}

$errorImage    = "";
$caminhoImagem = "";
function returnImage($img){
    
         // Verifica se o arquivo é uma imagem
    if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $img["type"])){
        $errorImage = "Isso não é uma imagem.";    
     }else{
        // Pega extensão da imagem
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $img["name"], $ext);
    
        // Gera um nome único para a imagem
        $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
    
        // Caminho de onde ficará a imagem
        $caminhoImagem = "../assets/fotos/" . $nome_imagem;
    
        // Faz o upload da imagem para seu respectivo caminho
        move_uploaded_file($img["tmp_name"], $caminhoImagem);
        
        return $caminhoImagem;  
    }
}





/* ESTABELECENDO CONEXÃO */

function conectDB(){    
    $dns  = "mysql:host=localhost; dbname=posts";
    $user = "root";
    $key  = ""; 
    try {                
        $conexao = new PDO($dns, $user, $key);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->exec("set names utf8");
        return $conexao;
    } catch (PDOException $erro) {
        echo "Erro na conexão:" . $erro->getMessage();
    }     

}


/* INSERINDO NO BANCO OS DADOS*/


function insertPost($conexao, $user, $title, $post, $img, $like, $type){  
    // recebendo a data atual para qual o post foi escrito
    $dataAtual = date("d/m/Y");
    // Try-Catch é utilizado para o tratamento de exceções, ou seja, erros que ocorrem em tempo de execução e podem derrubar a aplicação. 
    // Com o bloco “try” “captura” e pode identificar o erro, e tratá-lo da maneira que achar mais conveniente no bloco “catch”.
    try{
        // moldando o sql à ser executado durante a conexão com o banco
        $stmt = $conexao->prepare("INSERT INTO POSTAGENS ( ID_USUARIO, TITULO_POST ,CONTEUDO_POST, IMAGEM_POST, DATA_POST, CATEGORIA_POST, LIKES) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // preenchendo os parametros de entrada com os valores provindos do formulário
        //  -> == simbolo utilizado para chamar um método de uma classe php
        $stmt->bindParam(1, $user); // parametros
        $stmt->bindParam(2, $title);
        $stmt->bindParam(3, $post);
        $stmt->bindParam(4, $img);
        $stmt->bindParam(5, $dataAtual);
        $stmt->bindParam(6, $type);
        $stmt->bindParam(7, $like);
        // testando se a execução do sql retornou um valor true, se retornou..
        if ($stmt->execute()) 
        {
            // rowCount => confere o numero de linhas afetadas pelo delete, insert ou update, caso seja == 0 , ele não conseguiu fazer modificações no banco
            if ($stmt->rowCount() > 0) 
            {    
                echo 
                "<script>                         
                    document.location.href='../post.php' 
                    alert('Cadastrado com sucesso!');
                </script>";
            
            }else 
            {                
                echo 
                "<script>                         
                    document.location.href='../post.php' 
                    alert('Erro ao tentar efetivar cadastro!');
                </script>";
            }
        }else {
            // retorno de msg de erro
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    }catch (PDOException $erro) {
        echo 
        "<script>                         
            document.location.href='../post.php' 
            alert('Erro: ".$erro->getMessage()."!');
        </script>";        
    }
}



// isset retorna true para caso o campo existir, caso a input não exista o isset retorna falso

function validInputPosts(){ 
    $id        = $_POST["id-post"]   ;
    $usuario   = $_POST["id-user"]   ;
    $titulo    = $_POST["titulo"]    ;
    $postagem  = $_POST["postagem"]  ;
    $categoria = $_POST["categoria"] ;
    $curtidas  = $_POST["curtidas"]  ;
    $imagem    = $_FILES["imagem"]   ;    

    
    if(isset($postagem) && $postagem != null){
        if(isset($usuario) && $usuario != null){
            if(isset($imagem) && $imagem != null){
                insertPost(conectDB(), $usuario, $titulo, $postagem, returnImage($imagem), $curtidas, $categoria);
            }else{
                echo 
                "<script>                         
                    document.location.href='../post.php' 
                    alert('Preencha corretamente!!!');
                </script>";             
            }        
        }else{
            echo 
            "<script>                         
                document.location.href='../post.php' 
                alert('Preencha corretamente!!!');
            </script>";         }
    }else{
        echo 
        "<script>                         
            document.location.href='../post.php' 
            alert('Preencha corretamente!!!');
        </script>";     }
}



?>