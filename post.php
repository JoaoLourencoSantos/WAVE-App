<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/post.css">
    <link rel="icon" type="imagem/png" href="assets/icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <title>JÉRIKA</title>
</head>

<body>
    

    <header class="header">
        <a href="index.html">
            <img src="assets/logo2.png" alt="" id="logo">
        </a>
        <nav>
            <ul>
                <li> <a href="index.html">Home</a></li>
                <li> <a href="post.php">Posts </a></li>
                <li> <a href="sobre.html">Sobre Nós</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <header>
                <span style="font-weight: bold;">Postagens</span>                
            </header>
            <main style="font-weight: bold;">
                    <?php
                    // importando os objetos que precisarei, como conexão para fazer leitura
                    require('PHP/conexao.php');
                    //Try que roda o tamanho do banco e retorna dos dados em suas respectivas posições 
                    try {
                        $stmt = conectDB()->prepare("SELECT * FROM POSTAGENS");
                        if ($stmt->execute()) {
                            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                                //pegando parte da string da imagem, tirando o desnecessário
                                $nome = $rs->IMAGEM_POST;
                                $parte = substr($nome, 3);
                                $categoria="";
                                if($rs->CATEGORIA_POST == 1){
                                    $categoria="Esporte";
                                }elseif($rs->CATEGORIA_POST ==2){
                                    $categoria="Filme";
                                }elseif($rs->CATEGORIA_POST ==3){
                                    $categoria="Livro";
                                }elseif($rs->CATEGORIA_POST ==4){
                                    $categoria="Moda";
                                }elseif($rs->CATEGORIA_POST ==5){
                                    $categoria="Música";
                                }else{
                                    $categoria="Série";
                                }

                                //imprimindo os articles/posts
                                echo "<article>"
                                        ."<div style=' width:50%; height:450px; ' >"
                                            ."<img src='".$parte."' alt='Olá'>"
                                        ."</div>"
                                        ."<div>"
                                            ."<div>"
                                                ."<span>".$rs->TITULO_POST."</span>" 
                                                ."<span>".$rs->DATA_POST."</span>"

                                            ."</div>"
                                            ."<span>".$rs->CONTEUDO_POST."</span>"
                                            ."<div id='footer'>"
                                                
                                                ."<form id='coments' method='POST' action='post.php'>"
                                                    ."<div>"
                                                        ."<span> 0 </span> <img src='assets/icon-like.png' title='Curtir postagem!'> "
                                                    ."</div>"
                                                    ."<div>"
                                                        ."<span> 0 </span>"
                                                        ."<img src='assets/icon-chat.png' title='Comentar'>"
                                                    ."</div>"
                                                    ."<textarea id='comentario' name='comentario' placeholder='Comentar'></textarea>"                                                    
                                                    ."<div>"                                                    
                                                        ."<label id='salve' for='btn-salvar'>"                                                
                                                            ."<img src='assets/icon-success.png' title='Salvar comentário!'>"
                                                        ."<label/>"
                                                        ."<input hidden type='number' value='".$rs->ID_POST."' name='id-post'>"
                                                        ."<input hidden type='number' value='".$rs->ID_USUARIO."' name='id-user'>"
                                                        ."<input id='btn-salvar' type='submit'>"
                                                    ."</div>"
                                                ."</form>"    
                                                ."<span >".$categoria."</span>"

                                            ."</div>"
                                        ."</div>"
                                    ."</article>";
                            }
                        } else {
                            echo "Erro: Não foi possível recuperar os dados do banco de dados";
                        }
                    } catch (PDOException $erro) {
                        echo "Erro: ".$erro->getMessage();
                    }
                   
                    
                    
                    ?>     
            </main>

        </section>

        <section>

            <form id="conteudo1" action="PHP/conexao.php?type=1" method="POST"  enctype="multipart/form-data" >
                <div id="titulo-comentario"> <textarea id="titulo-postagem" name="titulo" placeholder="Título"cols="30" rows="5"></textarea></div>
                <input hidden type="number" value="5"  name="id-post">
                <input hidden type="number" value="5"  name="id-user">
                <input hidden type="number" value="10" name="curtidas">
                <textarea maxlength="1290" id="postagem" name="postagem" placeholder="Sobre o que vamos falar hoje?"cols="30" rows="5"></textarea>
                <div id="form2">
                    <select name="categoria" id="categoria">
                        <option value="1"> Esporte </option>
                        <option value="2"> Filme   </option>
                        <option value="3"> Livro   </option>
                        <option value="4"> Moda    </option>
                        <option value="5"> Música  </option>
                        <option value="6"> Série   </option>
                    </select>

                    <label id="imagem" for='selecao-arquivo' >
                        <img src="assets/icon-image.png ">
                    </label>
                    <input id='selecao-arquivo' type='file' name="imagem">
                    <label id="save" for='salvar'>
                        <img src="assets/icon-success.png ">
                    </label>
                    <input id='salvar' type='submit'>
                </div>
                
                
            </form>


        </section>

    </main>
    <?php
        
         
        function insertComentario($conexao,$usuario, $id_post, $comentario){
            $dataAtual = date("d/m/Y");
            try{
                $stmt = $conexao->prepare("INSERT INTO COMENTARIOS(ID_USUARIO, ID_POST, CONTEUDO_COMENTARIO, DATA_COMENTARIO) VALUES ( ?, ?, ?, ?)");
                $stmt->bindParam(1, $usuario);
                $stmt->bindParam(2, $id_post);
                $stmt->bindParam(3, $comentario);
                $stmt->bindParam(4, $dataAtual); 
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {    
                        echo 
                        "<script> 
                            alert('Comentado com sucesso!');
                        </script>";                                         
                    } else {                
                        echo 
                        "<script> 
                            alert('Erro ao tentar comentar!');
                        </script>";
                    }
                } else {
                    throw new PDOException("Erro: Não foi possível executar a declaração sql");
                }
            }catch (PDOException $erro) {
                echo 
                "<script>                         
                    alert('Erro: ".$erro->getMessage()."!');
                </script>";        
            }
        }   
        
        function validInputComents(){ 
            $usuario     = $_POST["id-user"]    ;
            $id_post     = $_POST["id-post"]    ;
            $comentario  = $_POST["comentario"] ;
        
            
            if(isset($comentario) && $comentario != null){
                if(isset($usuario) && $usuario != null){
                    if(isset($id_post) && $id_post != null){
                        insertComentario(conectDB(),$usuario, $id_post, $comentario);                          
                        $usuario     = null ;
                        $id_post     = null ;
                        $comentario  = null ;                         
                    }else{
                        echo 
                        "<script>                         
                            alert('Preencha corretamente1!!!');
                        </script>";             
                    }        
                }else{
                    echo 
                    "<script>                         
                        alert('Preencha corretamente2!!!');
                    </script>";         
                }
            }else{
                echo 
                "<script>                         
                    alert('Preencha corretamente3!!!');
                </script>";     }
        }
        
    ?>                
    <script>
        document.getElementById('btn-salvar').addEventListener("click", function(){
            <?php
                validInputComents();
            ?>
            document..getElementById('coments').reset();
        });
        
    </script>
</body>

</html>