<?php

class saida_model extends Model {

    var $_farmacia_saida_id = null;
    var $_descricao = null;

    function Solicitacao_model($farmacia_saida_id = null) {
        parent::Model();
        if (isset($farmacia_saida_id)) {
            $this->instanciar($farmacia_saida_id);
        }
    }

    function contador($farmacia_saida_id) {
        $this->db->select('ep.descricao');
        $this->db->from('tb_farmacia_saida_itens esi');
        $this->db->join('tb_farmacia_produto ep', 'ep.farmacia_produto_id = esi.produto_id');
        $this->db->where('esi.ativo', 'true');
        $this->db->where('esi.saida_cliente_id', $farmacia_saida_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarclientes() {
        $operador_id = $this->session->userdata('operador_id');
        $this->db->select('farmacia_cliente_id,
                            nome');
        $this->db->from('tb_farmacia_cliente ec');
        $this->db->join('tb_farmacia_operador_cliente oc', 'oc.cliente_id = ec.farmacia_cliente_id');
        $this->db->where('oc.operador_id', $operador_id);
        $this->db->where('ec.ativo', 'true');
        $this->db->where('oc.ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function saidanome($farmacia_saida_id) {
        $this->db->select('nome');
        $this->db->from('tb_farmacia_saida_cliente esc');
        $this->db->join('tb_farmacia_cliente ec', 'ec.farmacia_cliente_id = esc.cliente_id');
        $this->db->where('esc.farmacia_saida_setor_id', $farmacia_saida_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listararmazem() {
        $this->db->select('farmacia_armazem_id,
                            descricao');
        $this->db->from('tb_farmacia_armazem');
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function listarsaidas($farmacia_saida_id) {
        $this->db->select('ep.descricao, esi.farmacia_saida_itens_id, esi.quantidade, esi.exame_id');
        $this->db->from('tb_farmacia_saida_itens esi');
        $this->db->join('tb_farmacia_produto ep', 'ep.farmacia_produto_id = esi.produto_id');
        $this->db->where('esi.ativo', 'true');
        $this->db->where('esi.saida_cliente_id', $farmacia_saida_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarprodutos($farmacia_saida_id) {
        $this->db->select('ep.farmacia_produto_id,
                            ep.descricao');
        $this->db->from('tb_farmacia_produto ep');
        $this->db->join('tb_farmacia_menu_produtos emp', 'emp.produto = ep.farmacia_produto_id');
        $this->db->join('tb_farmacia_menu em', 'em.farmacia_menu_id = emp.menu_id');
        $this->db->join('tb_farmacia_cliente ec', 'ec.menu_id = emp.menu_id');
        $this->db->join('tb_farmacia_saida_cliente esc', 'esc.cliente_id = ec.farmacia_cliente_id');
        $this->db->where('esc.farmacia_saida_setor_id', $farmacia_saida_id);
        $this->db->where('ep.ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorprodutositem($farmacia_saida_itens_id) {
        $this->db->select('ep.farmacia_entrada_id,
                            p.descricao,
                            ep.validade,
                            ea.descricao as armazem');
        $this->db->from('tb_farmacia_saldo ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->join('tb_farmacia_saida_itens esi', 'esi.produto_id = ep.produto_id');
        $this->db->join('tb_farmacia_armazem ea', 'ea.farmacia_armazem_id = ep.armazem_id');
        $this->db->where('esi.farmacia_saida_itens_id', $farmacia_saida_itens_id);
        $this->db->where('ep.ativo', 'true');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarprodutositem($farmacia_saida_itens_id) {
        $this->db->select('ep.farmacia_entrada_id,
                            p.descricao,
                            ep.validade,
                            ea.descricao as armazem,
                            sum(ep.quantidade) as total');
        $this->db->from('tb_farmacia_saldo ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->join('tb_farmacia_saida_itens esi', 'esi.produto_id = ep.produto_id');
        $this->db->join('tb_farmacia_armazem ea', 'ea.farmacia_armazem_id = ep.armazem_id');
        $this->db->where('esi.farmacia_saida_itens_id', $farmacia_saida_itens_id);
        $this->db->where('ep.ativo', 'true');
        $this->db->groupby('ep.farmacia_entrada_id, p.descricao, ep.validade, ea.descricao');
        $this->db->orderby('ep.validade');
        $return = $this->db->get();
        return $return->result();
    }

    function listarsaidaprodutositem($farmacia_saida_itens_id) {
        $this->db->select('ep.farmacia_saida_id,
                            p.descricao,
                            ep.validade,
                            ep.quantidade');
        $this->db->from('tb_farmacia_saida ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->where('ep.farmacia_saida_itens_id', $farmacia_saida_itens_id);
        $this->db->where('ep.ativo', 'true');
        $this->db->orderby('ep.farmacia_saida_id');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorsaida($farmacia_saida_itens_id) {
        $this->db->select('ep.farmacia_saida_id');
        $this->db->from('tb_farmacia_saida ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->where('ep.farmacia_saida_itens_id', $farmacia_saida_itens_id);
        $this->db->where('ep.ativo', 'true');
        $this->db->orderby('ep.farmacia_saida_id');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listasaidapaciente($args = array()) {
        $this->db->select(' p.nome,
                            ip.internacao_prescricao_id,
                            i.internacao_id,
                            o.nome as medico');
        $this->db->from('tb_internacao_prescricao ip');
        $this->db->join('tb_internacao i', 'ip.internacao_id = i.internacao_id ');
        $this->db->join('tb_operador o', 'o.operador_id = i.operador_cadastro ');
        $this->db->join('tb_paciente p', 'p.paciente_id = i.paciente_id ');
        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
        }
        return $this->db;
    }

    function listasaidapacienteprescricao($internacao_id) {
        $this->db->select(' fp.descricao,
                            ip.internacao_prescricao_id,
                            o.nome as medico');
        $this->db->from('tb_internacao_prescricao ip');
        $this->db->join('tb_internacao i', 'ip.internacao_id = i.internacao_id ');
        $this->db->join('tb_operador o', 'o.operador_id = i.operador_cadastro ');
        $this->db->join('tb_farmacia_produto fp', 'fp.farmacia_produto_id = ip.medicamento_id');
        $this->db->where('i.internacao_id', $internacao_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarsaidaitem($farmacia_saida_id) {
        $this->db->select('ep.farmacia_saida_id,
                            p.descricao,
                            ep.validade,
                            ep.quantidade');
        $this->db->from('tb_farmacia_saida ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->where('ep.saida_cliente_id', $farmacia_saida_id);
        $this->db->where('ep.ativo', 'true');
        $this->db->orderby('ep.farmacia_saida_id');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorsaidaitem($farmacia_saida_id) {
        $this->db->select('ep.farmacia_saida_id');
        $this->db->from('tb_farmacia_saida ep');
        $this->db->join('tb_farmacia_produto p', 'p.farmacia_produto_id = ep.produto_id');
        $this->db->where('ep.saida_cliente_id', $farmacia_saida_id);
        $this->db->where('ep.ativo', 'true');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listar($args = array()) {
        $operador_id = $this->session->userdata('operador_id');
        $this->db->select('es.farmacia_saida_setor_id,
                            es.cliente_id,
                            ec.nome as cliente,
                            es.data_cadastro,
                            es.situacao');
        $this->db->from('tb_farmacia_saida_cliente es');
        $this->db->join('tb_farmacia_cliente ec', 'ec.farmacia_cliente_id = es.cliente_id');
        $this->db->join('tb_farmacia_operador_cliente oc', 'oc.cliente_id = ec.farmacia_cliente_id');
        $this->db->where('es.ativo', 'true');
        $this->db->where('oc.operador_id', $operador_id);
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('ec.nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function listarsaida($farmacia_saida_id) {
        $this->db->select('farmacia_saida_id,
                            descricao');
        $this->db->from('tb_farmacia_saida');
        $this->db->where('ativo', 'true');
        $this->db->where('farmacia_saida_id', $farmacia_saida_id);
        $return = $this->db->get();
        return $return->result();
    }

    function carregarsaida($farmacia_saida_id) {
        $this->db->select('farmacia_saida_id,
                            descricao');
        $this->db->from('tb_farmacia_saida');
        $this->db->where('farmacia_saida_id', $farmacia_saida_id);
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($farmacia_saida_setor_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('farmacia_saida_setor_id', $farmacia_saida_setor_id);
        $this->db->update('tb_farmacia_saida_cliente');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function gravar() {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('cliente_id', $_POST['setor']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_farmacia_saida_cliente');
            $farmacia_saida_id = $this->db->insert_id();
            return $farmacia_saida_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarsaidapaciente($setor) {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('cliente_id', $setor);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_farmacia_saida_cliente');
            $farmacia_saida_id = $this->db->insert_id();
            return $farmacia_saida_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($farmacia_saida_id) {

        if ($farmacia_saida_id != 0) {
            $this->db->select('farmacia_saida_id, descricao');
            $this->db->from('tb_farmacia_saida');
            $this->db->where("farmacia_saida_id", $farmacia_saida_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_farmacia_saida_id = $farmacia_saida_id;
            $this->_descricao = $return[0]->descricao;
        } else {
            $this->_farmacia_saida_id = null;
        }
    }

}

?>
