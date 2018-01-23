<?php

class procedimentoplano_model extends Model {

    var $_procedimento_convenio_id = null;
    var $_convenio_id = null;
    var $_convenio = null;
    var $_procedimento_tuss_id = null;
    var $_procedimento = null;
    var $_qtdech = null;
    var $_valorch = null;
    var $_qtdefilme = null;
    var $_valorfilme = null;
    var $_qtdeporte = null;
    var $_valorporte = null;
    var $_qtdeuco = null;
    var $_valoruco = null;
    var $_valortotal = null;

    function Procedimentoplano_model($procedimento_convenio_id = null) {
        parent::Model();
        if (isset($procedimento_convenio_id)) {
            $this->instanciar($procedimento_convenio_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('procedimento_convenio_id,
                            pc.convenio_id,
                            c.nome as convenio,
                            pc.procedimento_tuss_id,
                            pt.nome as procedimento,
                            pt.codigo,
                            pc.valortotal');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('c.nome ilike', $args['nome'] . "%");
        }
        if (isset($args['procedimento']) && strlen($args['procedimento']) > 0) {
            $this->db->where('pt.nome ilike', $args['procedimento'] . "%");
        }
        if (isset($args['codigo']) && strlen($args['codigo']) > 0) {
            $this->db->where('pt.codigo ilike', $args['codigo'] . "%");
        }
        return $this->db;
    }

    function listarprocedimentopercentual($args = array()) {
        $this->db->select('pm.procedimento_percentual_medico_id,
                            pm.procedimento_tuss_id,
                            pm.medico,
                            o.nome as medico,
                            pt.nome,
                            pm.valor');
        $this->db->from('tb_procedimento_percentual_medico pm');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pm.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = pm.medico', 'left');
        $this->db->where("pm.ativo", 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('o.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['procedimento']) && strlen($args['procedimento']) > 0) {
            $this->db->where('pt.nome ilike', "%" . $args['procedimento'] . "%");
        }
        return $this->db;
    }

    function listarprocedimento() {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            codigo');
        $this->db->from('tb_procedimento_tuss');
        $this->db->orderby('nome');
        $this->db->where("ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarconvenio() {
        $this->db->select('convenio_id,
                            nome,');
        $this->db->from('tb_convenio');
        $this->db->where("ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function instanciaragrupador($agrupador_id = null) {
        $this->db->select('agrupador_id,
                           nome,
                           convenio_id');
        $this->db->from('tb_agrupador_procedimento_nome');
        $this->db->where("ativo", 't');
        $this->db->where('agrupador_id', $agrupador_id);

        $query = $this->db->get();
        return $query->result();
    }

    function buscaragrupador($agrupador_id) {
        $this->db->select('agrupador_id,
                           nome,
                           convenio_id');
        $this->db->from('tb_agrupador_procedimento_nome');
        $this->db->where("ativo", 't');
        $this->db->where('agrupador_id', $agrupador_id);
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('c.nome ilike', "%" . $args['nome'] . "%");
        }

        $query = $this->db->get();
        return $query->result();
    }

    function listarprocedimentosagrupador($agrupador_id) {
//        die;
        $this->db->select('pa.agrupador_id,
                           pa.procedimento_agrupado_id,
                           pt.nome,
                           c.nome as convenio,
                           pt.codigo,
                           pc.procedimento_convenio_id');
        $this->db->from('tb_procedimentos_agrupados pa');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = pa.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("pa.ativo", 't');
        $this->db->where('pa.agrupador_id', $agrupador_id);

        $query = $this->db->get();
        return $query->result();
    }

    function gravaragrupador() {

        $this->db->select('DISTINCT(convenio_secundario_id)');
        $this->db->from('tb_convenio_secudario_associacao');
        $this->db->where('convenio_primario_id', $_POST['convenio']);
        $this->db->where('ativo', 't');
        $conv_sec = $this->db->get()->result();

        if (count($conv_sec) > 0) {

            $convPrimario = true;

            $this->db->select('DISTINCT(pt.grupo)');
            $this->db->from('tb_procedimentos_agrupados_ambulatorial paa');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = paa.procedimento_tuss_id', 'left');
            $this->db->where('paa.procedimento_agrupador_id', $_POST['procedimento']);
            $this->db->where('paa.ativo', 't');
            $grupos = $this->db->get()->result();

            $gp = array();
            foreach ($grupos as $value) {
                $gp[] = (string) $value->grupo;
            }
            $gp = implode(',', $gp);

            foreach ($conv_sec as $item) {
                $this->db->select('convenio_secundario_id, valor_percentual');
                $this->db->from('tb_convenio_secudario_associacao');
                $this->db->where('convenio_secundario_id', $item->convenio_secundario_id);
                $this->db->where("grupo IN ('{$gp}')");
                $this->db->where('ativo', 't');
                $verificador = $this->db->get()->result();

                if (count($verificador) != count($grupos)) {
                    return -3;
                    break;
                }
            }
        }

        $procedimento_agrupador_id = $_POST['procedimento'];
        $convenio_id = $_POST['convenio'];
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->select('convenio_id');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->where('pc.ativo', 't');
        $this->db->where("pc.procedimento_tuss_id", $_POST['procedimento']);
        $this->db->where("pc.convenio_id", $_POST['convenio']);
        $this->db->where("pc.empresa_id", $_POST['empresa']);

        if ($_POST['txtprocedimentoplanoid'] != "") {
            $this->db->where("pc.procedimento_convenio_id !=", $_POST['txtprocedimentoplanoid']);
        }

        $query = $this->db->get();
        $return = $query->result();
        $qtde = count($return);
        $qtde = 0;
        if ($qtde == 0) {

            $this->db->select('grupo_pagamento_id');
            $this->db->from('tb_convenio_grupopagamento cg');
            $this->db->where('cg.ativo', 't');
            $this->db->where("cg.convenio_id", $convenio_id);
            $query = $this->db->get();
            $grupoPagamento = $query->result();

            $this->db->set('procedimento_tuss_id', $procedimento_agrupador_id);
            $this->db->set('convenio_id', $convenio_id);
            $this->db->set('empresa_id', $_POST['empresa']);
            $this->db->set('qtdech', 0);
            $this->db->set('valorch', 0);
            $this->db->set('qtdefilme', 0);
            $this->db->set('valorfilme', 0);
            $this->db->set('qtdeporte', 0);
            $this->db->set('valorporte', 0);
            $this->db->set('qtdeuco', 0);
            $this->db->set('valoruco', 0);
            $this->db->set('valortotal', ((float) $_POST['valortotal']));
            $this->db->set('agrupador', 't');

            if (isset($_POST['valor_diferenciado'])) {
                $this->db->set('valor_pacote_diferenciado', 't');
            } else {
                $this->db->set('valor_pacote_diferenciado', 'f');
            }

            if ($_POST['txtprocedimentoplanoid'] == "") {
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimento_convenio');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $procedimento_convenio_id = $this->db->insert_id();


                foreach ($grupoPagamento as $gp) {
                    $this->db->set('procedimento_convenio_id', $procedimento_convenio_id);
                    $this->db->set('grupo_pagamento_id', $gp->grupo_pagamento_id);
                    $this->db->insert('tb_procedimento_convenio_pagamento');
                }
            } else {
                $procedimento_convenio_id = $_POST['txtprocedimentoplanoid'];

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->where('procedimento_convenio_id', $procedimento_convenio_id);
                $this->db->update('tb_procedimento_convenio');
            }

            if ($convPrimario) { // Caso seja um convenio primario, adiciona nos secundarios
                foreach ($conv_sec as $item) {
                    $valorPacote = 0;

                    // Traz os dados desse convenio secundario
                    $this->db->select('convenio_secundario_id, valor_percentual, grupo');
                    $this->db->from('tb_convenio_secudario_associacao');
                    $this->db->where('convenio_secundario_id', $item->convenio_secundario_id);
                    $this->db->where("grupo IN ('{$gp}')");
                    $this->db->where('ativo', 't');
                    $cv = $this->db->get()->result();

                    foreach ($cv as $value) {
                        // Somando o valor de todos os procedimentos desse grupo                        
                        $this->db->select('SUM(pc.valortotal) as valor');
                        $this->db->from('tb_procedimento_convenio pc');
                        $this->db->where('pc.ativo', 't');
                        $this->db->where('pc.convenio_id', $convenio_id);
                        $this->db->where("pc.procedimento_tuss_id IN (
                            SELECT paa.procedimento_tuss_id FROM ponto.tb_procedimentos_agrupados_ambulatorial paa
                            INNER JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = paa.procedimento_tuss_id
                            WHERE paa.procedimento_agrupador_id = {$procedimento_agrupador_id} AND paa.ativo = 't'
                            AND pt.grupo = '{$value->grupo}'
                        )");
                        $valor = $this->db->get()->result();

                        // Aplica o percentual cadastrado a todos os procedimentos desse grupo
                        $v = ($valor[0]->valor * $value->valor_percentual / 100);

                        // Soma o valor total desse grupo, ao valor total do pacote
                        $valorPacote += $v;
                    }

                    $this->db->set('procedimento_tuss_id', $procedimento_agrupador_id);
                    $this->db->set('convenio_id', $item->convenio_secundario_id);
                    $this->db->set('empresa_id', $_POST['empresa']);
                    $this->db->set('qtdech', 0);
                    $this->db->set('valorch', 0);
                    $this->db->set('qtdefilme', 0);
                    $this->db->set('valorfilme', 0);
                    $this->db->set('qtdeporte', 0);
                    $this->db->set('valorporte', 0);
                    $this->db->set('qtdeuco', 0);
                    $this->db->set('valoruco', 0);
                    $this->db->set('valortotal', $valorPacote);
                    $this->db->set('agrupador', 't');
                    if (isset($_POST['valor_diferenciado'])) {
                        $this->db->set('valor_pacote_diferenciado', 't');
                    } else {
                        $this->db->set('valor_pacote_diferenciado', 'f');
                    }

                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_id);
                    $this->db->insert('tb_procedimento_convenio');
                }
            }

            return $procedimento_convenio_id;
        } else {
            return -2;
        }
    }

    function verificaagrupadorconvenio($convenio_id, $procedimento_agrupador_id) {

        $this->db->select('pa.procedimento_tuss_id');
        $this->db->from('tb_procedimentos_agrupados_ambulatorial pa');
        $this->db->where("pa.procedimento_agrupador_id", $procedimento_agrupador_id);
        $this->db->where("pa.ativo", 't');
        $query = $this->db->get();
        $agrupados = $query->result();
//        
        $this->db->select('procedimento_convenio_id');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->where("procedimento_tuss_id IN (SELECT procedimento_tuss_id 
                                                   FROM ponto.tb_procedimentos_agrupados_ambulatorial
                                                   WHERE ativo = 't' AND procedimento_agrupador_id = $procedimento_agrupador_id)");
        $this->db->where("convenio_id", $convenio_id);
        $this->db->where("ativo", 't');
        $query = $this->db->get();
        $procedimentos = $query->result();

        if (count($agrupados) <= count($procedimentos)) {
            return count($procedimentos);
        } else {
            return -1;
        }
    }

    function gravaragrupadornome() {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('convenio_id', $_POST['convenio']);
            if ($_POST['agrupador_id'] == '' || !isset($_POST['agrupador_id'])) {
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_agrupador_procedimento_nome');
                $agrupador_id = $this->db->insert_id();
            } else {
                $agrupador_id = $_POST['agrupador_id'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('agrupador_id', $agrupador_id);
                $this->db->update('tb_agrupador_procedimento_nome');
            }
            $erro = $this->db->_error_message();

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('agrupador_id', $agrupador_id);
            $this->db->update('tb_procedimentos_agrupados');

            if (trim($erro) != "") // erro de banco
                return 0;
            else
                return $agrupador_id;
        } catch (Exception $exc) {
            return 0;
        }
    }

    function gravaragrupadoradicionar() {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select();
            $this->db->from('tb_procedimentos_agrupados');
            $this->db->where('agrupador_id', $_POST['agrupador_id']);
            $this->db->where('procedimento_tuss_id', $_POST['procedimento']);
            $this->db->where("ativo", 't');
            $query = $this->db->get()->result();

            if (count($query) == 0) {
                $this->db->set('agrupador_id', $_POST['agrupador_id']);
                $this->db->set('procedimento_tuss_id', $_POST['procedimento']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimentos_agrupados');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return false;
                else
                    return true;
            }
            else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluiragrupadornome($agrupador_id) {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('agrupador_id', $agrupador_id);
            $this->db->update('tb_agrupador_procedimento_nome');


            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return 0;
            else
                return 1;
        } catch (Exception $exc) {
            return 0;
        }
    }

    function excluirprocedimentoagrupador($procedimento_agrupado_id) {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('procedimento_agrupado_id', $procedimento_agrupado_id);
            $this->db->update('tb_procedimentos_agrupados');


            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return 0;
            else
                return 1;
        } catch (Exception $exc) {
            return 0;
        }
    }

    function listarprocedimentoconvenioagrupadorcirurgico($convenio_id) {
        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome as procedimento,
                            pt.codigo');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pc.convenio_id", $convenio_id);
        $this->db->where("pt.grupo", "CIRURGICO");
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaragrupador() {
        $this->db->select('agrupador_id,
                           nome                            
                            ');
        $this->db->from('tb_agrupador_procedimento_nome');
        $this->db->where("ativo", 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('c.nome ilike', "%" . $args['nome'] . "%");
        }

        return $this->db;
    }

    function listarautocompletetuss($parametro = null) {
        $this->db->select('tuss_id,
                            codigo,
                            descricao,
                            ans');
        $this->db->from('tb_tuss');
        if ($parametro != null) {
            $this->db->where('codigo ilike', "%" . $parametro . "%");
            $this->db->orwhere('descricao ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($procedimento_convenio_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimento_convenio_id', $procedimento_convenio_id);
        $this->db->update('tb_procedimento_convenio');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirpercentual($procedimento_percentual_medico_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimento_percentual_medico_id', $procedimento_percentual_medico_id);
        $this->db->update('tb_procedimento_percentual_medico');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    /**
     * Função para gravar valores na tabela TB_SERVIDOR.
     * @author Equipe de desenvolvimento APH
     * @access public
     * @return Resposta true/false da conexão com o banco
     */
    function gravar() {
        try {

            /* inicia o mapeamento no banco */
            $procedimento_convenio_id = $_POST['txtprocedimentoplanoid'];
            $this->db->set('procedimento_tuss_id', $_POST['procedimento']);
            $this->db->set('convenio_id', $_POST['convenio']);
            $this->db->set('qtdech', $_POST['qtdech']);
            $this->db->set('valorch', $_POST['valorch']);
            $this->db->set('qtdefilme', $_POST['qtdefilme']);
            $this->db->set('valorfilme', $_POST['valorfilme']);
            $this->db->set('qtdeporte', $_POST['qtdeporte']);
            $this->db->set('valorporte', $_POST['valorporte']);
            $this->db->set('qtdeuco', $_POST['qtdeuco']);
            $this->db->set('valoruco', $_POST['valoruco']);
            $this->db->set('valortotal', $_POST['valortotal']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txtprocedimentoplanoid'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimento_convenio');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $procedimento_convenio_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('procedimento_convenio_id', $procedimento_convenio_id);
                $this->db->update('tb_procedimento_convenio');
            }

            return $servidor_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarpercentualmedico() {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('procedimento_tuss_id', $_POST['procedimento']);
            $this->db->set('medico', $_POST['medico']);
            $this->db->set('valor', str_replace(",", ".", $_POST['valor']));

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_procedimento_percentual_medico');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $procedimento_id = $this->db->insert_id();


            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($procedimento_convenio_id) {

        if ($procedimento_convenio_id != 0) {
            $this->db->select('procedimento_convenio_id,
                            pc.convenio_id,
                            c.nome as convenio,
                            pc.procedimento_tuss_id,
                            pt.nome as procedimento,
                            pc.qtdech,
                            pc.valorch,
                            pc.qtdefilme,
                            pc.valorfilme,
                            pc.qtdeporte,
                            pc.valorporte,
                            pc.qtdeuco,
                            pc.valoruco,
                            pc.valortotal');
            $this->db->from('tb_procedimento_convenio pc');
            $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
            $this->db->where("pc.ativo", 't');
            $this->db->where("procedimento_convenio_id", $procedimento_convenio_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_procedimento_convenio_id = $procedimento_convenio_id;
            $this->_convenio_id = $return[0]->convenio_id;
            $this->_convenio = $return[0]->convenio;
            $this->_procedimento_tuss_id = $return[0]->procedimento_tuss_id;
            $this->_procedimento = $return[0]->procedimento;
            $this->_qtdech = $return[0]->qtdech;
            $this->_valorch = $return[0]->valorch;
            $this->_qtdefilme = $return[0]->qtdefilme;
            $this->_valorfilme = $return[0]->valorfilme;
            $this->_qtdeporte = $return[0]->qtdeporte;
            $this->_valorporte = $return[0]->valorporte;
            $this->_qtdeuco = $return[0]->qtdeuco;
            $this->_valoruco = $return[0]->valoruco;
            $this->_valortotal = $return[0]->valortotal;
        } else {
            $this->_procedimento_convenio_id = null;
            $this->_qtdech = 0;
            $this->_valorch = 0;
            $this->_qtdefilme = 0;
            $this->_valorfilme = 0;
            $this->_qtdeporte = 0;
            $this->_valorporte = 0;
            $this->_qtdeuco = 0;
            $this->_valoruco = 0;
        }
    }

}

?>
