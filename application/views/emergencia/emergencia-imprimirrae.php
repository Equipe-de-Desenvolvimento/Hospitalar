<html>
<head>
<meta charset="utf-8">
<link href="<?= base_url()?>css/tabelarae.css" rel="stylesheet" type="text/css">
<title>Impressão RAE</title>
</head>

<body>
<table id="tabelaspec" width="92%" border="1" align="center" cellpadding="0" cellspacing="0" class="tipp">
  <tbody>
  <tr>
      <td width="58" height="51" style="font-size: 9px;"><p class="ttr"><strong style="font-weight: normal; text-align: center;"><strong style="font-weight: normal; text-align: left;"><img src="<?= base_url()?>img/logorae.png" alt="" width="58" height="49" class="ttr"/></strong></strong></p></td>
      <td width="127" class="ttrl" style="font-size: 9px;">&nbsp;</td>
      <td height="51" colspan="4" class="ttrl" style="font-size: 10px; font-weight: normal; text-align: center;"><strong><? echo $empresa[0]->razao_social; ?><br>
        <? echo $empresa[0]->logradouro; ?><? echo $empresa[0]->bairro; ?>&nbsp;N &nbsp;<? echo $empresa[0]->numero; ?> <br>
        CNPJ:&nbsp; <? echo $empresa[0]->cnpj; ?><br>
          Telefone:&nbsp; <? echo $empresa[0]->telefone; ?></strong></td>
      <td height="51" colspan="2" class="ttl" style="font-size: 15px; font-weight: normal; text-align: right;"><strong>SUS</strong></td>
    </tr>
    <tr>
      <td height="27" colspan="8" align="center" style="text-align: center; font-size: 15px; font-weight: normal;"><strong>REGISTRO DE ATENDIMENTO EMERGENCIAL</strong></td>
    </tr>
    <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> DADOS PESSOAIS</strong></td>
    </tr>
    <tr>
      <td colspan="6" class="ti">NOME</td>
      <td colspan="2" class="ti">N° De Registro</td>
    </tr>
    <tr>
     <td height="16" colspan="6" class="tc"><strong><? echo $impressao[0]->paciente; ?></strong></td>
      <td colspan="2" class="tc"><strong> <? echo $impressao[0]->paciente_id; ?> </strong></td>
    </tr>
    <tr>
      <td colspan="3" class="ti"><em class="ti" style="font-size: 7pt">TIPO DOC</em></td>
      <td width="331" class="ti"><em>N DOC</em></td>
      <td width="131" class="ti"><em>NASCIMENTO</em></td>
      <td width="326" class="ti"><em>SEXO</em></td>
      <td colspan="2" class="ti">RAÇA/COR</td>
    </tr>
    <tr>
      <td colspan="3" class="tc"><strong>RG</strong></td>
      <td class="tc"><strong><? echo $impressao[0]->rg; ?></strong></td>
      <td class="tc"><strong><?$ano= substr($impressao[0]->nascimento,0,4);?>
                                                            <?$mes= substr($impressao[0]->nascimento,5,2);?>
                                                            <?$dia= substr($impressao[0]->nascimento,8,2);?>
                                                            <?$datafinal= $dia . '/' . $mes . '/' . $ano; ?>
                                                            <?php echo$datafinal?></strong></td>
      <td class="tc"><strong><? echo $impressao[0]->sexo; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->raca; ?></strong></td>
    </tr>
    
    <tr>
      <td colspan="6" class="ti">NOME MÃE</td>
      <td colspan="2" class="ti">CONTATO</td>
    </tr>
    <tr>
     <td height="16" colspan="6" class="tc"><strong><? echo $impressao[0]->nome_mae; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->telefoneresp; ?></strong></td>
    </tr>
    <tr>
      <td colspan="6" class="ti">NOME RESPONSÁVEL</td>
      <td colspan="2" class="ti">CONTATO</td>
    </tr>
    <tr>
     <td height="16" colspan="6" class="tc"><strong><? echo $impressao[0]->nomeresp; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->telefoneresp; ?></strong></td>
    </tr>
   <tr>
      <td colspan="6" class="ti">ENDEREÇO</td>
      <td colspan="2" class="ti">CONTATO</td>
    </tr>
    <tr>
     <td height="16" colspan="6" class="tc"><strong><? echo $impressao[0]->logradouro; ?> &nbsp;N&nbsp; <? echo $impressao[0]->numero; ?> &nbsp; <? echo $impressao[0]->complemento; ?> </strong></td>
      <td colspan="2" class="tc"><strong>NI</strong></td>
    </tr>
    <tr>
      <td colspan="5" class="ti">MUNICIPIO</td>
      <td class="ti">COD IBGE</td>
      <td width="91" class="ti">CEP</td>
      <td width="174" class="ti">UF</td>
    </tr>
    <tr>
      <td colspan="5" class="tc"><strong><? echo $impressao[0]->municipio; ?></strong></td>
      <td class="tc"><strong><? echo $impressao[0]->codigo_ibge; ?></strong></td>
      <td class="tc"><strong><? echo $impressao[0]->cep; ?></strong></td>
      <td class="tc"><strong><? echo $impressao[0]->estado; ?></strong></td>
    </tr>
    <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> OCORRÊNCIA</strong></td>
    </tr>
    <tr>
      <td colspan="8" class="ti">LOCAL DA OCORRÊNCIA</td>
    </tr>
    <tr>
      <td colspan="8" class="tc"><strong><? echo $impressao[0]->local_ocorrencia; ?> </strong></td>
    </tr>
    <tr>
      <td height="13" colspan="4" class="ti">TRANSPORTE</td>
      <td colspan="4" class="ti">DADOS DO ACIDENTE</td>
    </tr>
    <tr>
      <td height="16" colspan="4" class="tc"><strong><? echo $impressao[0]->veiculo_ocorrencia; ?></strong></td>
      <td colspan="4" class="tc"><strong><? echo $impressao[0]->descricao_ocorrencia; ?></strong></td>
    </tr>
     
    <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> ACOLHIMENTO</strong></td>
    </tr>
    <tr>
      <td colspan="8" class="ti">MOTIVO</td>
    </tr>
    <tr>
      <td colspan="8" class="tc"><strong><? echo $impressao[0]->tipo_atendimento; ?> </strong></td>
    </tr>
    <tr>
      <td colspan="5" class="ti"><em class="ti" style="font-size: 7pt"></em><em>SINAIS E SINTOMAS</em></td>
      <td colspan="3" class="ti"><em></em>ESCALA DE DOR</td>
    </tr>
    <tr>
      <td colspan="5" class="tc"><strong><? echo $impressao[0]->sitomas_atendimento; ?></strong></td>
      <td colspan="3" class="tc"><strong><? echo $impressao[0]->escala_dor; ?></strong><strong></strong></td>
    </tr>
   <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> SINAIS VITAIS</strong></td>
    </tr>
    <tr>
      <td colspan="3" class="ti"><em class="ti" style="font-size: 7pt">PESO</em></td>
      <td width="331" class="ti"><em>PRESSÃO ARTERIAL SISTÓLICA</em></td>
      <td colspan="2" class="ti"><em>FREQ RESPIRATORIA</em><em></em></td>
      <td colspan="2" class="ti">TEMPERATURA</td>
    </tr>
    <tr>
      <td colspan="3" class="tc"><strong><? echo $impressao[0]->peso; ?>Kg</strong></td>
      <td class="tc"><strong><? echo $impressao[0]->pa_sist; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->frequencia_respiratoria; ?></strong><strong></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->temperatura; ?>°C</strong></td>
    </tr>
    <tr>
      <td height="14" colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> ESCORE DE TRAUMA REVISADO</strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center" style="text-align:center;font-size: 9px;"><strong>VALOR </strong></td>
      <td align="center" style="text-align:center;font-size: 9px;"><strong>&quot;X&quot;</strong></td>
      <td height="14" align="center" style="text-align:center;font-size: 9px;"><strong>ESCALA GLASGLOW</strong></td>
      <td align="center" style="text-align:center;font-size: 9px;"><strong>'X '</strong></td>
      <td align="center" style="text-align:center;font-size: 9px;"><strong>PA SISTÓLICA</strong></td>
      <td align="center" style="text-align:center;font-size: 9px;"><strong>'X '</strong></td>
      <td align="center" style="text-align:center;font-size: 9px;"><strong>FREQ RESPIRATÓRIA</strong></td>
    </tr>
    <tr>
      <td height="34" colspan="2" align="center" class="tm" ><em>4</em></td>
      <td width="77" class="tm">&nbsp;</td>
      <td width="331" class="tm"><em>13 A 15</em></td>
      <td class="ti"><em></em></td>
      <td class="tm">&gt;89</td>
      <td class="ti">&nbsp;</td>
      <td class="tm">10 A 29</td>
    </tr>
    
    <tr>
      <td height="30" colspan="2" class="tm"><em>3</em></td>
      <td width="77" class="ti">&nbsp;</td>
      <td width="331" class="tm"><em>09 A 12</em></td>
      <td class="ti">&nbsp;</td>
      <td class="tm">76 A 89</td>
      <td class="ti">&nbsp;</td>
      <td class="tm">&gt;29</td>
    </tr>
    
    <tr>
      <td height="37" colspan="2" class="tm"><em>2</em></td>
      <td width="77" class="ti">&nbsp;</td>
      <td width="331" class="tm"><em>06 A 08</em></td>
      <td class="ti"><em></em></td>
      <td class="tm">50 A 75</td>
      <td class="ti">&nbsp;</td>
      <td class="tm">06 A 09</td>
    </tr>
    
    <tr>
      <td height="32" colspan="2" class="tm"><em>1</em></td>
      <td width="77" class="ti">&nbsp;</td>
      <td width="331" class="tm"><em>04 A 05</em></td>
      <td class="ti"><em></em></td>
      <td class="tm">01 A 49</td>
      <td class="ti">&nbsp;</td>
      <td class="tm">01 A 05</td>
    </tr>
    
    <tr>
      <td height="33" colspan="2" class="tm"><em>0</em></td>
      <td width="77" class="ti">&nbsp;</td>
      <td width="331" class="tm"><em>00 A 03</em></td>
      <td class="ti"><em></em></td>
      <td class="tm">00</td>
      <td class="ti">&nbsp;</td>
      <td class="tm">00</td>
    </tr>
    <tr>
      <td height="13" colspan="4" class="ti">CLASSIFICAÇÃO</td>
      <td colspan="4" class="ti">DATA E HORA DO ATENDIMENTO</td>
    </tr>
    <tr>
      <td height="16" colspan="4" class="tc"><strong><? echo $impressao[0]->classificacao; ?></strong></td>
      <td colspan="4" class="tc"><strong><?$ano= substr($impressao[0]->data_cadastro,0,4);?>
                                                            <?$mes= substr($impressao[0]->data_cadastro,5,2);?>
                                                            <?$dia= substr($impressao[0]->data_cadastro,8,2);?>
                                                            <?$hora= substr($impressao[0]->data_cadastro,10,10);?>
                                                            <?$datafinal= $dia . '/' . $mes . '/' . $ano . $hora; ?>
                                                            <?php echo$datafinal?></strong></td>
    </tr>
    <tr>
      <td height="13" colspan="4" class="ti">REFERÊNCIA</td>
      <td colspan="4" class="ti">RESPONSÁVEL PELO ACOLHIMENTO</td>
    </tr>
    <tr>
      <td height="16" colspan="4" class="tc"><strong>&nbsp;</strong></td>
      <td colspan="4" class="tc"><strong><? echo $impressao[0]->operador; ?></strong></td>
    </tr>
    <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> ÁREA ATENDIMENTO</strong></td>
    </tr>
    <tr>
      <td colspan="8" class="ti">ÁREA DE ATENDIMENTO</td>
    </tr>
    <tr>
      <td colspan="8" class="tc"><strong>02 - EMERGENCIA TRAUMATOLOGIA</strong></td>
    </tr>
    <tr>
      <td colspan="8" align="center" style="text-align:center;font-size: 9px;"><strong> ATENDIMENTO MÉDICO</strong></td>
    </tr>
    <tr>
      <td colspan="8" class="ti">ANAMNESE</td>
    </tr>
    <tr>
        <td height="50" colspan="8" class="tc"><strong><? echo $impressao[0]->anamnase; ?></strong></td>
    </tr>
    <tr>
      <td height="13" colspan="4" class="ti">DIAGNÓSTICO</td>
      <td colspan="2" class="ti">COD. PROCEDIMENTO</td>
      <td colspan="2" class="ti">CID</td>
    </tr>
    <tr>
        <td height="16" colspan="4" class="tc"><strong><? echo $impressao[0]->diagnostico; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->cod; ?></strong></td>
      <td colspan="2" class="tc"><strong><? echo $impressao[0]->cid; ?></strong></td>
    </tr>
    <tr>
      <td colspan="8" class="ti">SADT SOLICITADO:</td>
    </tr>
    <tr>
      <td height="27" colspan="8" class="tc"><strong>( ) HC ( ) SU ( ) US ABDOMINAL ( ) TC CRANIO ( ) RAIO-X __________________________________ ( ) OUTROS __________________________________</strong></td>
    </tr>
    <tr>
      <td height="13" colspan="8" class="ti">CONDUTA</td>
    </tr>
    <tr>
        <td height="16" colspan="8" class="tc"><strong><? echo $impressao[0]->conduta; ?></strong></td>
    </tr>
    <tr>
      <td height="13" colspan="4" class="ti">DATA E HORA DO ATENDIMENTO</td>
      <td colspan="4" class="ti">CARIMBO E ASSINATURA DO MÉDICO ESPECIALISTA</td>
    </tr>
    <tr>
      <td height="61" colspan="4" class="tc">&nbsp;</td>
      <td colspan="4" class="tc">&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>
