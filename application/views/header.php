<?
//Da erro no home

if ($this->session->userdata('autenticado') != true) {
    redirect(base_url() . "login/index/login004", "refresh");
}
$perfil_id = $this->session->userdata('perfil_id');

function alerta($valor) {
    echo "<script>alert('$valor');</script>";
}

function debug($object) {
    echo "<pre>";
    var_dump($object);
    echo "</pre>";
}
?>
<!DOCTYPE html PUBLIC "-//carreW3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR" >
    <head>
        <title>STG - SISTEMA DE GESTAO DE HOPSITAL v1.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Reset de CSS para garantir o funcionamento do layout em todos os brownsers -->
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <!--<link href="<?= base_url() ?>js/fullcalendar/lib/cupertino/jquery-ui.min.css" rel="stylesheet" />-->
        <link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    $('input:text').setMask();
                });
            })(jQuery);

        </script>

    </head>
    <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>

    <?php
    $this->load->library('utilitario');
    Utilitario::pmf_mensagem($this->session->flashdata('message'));
    ?>


    <div class="container">
        <div class="header">
            <div id="imglogo">
                <img src="<?= base_url(); ?>img/stg - logo.jpg" alt="Logo"
                     title="Logo" height="70" id="Insert_logo"
                     style="display:block;" />
            </div>
            <div id="login">
                <div id="user_info">
                    <label style='font-family: serif; font-size: 8pt;'>Seja bem vindo <?= $this->session->userdata('login'); ?>! </label>
                    <label style='font-family: serif; font-size: 8pt;'>Empresa: <?= $this->session->userdata('empresa'); ?> </label>
                </div>
                <div id="login_controles">
                    <!--
                    <a href="#" alt="Alterar senha" id="login_pass">Alterar Senha</a>
                    -->
                    <a id="login_sair" title="Sair do Sistema" onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                       href="<?= base_url() ?>login/sair">Sair</a>
                </div>
                <!--<div id="user_foto">Imagem</div>-->

            </div>
        </div>
        <div class="decoration_header">&nbsp;</div>
        <!-- Fim do Cabeçalho -->
        <div class="barraMenus" style="float: left;">
            <ul id="menu" class="filetree">
<!--                <li><span class="folder">Ponto</span>
                    <ul>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/funcionario">Funcionario</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/funcionario/relatorio">Funcionario lista</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/setor">Setor</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/funcao">Fun&ccedil;&atilde;o</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/cargo">Cargo</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/horariostipo">Horarios Tipo</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/horariostipo/virada">Virada</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/competencia">Competencia</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/ocorrenciatipo">Ocorrencia tipo</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/processaponto">processar</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/importarponto">importar ponto</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/importarponto/importarpontobatida">importar batida</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/relatorio/impressaocartaofixo">ponto Fixo</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/relatorio/impressaocartaovariavel">ponto Variavel</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/relatorio/impressaocartaosemiflexivel">ponto Semiflexivel</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ponto/processaponto">processar</a></span></li>
                    </ul>
                </li>-->


                <li><span class="folder">Recep&ccedil;&atilde;o</span>
                    <ul>
                        <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/pacientes">Cadastro</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listaresperacaixa">Fila Caixa</a></span></li>
    <!--                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncao">Multifuncao Exame</a></span></li>-->
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaoconsulta">Pronto Atendimento</a></span></li>
    <!--                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaofisioterapia">Multifuncao Fisioterapia</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/agenda/medicoagenda">Medico agenda</a></span></li>-->
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioconsultaconvenio">Medico convenio</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioconvenioquantidade">Convenio exames/consultas</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimentoplano/procedimentoplanoconsulta">Pre&ccedil;o procedimento</a></span></li>
                            <?
                        }
                        if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio Medico Convenio</a></span></li>
                        <? } ?>
<!--                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exametemp">Pacientes temporarios</a></span></li>
<li><span class="file"><a href="<?= base_url() ?>ambulatorio/localizapaciente">Loacalizar pacientes</a></span></li>-->
                    </ul>
                </li>

<!--                <li><span class="folder">Atendimento</span>
                    <ul>
                <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/painelrecepcao">Painel recepcao</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarsalasespera">Salas de Espera</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarexamerealizando">Salas de Exames</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarexamependente">Exames pendentes</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedico">Multifuncao Medico</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo">Manter Laudo</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo/pesquisardigitador">Manter Laudo Digitador</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo/pesquisarrevisor">Manter Revisor</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo/pesquisarlaudoantigo">Manter Antigo</a></span></li>
                <? } ?>
                    </ul>
                </li>-->
<!--                <li><span class="folder">Consultas</span>
                    <ul>
                <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta">Multifuncao Medico</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsulta">Manter Consulta</a></span></li>
                    <?
                }
                if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5) {
                    ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio Medico Convenio</a></span></li>
                <? } ?>
                    </ul>
                </li>-->
<!--                <li><span class="folder">Fisioterapia</span>
                    <ul>
                <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicofisioterapia">Multifuncao Fisioterapeuta</a></span></li>
                    <?
                }
                if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5) {
                    ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio Medico Convenio</a></span></li>
                <? } ?>
                    </ul>
                </li>-->
<!--                <li><span class="folder">Laboratorial</span>
                    <ul>
                <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicolaboratorial">Multifuncao Laboratorial</a></span></li>
                    <?
                }
                if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5) {
                    ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio Medico Convenio</a></span></li>
                <? } ?>
                    </ul>
                </li>-->
                <li><span class="folder">Pronto atendimento</span>
                    <ul>
                        <li><span class="folder">Rotinas</span>
                            <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6 || $perfil_id == 7) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/listarmultifuncaomedicoconsulta">Multifuncao Medico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/laudo/pesquisarconsulta">Manter Consulta</a></span></ul>
                                <?
                            }
                            ?>
                        </li> 
                        <li><span class="folder">Relatorios</span>
                            <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconvenio">Relatorio de Produ&ccedil;&atilde;o</a></span></ul>
                        </li>   
                    </ul>
                </li>
                <li><span class="folder">Emergencia</span>
                    <ul>
                        <li><span class="file"><a href="<?= base_url() ?>cadastros/pacientes">Cadastro Pacientes</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>emergencia/triagem/pesquisar">Fila de Triagem</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>emergencia/filaacolhimento">Fila de Acolhimento</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>emergencia/filaacolhimento/pesquisarrae">Listar RAE</a></span></li>
                    </ul>
                </li>
                <li><span class="folder">Internacao</span>
                    <ul>
                        <li><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarsolicitacaointernacao">Listar Solicitacoes</a></span></li>
                        <!--<li><span class="file"><a href="<?= base_url() ?>internacao/internacao">Listar Internacoes</a></span></li>-->
                        <li><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarsaida">Listar Saidas</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>internacao/internacao/listarprescricao">Relatorio Prescricao</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>internacao/internacao/pacientesinternados/Todas">Pacientes Internados</a></span></li>
                    </ul>
                </li>

                <li><span class="folder">Centro Cirurgico</span>
                    <ul>
                        <li><span class="file"><a href="<?= base_url() ?>centrocirurgico/centrocirurgico">Listar Solicitacoes</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>centrocirurgico/centrocirurgico/pesquisarcirurgia">Fila de Cirurgia</a></span></li>
                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/mapacirurgico">Mapa Cirurgico</a></span></li>
                    </ul>
                </li>
                <li><span class="folder">Faturamento</span>
                    <ul>
                        <? if ($perfil_id == 1 || $perfil_id == 3) { ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/faturamentoexame">Faturar</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exame/faturamentoexamexml">Gerar xml</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriovalorprocedimento">Ajustar valores</a></span></li>
                        <? } ?>
                    </ul>
                    <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioexame">Relatorio Conferencia</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/gerarelatoriogeralsintetico">Relatorio Sintetico Geral</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioexamech">Relatorio Faturamento Convenio CH</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriopacieneteexame">Relatorio Convenio Paciente</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocancelamento">Relatorio Cancelamento</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotempoesperaexame">Relatorio Tempo espera exame</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotemposalaespera">Relatorio Tempo sala de espera</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupo">Relatorio Exame Grupo</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupoanalitico">Relatorio Exame Grupo Analitico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupoprocedimento">Relatorio Exame Grupo Procedimento</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupoprocedimentomedico">Relatorio Grupo Procedimento Medico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogeralconvenio">Relatorio Geral Convenio</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitante">Relatorio Medico Solicitante sintetico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitantexmedico">Relatorio Medico Solicitante analitico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitantexmedicoindicado">Relatorio Medico Solicitante X Medico Indicado</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriolaudopalavra">Relatorio Laudo palavra chave</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriophmetria">Relatorio PH Metria</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotecnicoconvenio">Relatorio Tecnico Convenio</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotecnicoconveniosintetico">Relatorio Tecnico Convenio Sintetico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioexamesala">Relatorio Consolidado por sala</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioconveniovalor">Relatorio Convenio exames/consultas valor</a></span></ul>
                </li>
                <li><span class="folder">Estoque</span>
                    <ul><? if ($perfil_id == 1 || $perfil_id == 8) { ?>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/solicitacao">Manter Solicitacao</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada">Manter Entrada</a></span></li>

                            <li><span class="file"><a href="<?= base_url() ?>estoque/armazem">Manter Armazem</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/fornecedor">Manter Fornecedor</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/produto">Manter Produto</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/cliente">Manter Setor</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatorioentradaarmazem">Relatorio Entrada Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatoriosaidaarmazem">Relatorio Saida Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatoriosaldoarmazem">Relatorio Saldo Produtos/Entrada</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatoriosaldo">Relatorio Saldo Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatoriominimo">Relatorio Estoque Minimo</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatorioprodutos">Relatorio Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>estoque/entrada/relatoriofornecedores">Relatorio Fornecedores</a></span></li>

                        <? } ?>
                    </ul>
                </li>
                <li><span class="folder">Farmácia</span>
                    <ul><? if ($perfil_id == 1 || $perfil_id == 8) { ?>
                                        <!--<li><span class="file"><a href="<?= base_url() ?>farmacia/solicitacao">Manter Solicitacao</a></span></li>-->
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada">Manter Entrada</a></span></li>
                            <!--<li><span class="file"><a href="<?= base_url() ?>farmacia/menu">Manter Menu</a></span></li>-->

                            <li><span class="file"><a href="<?= base_url() ?>farmacia/armazem">Manter Armazem</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/fornecedor">Manter Fornecedor</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/produto">Manter Produto</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/fracionamento">Fracionamento</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/saida">Saida por paciente</a></span></li>
                            <!--<li><span class="file"><a href="<?= base_url() ?>farmacia/cliente">Manter Setor</a></span></li>-->
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatorioentradaarmazem">Relatorio Entrada Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatoriosaidaarmazem">Relatorio Saida Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatoriosaldoarmazem">Relatorio Saldo Produtos/Entrada</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatoriosaldo">Relatorio Saldo Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatoriominimo">Relatorio farmacia Minimo</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatorioprodutos">Relatorio Produtos</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>farmacia/entrada/relatoriofornecedores">Relatorio Fornecedores</a></span></li>
                            <!--<li><span class="file"><a href="<?= base_url() ?>seguranca/operador/operadorsetor">Listar Operadores</a></span></li>-->
                        <? } ?>
                    </ul>
                </li>
                <li><span class="folder">Financeiro</span>
                    <ul>
                        <? if ($perfil_id == 1) { ?>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa">Manter Entrada</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/pesquisar2">Manter Saida</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/contaspagar">Manter Contas a pagar</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/contasreceber">Manter Contas a Receber</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/tipo">Manter Tipo</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/forma">Manter Conta</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/fornecedor">Manter Credor/Devedor</a></span></li>
                            <?
                        }
                        if ($perfil_id == 1 || $perfil_id == 3) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/gerarelatoriogeralsintetico">Relatorio Sintetico Geral</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioexame">Relatorio Conferencia</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatorioexamech">Relatorio Faturamento Convenio CH</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriopacieneteexame">Relatorio Convenio Paciente</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocancelamento">Relatorio Cancelamento</a></span></li>
                            <?
                        }
                        if ($perfil_id == 1) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/relatoriosaida">Relatorio Saida</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/relatoriosaidagrupo">Relatorio Saida Tipo</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/relatorioentrada">Relatorio Entrada</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/relatorioentradagrupo">Relatorio Entrada Conta</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/contaspagar/relatoriocontaspagar">Relatorio Contas a pagar</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/contasreceber/relatoriocontasreceber">Relatorio Contas a Receber</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>cadastros/caixa/relatoriomovitamentacao">Relatorio Moviten&ccedil;&atilde;o</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupo">Relatorio Exame Grupo</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupoanalitico">Relatorio Exame Grupo Analitico</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogrupoprocedimento">Relatorio Exame Grupo Procedimento</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogeralconvenio">Relatorio Geral Convenio</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitante">Relatorio Medico Solicitante sintetico</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitantexmedico">Relatorio Medico Solicitante analitico</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitantexmedicoindicado">Relatorio Medico Solicitante X Medico Indicado</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriolaudopalavra">Relatorio Laudo palavra chave</a></span></li>
                            <?
                        }
                        if ($perfil_id == 1 || $perfil_id == 5) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocaixa">Relatorio Caixa</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocaixafaturado">Relatorio Caixa Faturamento</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocaixacartao">Relatorio Caixa Cartao</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriocaixacartaoconsolidado">Relatorio Consolidado Cartao</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriophmetria">Relatorio PH Metria</a></span></li>
                            <?
                        }
                        if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconveniofinanceiro">Relatorio Medico Convenio</a></span></li>
                        <? } ?>
                    </ul>
                </li>
                <li><span class="folder">Relatorios</span>
                    <ul>
                        <?
                        if ($perfil_id == 1) {
                            ?>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotecnicoconvenio">Relatorio Tecnico Convenio</a></span></li>
                            <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriotecnicoconveniosintetico">Relatorio Tecnico Convenio Sintetico</a></span></li>
                        <? } ?>
<!--                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/exametemp">Pacientes temporarios</a></span></li>
<li><span class="file"><a href="<?= base_url() ?>ambulatorio/localizapaciente">Loacalizar pacientes</a></span></li>-->
                    </ul>
                </li>


<!--                <li><span class="folder">Relatorios (RM)</span>
                    <ul>
                <? if ($perfil_id == 1 || $perfil_id == 6 || $perfil_id == 9) { ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriogruporm">Relatorio Caixa</a></span></li>
                    <?
                }
                if ($perfil_id == 1 || $perfil_id == 9) {
                    ?>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitanterm">Relatorio Medico Solicitante</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicosolicitanterm">Relatorio Medico Solicitante</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriomedicoconveniorm">Relatorio Medico Convenio</a></span></li>
                                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/relatoriofaturamentorm">Relatorio Faturamento</a></span></li>
                <? } ?>
                    </ul>
                </li>-->
                <li><span class="folder">Configura&ccedil;&atilde;o</span>
                    <ul>
                        <li><span class="folder">Recep&ccedil;&atilde;o</span>
                            <? if ($perfil_id == 1 || $perfil_id == 5) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>seguranca/operador">Listar Profissionais</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/motivocancelamento">Motivo cancelamento</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/tipoconsulta">Tipo consulta</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/horario">Manter Horarios</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/agenda">Agenda Horarios</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/exame">Agenda Manter</a></span></ul>
                            <? } ?>
                            <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/sala">Manter Salas</a></span></ul>
                        </li>

                        <li><span class="folder">Procedimento</span>                    
                            <? if ($perfil_id == 1 || $perfil_id == 3) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimento">Manter Procedimentos</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimento/relatorioprocedimento">Relatorio Procedimentos</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimento/pesquisartuss">Manter Procedimentos TUSS</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimento/gerarelatorioprocedimentotuss">Relatorio Procedimentos TUSS</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>cadastros/convenio">Manter convenio</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimentoplano">Manter Procedimentos Convenio</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/procedimentoplano/procedimentopercentual">Manter Percentual M&eacute;dico</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/classificacao">Manter Classificacao</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modelolaudo">Manter Modelo Laudo</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modelolinha">Manter Modelo Linha</a></span></ul>

                            <? } ?>
                        </li>
                        <li><span class="folder">Imagem</span> 
                            <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modelolaudo">Manter Modelo Laudo</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modelolinha">Manter Modelo Linha</a></span></ul>
                            <? } ?>
                        </li>
                        <li><span class="folder">Pronto Atendimento</span> 
                            <? if ($perfil_id == 1 || $perfil_id == 2 || $perfil_id == 3 || $perfil_id == 4 || $perfil_id == 5 || $perfil_id == 6) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modeloreceita">Manter Modelo Receita</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modeloatestado">Manter Modelo Atestado</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modeloreceitaespecial">Manter Modelo R. Especial</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/modelosolicitarexames">Manter Modelo S.Exames</a></span></ul>
                            <? } ?>
                        </li>
                        <li><span class="folder">Interna&ccedil;&atilde;o</span>
                            <ul><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarunidade">Listar Unidades</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarenfermaria">Lista Enfermarias</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarleito">Listar Leitos</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>internacao/internacao/pesquisarmotivosaida">Manter Motivo Saida</a></span></ul> 
                        </li>
                        <li><span class="folder">Estoque</span>
                            <ul><span class="file"><a href="<?= base_url() ?>estoque/menu">Manter Menu</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>estoque/tipo">Manter Tipo</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>estoque/classe">Manter Classe</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>estoque/subclasse">Manter Sub-Classe</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>estoque/unidade">Manter Medida</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>seguranca/operador/operadorsetor">Listar Operadores</a></span></ul>
                        </li>
                        <li><span class="folder">Farmacia</span>
                            <ul><span class="file"><a href="<?= base_url() ?>farmacia/tipo">Manter Tipo</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>farmacia/classe">Manter Classe</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>farmacia/subclasse">Manter Sub-Classe</a></span></ul>
                            <ul><span class="file"><a href="<?= base_url() ?>farmacia/unidade">Manter Medida</a></span></ul>
                        </li>
                        <li><span class="folder">Administrativas</span>
                            <? if ($perfil_id == 1 || $perfil_id == 3) { ?>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/empresa">Manter Empresa</a></span></ul>
                                <ul><span class="file"><a href="<?= base_url() ?>ambulatorio/versao">Vers&atilde;o</a></span></ul>
                            <? } ?>
                        </li> 
<!--                    <li><span class="file"><a href="<?= base_url() ?>ambulatorio/agenda">Manter Agendas</a></span></li>-->


                    </ul>
                </li>
                <li><span class="file"><a onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                                          href="<?= base_url() ?>login/sair">Sair</a></span>
                </li>
            </ul>
            <!-- Fim da Barra Lateral -->
        </div>
        <div class="mensagem"><?
            if (isset($mensagem)): echo $mensagem;
            endif;
            ?></div>
        <script type="text/javascript">
            $("#menu").treeview({
                animated: "normal",
                persist: "cookie",
                collapsed: true,
                unique: true
            });
        </script>
