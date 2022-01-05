<?php
require_once("../../conexao.php");
@session_start();
$id_usuario = $_SESSION['id_usuario'];

$pagamento = $_POST['pagamento'];
$lancamento = $_POST['lancamento'];
$data = $_POST['data'];
$desconto = $_POST['desconto'];
$acrescimo = $_POST['acrescimo'];
$subtotal = $_POST['subTotal'];
$parcelas = $_POST['parcelas'];
$cliente = $_POST['id-cli'];


$desconto = str_replace(',', '.', $desconto);
$acrescimo = str_replace(',', '.', $acrescimo);

if ($data == date('Y-m-d') and $parcelas == '1') {
    $status = 'Concluída';
} else {
    $status = 'Pendente';
}

if ($parcelas < 1) {
    echo 'As parcelas tem que ser pelo menos igual a 1';
    exit();
}

//BUSCANDO A TABELA CLIENTE PARA OBTER O NOME DELE 
$query_con = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res[0]['nome'];


$total_venda = 0;
$query_con = $pdo->query("SELECT * FROM itens_venda WHERE id_venda = 0 
and usuario = '$id_usuario' order by id desc");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        $valor_total_item = $res[$i]['total'];
        $total_venda += $valor_total_item;
    }
} else {
    echo 'Não é possível fechar a venda sem itens!';
    exit();
}

$query_caix = $pdo->query("SELECT * FROM caixa WHERE status = 'Aberto'");
$res_caix = $query_caix->fetchAll(PDO::FETCH_ASSOC);
if (@count($res_caix) > 0) {
    $caixa_aberto = $res_caix[0]['id'];
} else {
    $caixa_aberto = 0;
}


$query = $pdo->prepare("INSERT INTO vendas set valor = '$total_venda', usuario = '$id_usuario',
 pagamento = :pagamento, lancamento = :lancamento, data_lanc = CurDate(), data_pgto = :data, 
 desconto = :desconto, acrescimo = :acrescimo, subtotal = :subtotal, parcelas = :parcelas, 
 status = '$status', cliente = :cliente");


$query->bindValue(":pagamento", "$pagamento");
$query->bindValue(":lancamento", "$lancamento");
$query->bindValue(":data", "$data");
$query->bindValue(":desconto", "$desconto");
$query->bindValue(":acrescimo", "$acrescimo");
$query->bindValue(":subtotal", "$subtotal");
$query->bindValue(":parcelas", "$parcelas");
$query->bindValue(":cliente", "$cliente");
$query->execute();
$id_ult_registro = $pdo->lastInsertId();

$descricao_conta = 'Cliente - ' . $nome_cli;

if ($status == 'Concluída') {

    $pdo->query("INSERT INTO movimentacoes set tipo = 'Entrada', movimento = 'Venda', 
	descricao = '$descricao_conta', valor = '$subtotal', usuario = '$id_usuario', data = curDate(), 
	lancamento = '$lancamento', plano_conta = 'Venda', documento = '$pagamento', 
	caixa_periodo = '$caixa_aberto', id_mov = '$id_ult_registro'");
} else {
    if ($parcelas > 1) {

        $query = $pdo->query("UPDATE contas_receber set cliente = '$cliente',
		entrada = '$lancamento', documento = '$pagamento', plano_conta = 'Venda', frequencia = 'Uma Vez',
		 usuario_lanc = '$id_usuario', status = 'Pendente', data_recor = curDate(), 
		 id_venda = '$id_ult_registro' WHERE id_venda = '-1' and usuario_lanc = '$id_usuario'");
    } else {

        $query = $pdo->query("INSERT INTO contas_receber set descricao = 'Venda', 
		cliente = '$cliente', entrada = '$lancamento', documento = '$pagamento', plano_conta = 'Venda', 
		data_emissao = curDate(), vencimento = '$data', frequencia = 'Uma Vez', valor = '$subtotal', 
		usuario_lanc = '$id_usuario', status = 'Pendente', data_recor = curDate(), 
		id_venda = '$id_ult_registro'");
    }
}


$query_con = $pdo->query("SELECT * FROM itens_venda WHERE id_venda = 0 and usuario = '$id_usuario'
 order by id desc");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        $pdo->query("UPDATE itens_venda set id_venda = '$id_ult_registro' where id_venda = 0 
        and usuario = '$id_usuario'");
    }
}


echo 'Salvo com Sucesso!';
