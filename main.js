// Implementa acesso para a tela de login
function acessar(){
    let loginEmail = document.getElementById('loginEmail').value;
    let loginSenha = document.getElementById('loginSenha').value;

    //Implementa a estrutura que testa se todos os campos foram preenchidos
    if(!loginEmail || !loginSenha){
        alert('Favor preencher todos os campos!');
        return;
    }else {
        window.location.href = 'cadastro.html';
    }
}

// Cria variável do tipo array
var dadosLista = [];

function salvarUser(){
    let nomeUser = document.getElementById('nomeUser').value;

    if(nomeUser){
        dadosLista.push(nomeUser);
        console.log(dadosLista);
        document.getElementById('nomeUser').value = '';
        alert('Usuário cadastrado com sucesso!');
    }else {
        alert("Favor informar um nome para cadastro");
    }

}

//Função para preencher a lista de cadastro