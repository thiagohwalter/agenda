<?php
//Função que cadastrará o usuário;
function cadastrar($nome,$email,$telefone){
    //ira pegar os arquivos "contatos.json", decodifica-los e retornará aos usuários;
    $contatosAuxiliar = pegarContatos();
    // $contato recebe parametros do formulário.
    $contato = [
        'id'      => uniqid(),
        'nome'    => $nome,
        'email'   => $email,
        'telefone'=> $telefone
    ];
    //Array_push ira pegar $contato e colocará no final do $contatosAuxiliar, que no caso, é um arquivo "contatos.json" decodificado;
    array_push($contatosAuxiliar, $contato);
    //Atualiza o arquivo;
    atualizarArquivo($contatosAuxiliar);
}

//Função pegarContatos pega os contatos do arquivo contatos.json;
function pegarContatos($valor_buscado = null){

    if ($valor_buscado == null){
        //Pega arquivo "contatos.json";
        $contatosAuxiliar = file_get_contents('contatos.json');
        //decodifica o arquivo;
        $contatosAuxiliar = json_decode($contatosAuxiliar, true);
        //retorna o arquivo;
        return $contatosAuxiliar;
    } else {
        return buscarContato($valor_buscado);
    }
}

//Função que exclui os contatos;
function excluirContato($id){
     //Chama a função que irá pegar os contatos;
    $contatosAuxiliar = pegarContatos();
    //Em cada contatoAuxiliar,o dado é pego do contato na posição que está portanto:
    foreach ($contatosAuxiliar as $posicao => $contato){
    //Se a a variável id (['id']) do contato é igual a variável id que estou procurando...
        if($id == $contato['id']) {
    //excluir os dados do contato pelo id;
            unset($contatosAuxiliar[$posicao]);
        }
    }

    atualizarArquivo($contatosAuxiliar);
}
//Função que edita o contato;
function editarContato($id){
    //Pega os contatos;
    $contatosAuxiliar = pegarContatos();
    //Para cada contatoAuxiliar como contato ira fazer...;
    foreach ($contatosAuxiliar as $contato){
     //Se o id do contato for o que procuro
        if ($contato['id'] == $id){
     //ira retornar os contatos com seus dados (que estao no caso, em editar.php)
            return $contato;
        }
    }
}
//Função para Salvar o contato que foi editado;
function salvarContatoEditado($id){
    //Pega os contatos
    $contatosAuxiliar = pegarContatos();
    //Para cada contatoAuxiliar como a posição do array contato...;
    foreach ($contatosAuxiliar as $posicao => $contato){
    //Se o id do contato é o id que estou tentando encontrar...;
        if ($contato['id'] == $id){
        //Então será  editado os dados do contato;
            $contatosAuxiliar[$posicao]['nome'] = $_POST['nome'];
            $contatosAuxiliar[$posicao]['email'] = $_POST['email'];
            $contatosAuxiliar[$posicao]['telefone'] = $_POST['telefone'];
            break;
        }
    }
    //Atualizará o arquivo;
    atualizarArquivo($contatosAuxiliar);
}
//Função para Atualizar o arquivo;
function atualizarArquivo($contatosAuxiliar){
    //depois que for cadastrado,editado ou excluído o usuário, o arquivo "contatos.json" é codificado novamente;
    $contatosJson = json_encode($contatosAuxiliar, JSON_PRETTY_PRINT);
    //irá receber todos os dados de usuário que estarão contidos no arquivo "contatos.json", trocando os dados pelos que haviam anteriormente;
    file_put_contents('contatos.json', $contatosJson);
    //O usuario sera redirecionado para a pagina inicial ;
    header("Location: index.phtml");
}
//Função que busca um contato pelo nome;
function buscarContato($nome){
    //Pega os contatos;
    $contatosAuxiliar = pegarContatos();

    $contatosEncontrados = [];

    //Para cada contatoAuxiliar com o valor contato;
    foreach ($contatosAuxiliar as $contato){
        //Se e o id do contato for o mesmo que procuro
        if ($contato['nome'] == $nome){
            //retorne o contato com os dados;
            $contatosEncontrados[] = $contato;
        }
    }

    return $contatosEncontrados;
}
//ROTAS
switch($_GET['acao']){
    case "cadastrar":
    cadastrar($_POST['nome'],$_POST['email'],$_POST['telefone']);
        break;
    case "editar":
        salvarContatoEditado($_POST['id']);
        break;
    case "excluir":
        excluirContato($_GET['id']);
        break;
}