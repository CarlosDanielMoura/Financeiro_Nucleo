<?php
require_once("../conexao.php");
require_once("verificar.php");
$pagina = 'lista_vendas';

require_once($pagina . "/campos.php");

?>

<link rel="stylesheet" href="../css/lista_venda_compras.css">

<div class="col-md-12 my-3">

</div>

<small>
    <div class="tableDados bg-light" id="listar">

    </div>
</small>



<!-- Modal -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Cancelar Venda</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-excluir" method="post">
                <div class="modal-body">

                    Deseja Realmente cancelar esta Venda? <span id="nome-excluido"></span>?

                    <?php require_once("verificar_adm.php"); ?>

                    <small>
                        <div id="mensagem-excluir" align="center"></div>
                    </small>

                    <input type="hidden" class="form-control" name="id-excluir" id="id-excluir">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
                    <button type="submit" class="btn btn-danger">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>





<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Venda - SubTotal: R$ <span id="campo9"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <small>
                    <!--LINHA 1-->
                    <div class="row">
                        <div class="dados-detalhados">
                            <div class="col-6 dados-detalhados-itens">
                                <b>Usuário:</b> <span id="campo2"></span>
                            </div>
                            <div class="col-6 dados-detalhados-item">
                                <b>Pagamento:</b> <span id="campo3"></span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!--LINHA 2-->
                    <div class="row">

                        <div class="dados-detalhados">

                            <div class="col-6 dados-detalhados-itens">
                                <b>Lançamento:</b> <span id="campo4"></span>
                            </div>
                            <div class="col-6 dados-detalhados-itens">
                                <b>Vencimento:</b> <span id="campo6"></span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!--LINHA 3-->
                    <div class="row">
                        <div class="dados-detalhados">
                            <div class="col-6 dados-detalhados-itens">
                                <b>Valor:</b> R$ <span id="campo1"></span>
                            </div>
                            <div class="col-6 dados-detalhados-itens">
                                <b>Desconto:</b> R$ <span id="campo7"></span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!--LINHA 4-->
                    <div class="row">
                        <div class="dados-detalhados">
                            <div class="col-6 dados-detalhados-itens">
                                <b>Acréscimo:</b> R$ <span id="campo8"></span>
                            </div>
                            <div class="col-6 dados-detalhados-itens">
                                <b>SubTotal:</b> R$ <span id="subtot"></span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!--LINHA 5-->
                    <div class="row">
                        <div class="dados-detalhados">
                            <div class="col-6 dados-detalhados-itens">
                                <b>Parcelas:</b> <span id="campo10"></span>
                            </div>
                            <div class="col-6 dados-detalhados-itens">
                                <b>Status:</b> <span id="campo11"></span>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <!--LINHA 6-->
                    <div class="row">
                        <div class="dados-detalhados">
                            <div class="col-12 dados-detalhados-itens">
                                <b>Cliente:</b> <span id="campo12"></span>
                            </div>
                        </div>
                        <hr>
                    </div>
                </small>

                <small>
                    <div id="listar-parcelas"></div>
                </small>






            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    var pag = "<?= $pagina ?>"
</script>
<script src="../js/ajax.js"></script>