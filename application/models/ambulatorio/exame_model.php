<?php

class exame_model extends Model {

    var $_agenda_exames_id = null;
    var $_horarioagenda_id = null;
    var $_paciente_id = null;
    var $_procedimento_tuss_id = null;
    var $_inicio = null;
    var $_fim = null;
    var $_confirmado = null;
    var $_ativo = null;
    var $_nome = null;
    var $_data_inicio = null;
    var $_data_fim = null;

    function exame_model($agenda_exames_id = null) {
        parent::Model();
        if (isset($agenda_exames_id)) {
            $this->instanciar($agenda_exames_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('agenda_exames_nome_id,
                            nome');
        $this->db->from('tb_agenda_exames_nome');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', $args['nome'] . "%");
        }
        return $this->db;
    }

    function listarhospitais() {
        $this->db->select('hospital_id, 
                               f.nome');
        $this->db->from('tb_hospital f');
        $this->db->join('tb_municipio c', 'c.municipio_id = f.municipio_id', 'left');
        $this->db->where('f.ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesguiamanual($paciente_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.data_autorizacao,
                            ag.ambulatorio_guia_id,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            ae.paciente_id,
                            ae.faturado,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            c.nome as convenio,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_ambulatorio_guia ag');
        $this->db->join('tb_agenda_exames ae', 'ae.guia_id = ag.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('ag.paciente_id', $paciente_id);
        $this->db->where('ag.data_criacao', date("Y-m-d"));
        $this->db->orderby('ae.valor_total desc');
        $return = $this->db->get();
//        var_dump($return->result()); die;
        return $return->result();
    }

    function gravarexamesfaturamentomanual($ambulatorio_guia, $percentual) {
        try {
//            echo '<pre>';
//            var_dump($_POST); die;
            $this->db->select('ag.tipo');
            $this->db->from('tb_procedimento_convenio pc');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
            $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo', 'left');
            $this->db->where('pc.procedimento_convenio_id', $_POST['procedimento1']);
            $return = $this->db->get()->result();
            $tipo = $return[0]->tipo;
//            var_dump($return); die;

            $hora = date("H:i:s");
            $data = date("Y-m-d");
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            $this->db->set('valor', $_POST['valor1']);

            if ($_POST['valortot'] != "") {
//                $this->db->set('valor_bruto', $_POST['valortot']);
            }

            $valortotal = $_POST['valor1'] * $_POST['qtde1'];

            $this->db->set('valor_medico', $percentual[0]->perc_medico);
            $this->db->set('percentual_medico', $percentual[0]->percentual);

            $this->db->set('valor1', $valortotal);
            $this->db->set('valor_total', $valortotal);
            $this->db->set('quantidade', $_POST['qtde1']);
            $this->db->set('autorizacao', $_POST['autorizacao1']);
            $this->db->set('empresa_id', $_POST['txtempresa']);
            $this->db->set('confirmado', 't');
            $this->db->set('tipo', $tipo);
            $this->db->set('ativo', 'f');
            $this->db->set('realizada', 't');
            if ($_POST['medicoagenda'] != "") {
                $this->db->set('medico_consulta_id', $_POST['medicoagenda']);
                $this->db->set('medico_agenda', $_POST['medicoagenda']);
            }
            if ($_POST['crm1'] != "") {
                $this->db->set('medico_solicitante', $_POST['crm1']);
            }

            if ($_POST['sala1'] != "") {
                $this->db->set('agenda_exames_nome_id', $_POST['sala1']);
            }
            $this->db->set('faturado', 't');
            $this->db->set('situacao', 'OK');
            $this->db->set('guia_id', $ambulatorio_guia);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('data', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data']))));
            $this->db->set('data_faturar', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data']))));
            $this->db->set('data_autorizacao', $horario);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('data_realizacao', $horario);
            $this->db->set('operador_realizacao', $operador_id);
            $this->db->set('data_faturamento', $horario);
            $this->db->set('operador_faturamento', $operador_id);
            $this->db->set('operador_autorizacao', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $agenda_exames_id = $this->db->insert_id();

            $this->db->set('empresa_id', $_POST['txtempresa']);
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
            $this->db->set('medico_realizador', $_POST['medicoagenda']);
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('guia_id', $_POST['txtguia_id']);
            $this->db->set('agenda_exames_id', $agenda_exames_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_exames');
            $exames_id = $this->db->insert_id();

            if ($_POST['laudo'] == "on") {
                $this->db->set('empresa_id', $_POST['txtempresa']);
                $this->db->set('data', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data']))));
                $this->db->set('medico_parecer1', $_POST['medicoagenda']);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['procedimento1']);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $ambulatorio_guia);
                $this->db->set('tipo', $_POST['tipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);

                $this->db->insert('tb_ambulatorio_laudo');
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarprocedimentocirurgicoconvenio($convenio_id) {
        $this->db->select('pc.procedimento_convenio_id,
                           pc.valortotal,
                           pt.codigo,
                           pt.nome');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('pc.ativo', 'true');
        $this->db->where('pc.convenio_id', $convenio_id);
        $return = $this->db->get();
        return $return->result();
    }
    
        function listarprocedimentosadcionados($guia_id) {
        $this->db->select('ae.agenda_exames_id,
                           ae.horario_especial,
                           ae.data_autorizacao,
                           ae.data_realizacao,
                           ae.valor_total,
                           pt.nome');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('ae.cancelada', 'f');
        $this->db->where('ae.guia_id', $guia_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarguiafaturamentomanualambulatorial($paciente_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ambulatorio_guia_id');
        $this->db->from('tb_ambulatorio_guia');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('paciente_id', $paciente_id);
        $this->db->where('data_criacao', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data']))));
        $return = $this->db->get();
        return $return->result();
    }

    function gravarguiamanual($paciente_id) {
//        var_dump($paciente_id);die;
        $horario = date("Y-m-d H:i:s");
        $data = date("Y-m-d");
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->set('empresa_id', $empresa_id);
        $this->db->set('tipo', 'EXAME');
        $this->db->set('data_criacao', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data']))));
        $this->db->set('convenio_id', $_POST['convenio1']);
        $this->db->set('paciente_id', $paciente_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_ambulatorio_guia');
        $ambulatorio_guia_id = $this->db->insert_id();
        return $ambulatorio_guia_id;
    }
    
    
    function listarguiafaturamentomanualcirurgico() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('g.ambulatorio_guia_id,
                            aee.valor,
                            p.nome as paciente,
                            ae.agenda_exames_id,
                            ae.faturado,
                            ae.numero_sessao,
                            g.data_criacao,
                            g.equipe,
                            ae.autorizacao,
                            c.nome,
                            ae.financeiro,
                            pt.nome as procedimento,
                            pt.codigo,
                            o.nome as medico,
                            ae.paciente_id,
                            g.observacoes');
        $this->db->from('tb_agenda_exame_equipe aee');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = aee.agenda_exames_id');
        $this->db->join('tb_ambulatorio_guia g', 'g.ambulatorio_guia_id = ae.guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id= al.medico_parecer1', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');

        $this->db->where("aee.ativo", 't');
        $this->db->where("g.data_criacao >=", date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where("g.data_criacao <=", date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));

        $this->db->where('ae.cancelada', 'false');

        if (isset($_POST['nome']) && strlen($_POST['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $_POST['nome'] . "%");
        }
        if (isset($_POST['convenio']) && $_POST['convenio'] != "") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }

        $this->db->orderby('g.ambulatorio_guia_id');
        $this->db->orderby('g.data_criacao');
        $this->db->orderby('p.nome');
        $this->db->orderby('ae.numero_sessao');
        $this->db->orderby('ae.paciente_id');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompletepaciente($parametro = null) {
        $this->db->select('paciente_id,
                            nome,
                            telefone,
                            leito,
                            nascimento,
                            cpf');
        $this->db->from('tb_paciente');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listarobservacoes($agenda_exame_id) {
        $this->db->select('observacoes');
        $this->db->from('tb_agenda_exames');
        $this->db->where('agenda_exames_id', $agenda_exame_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedico() {
        $this->db->select('operador_id,
            nome');
        $this->db->from('tb_operador');
        $this->db->where('consulta', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function listarsalas() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_sala_id,
                            nome');
        $this->db->from('tb_exame_sala');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 'true');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarsalastotal() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_sala_id,
                            nome');
        $this->db->from('tb_exame_sala');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listargrupo($agenda_exames_id) {
        $this->db->select('pt.grupo');
        $this->db->from('tb_agenda_exames e');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->where('e.agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicoagenda($agenda_exames_id) {
        $this->db->select('medico_agenda');
        $this->db->from('tb_agenda_exames');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarsalaagenda($agenda_exames_id) {
        $this->db->select('agenda_exames_nome_id, tipo');
        $this->db->from('tb_agenda_exames');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listartodassalas() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_sala_id,
                            nome');
        $this->db->from('tb_exame_sala');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexames($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('e.exames_id,
                            e.agenda_exames_id,
                            e.paciente_id,
                            p.nome as paciente,
                            e.agenda_exames_id,
                            e.sala_id,
                            ae.inicio,
                            e.guia_id,
                            e.procedimento_tuss_id,
                            e.data_cadastro,
                            es.nome as sala,
                            o.nome as tecnico,
                            pt.nome as procedimento');
        $this->db->from('tb_exames e');
        $this->db->join('tb_paciente p', 'p.paciente_id = e.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = e.sala_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = e.tecnico_realizador', 'left');
        $this->db->where('e.situacao', 'EXECUTANDO');
        $this->db->where('pt.grupo !=', 'CONSULTA');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('e.cancelada', 'false');
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('e.sala_id', $args['sala']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function listarexamespendentes($args = array()) {
        $this->db->select('e.exames_id,
                            e.paciente_id,
                            p.nome as paciente,
                            e.agenda_exames_id,
                            e.sala_id,
                            ae.inicio,
                            e.data_cadastro,
                            es.nome as sala,
                            pt.nome as procedimento');
        $this->db->from('tb_exames e');
        $this->db->join('tb_paciente p', 'p.paciente_id = e.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = e.sala_id', 'left');
        $this->db->where('e.situacao', 'PENDENTE');
        $this->db->where('e.cancelada', 'false');
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('e.sala_id', $args['sala']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function listararquivo($agenda_exames_id) {
        $this->db->select(' ae.paciente_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.sexo,
                            ae.agenda_exames_id,
                            ae.inicio,
                            pt.nome as procedimento,
                            pc.procedimento_tuss_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('ae.agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listardicom($agenda_exames_id) {
        $this->db->select('e.exames_id,
                            e.paciente_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.sexo,
                            e.agenda_exames_id,
                            e.sala_id,
                            ae.inicio,
                            c.nome as convenio,
                            e.tecnico_realizador,
                            o.nome as tecnico,
                            e.data_cadastro,
                            pt.nome as procedimento,
                            pt.codigo,
                            pc.procedimento_tuss_id');
        $this->db->from('tb_exames e');
        $this->db->join('tb_paciente p', 'p.paciente_id = e.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = e.tecnico_realizador', 'left');
        $this->db->where('ae.guia_id', $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function contador($parametro, $agenda_exames_nome_id) {
        $this->db->select();
        $this->db->from('tb_agenda_exames');
        $this->db->where('data', $parametro);
        $this->db->where('nome_id', $agenda_exames_nome_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarexameagenda($parametro, $agenda_exames_nome_id) {
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.paciente_id,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = ae.procedimento_tuss_id', 'left');
        $this->db->orderby('inicio');
        $this->db->where('ae.data', $parametro);
        $this->db->where('ae.nome_id', $agenda_exames_nome_id);
        $return = $this->db->get();

        return $return->result();
    }

    function listarexameagendaconfirmada($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ordenador,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.confirmado', 'true');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['tipo']) && strlen($args['tipo']) > 0) {
            $this->db->where('ae.tipo', $args['tipo']);
        }
        return $this->db;
    }

    function listarexameagendaconfirmada2($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ordenador,
                            ae.data_autorizacao,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->orderby('ae.ordenador');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.data_autorizacao');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.confirmado', 'true');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['tipo']) && strlen($args['tipo']) > 0) {
            $this->db->where('ae.tipo', $args['tipo']);
        }
        return $this->db;
    }

    function listarexamecaixaespera($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('g.ambulatorio_guia_id,
                            sum(ae.valor_total) as valortotal,
                            sum(ae.valor1) as valorfaturado,
                            p.nome as paciente,
                            g.data_criacao,
                            ae.paciente_id');
        $this->db->from('tb_ambulatorio_guia g');
        $this->db->join('tb_agenda_exames ae', 'ae.guia_id = g.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("c.dinheiro", 't');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.confirmado', 'true');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('ae.faturado', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }

        return $this->db;
    }

    function listarexamesguia($guia_id) {

        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            al.ambulatorio_laudo_id as laudo,
                            ae.situacao,
                            c.nome as convenio,
                            ae.guia_id,
                            pc.valortotal,
                            ae.quantidade,
                            ae.valor_total,
                            ae.autorizacao,
                            ae.paciente_id,
                            ae.faturado,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            pt.nome as exame,
                            c.nome as convenio,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('ae.guia_id', $guia_id);
        $this->db->orderby('ae.valor_total desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamemultifuncao($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.realizada,
                            ae.confirmado,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            c.nome as convenio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function listarexamemultifuncao2($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.realizada,
                            ae.confirmado,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            c.nome as convenio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.inicio');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->limit(5);
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function listarestatisticapaciente($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.realizada,
                            ae.confirmado,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            c.nome as convenio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.situacao', 'OK');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->limit(5);
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarestatisticasempaciente($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.realizada,
                            ae.confirmado,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            c.nome as convenio,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.situacao', 'LIVRE');
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->limit(5);
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarexamemultifuncaoconsulta($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            p.celular,
                            p.telefone,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'CONSULTA');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function listarexamemultifuncaoconsulta2($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.realizada,
                            ae.confirmado,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            c.nome as convenio,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            tc.descricao as tipoconsulta,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_ambulatorio_tipo_consulta tc', 'tc.ambulatorio_tipo_consulta_id = ae.tipo_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.inicio');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'CONSULTA');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function listarexamemultifuncaofisioterapia($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            p.celular,
                            p.telefone,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->where('ae.data >=', $data);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'FISIOTERAPIA');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function listarexamemultifuncaofisioterapia2($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.realizada,
                            ae.confirmado,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            c.nome as convenio,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            tc.descricao as tipoconsulta,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_ambulatorio_tipo_consulta tc', 'tc.ambulatorio_tipo_consulta_id = ae.tipo_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.inicio');
        $this->db->where('ae.data >=', $data);
        $this->db->where('numero_sessao', null);
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'FISIOTERAPIA');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
//        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_consulta_id', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        }
        return $this->db;
    }

    function autorizarsessaofisioterapia($paciente_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.situacao,
                            ae.guia_id,
                            ae.realizada,
                            ae.confirmado,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.telefonema,
                            ae.numero_sessao,
                            ae.observacoes,
                            p.celular,
                            ae.bloqueado,
                            p.telefone,
                            c.nome as convenio,
                            o.nome as medicoagenda,
                            an.nome as sala,
                            tc.descricao as tipoconsulta,
                            p.nome as paciente,
                            op.nome as secretaria,
                            ae.procedimento_tuss_id,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->join('tb_ambulatorio_tipo_consulta tc', 'tc.ambulatorio_tipo_consulta_id = ae.tipo_consulta_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.operador_atualizacao', 'left');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.numero_sessao');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.paciente_id', $paciente_id);
        $this->db->where('ae.tipo', 'FISIOTERAPIA');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.numero_sessao >=', '1');
        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.confirmado', 'false');
        $this->db->where('ae.cancelada', 'false');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmultifuncaomedico($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->where('pt.grupo !=', 'CONSULTA');
//        $this->db->where('pt.grupo !=', 'LABORATORIAL');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        } if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_agenda', $args['medico']);
        }
        return $this->db;
    }

    function listarmultifuncao2medico($args = array()) {


        $teste = empty($args);
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            pt.grupo,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'EXAME');
//        $this->db->where('pt.grupo !=', 'CONSULTA');
//        $this->db->where('pt.grupo !=', 'LABORATORIAL');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.inicio');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            }
            if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarmultifuncaomedicolaboratorial($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('pt.grupo', 'LABORATORIAL');
//        $this->db->where('pt.grupo !=', 'CONSULTA');
//        $this->db->where('pt.grupo !=', 'LABORATORIAL');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ae.data', $args['data']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ae.situacao', $args['situacao']);
        } if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ae.medico_agenda', $args['medico']);
        }
        return $this->db;
    }

    function listarmultifuncao2medicolaboratorial($args = array()) {


        $teste = empty($args);
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            pt.grupo,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('pt.grupo', 'LABORATORIAL');
//        $this->db->where('pt.grupo !=', 'CONSULTA');
//        $this->db->where('pt.grupo !=', 'LABORATORIAL');
        $this->db->orderby('ae.data');
        $this->db->orderby('ae.inicio');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            }
            if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarmultifuncaoconsulta($args = array()) {
        $teste = empty($args);
        $empresa_id = $this->session->userdata('empresa_id');
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            o.nome as medicoconsulta,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'CONSULTA');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            } if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarmultifuncao2consulta($args = array()) {
        $teste = empty($args);
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.data_autorizacao,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            o.nome as medicoconsulta,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            e.exames_id,
                            e.sala_id,
                            c.nome as convenio,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'CONSULTA');
        $this->db->orderby('ae.realizada', 'desc');
        $this->db->orderby('al.situacao');
        $this->db->orderby('ae.data_autorizacao');
//        $this->db->orderby('ae.data');
//        $this->db->orderby('ae.inicio');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            } if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarmultifuncaofisioterapia($args = array()) {
        $teste = empty($args);
        $empresa_id = $this->session->userdata('empresa_id');
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            o.nome as medicoconsulta,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.tipo', 'FISIOTERAPIA');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            } if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarmultifuncao2fisioterapia($args = array()) {
        $teste = empty($args);
        $operador_id = $this->session->userdata('operador_id');
        $dataAtual = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.agenda_exames_id,
                            ae.agenda_exames_nome_id,
                            ae.data,
                            ae.inicio,
                            ae.data_autorizacao,
                            ae.fim,
                            ae.ativo,
                            ae.telefonema,
                            ae.situacao,
                            ae.guia_id,
                            ae.data_atualizacao,
                            ae.paciente_id,
                            ae.observacoes,
                            ae.realizada,
                            al.medico_parecer1,
                            al.ambulatorio_laudo_id,
                            al.exame_id,
                            al.procedimento_tuss_id,
                            p.paciente_id,
                            an.nome as sala,
                            o.nome as medicoconsulta,
                            p.nome as paciente,
                            ae.procedimento_tuss_id,
                            ae.confirmado,
                            c.nome as convenio,
                            pt.nome as procedimento,
                            al.situacao as situacaolaudo');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_consulta_id', 'left');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.confirmado', 't');
        $this->db->where('ae.tipo', 'FISIOTERAPIA');
        $this->db->orderby('ae.realizada', 'desc');
        $this->db->orderby('al.situacao');
        $this->db->orderby('ae.data_autorizacao');
//        $this->db->orderby('ae.data');
//        $this->db->orderby('ae.inicio');
//        $this->db->where('ae.confirmado', 'true');
//        $this->db->where('ae.ativo', 'false');
//        $this->db->where('ae.realizada', 'false');
        $this->db->where('ae.cancelada', 'false');
        if ($teste == true) {
//        if ((!isset($args['nome'])&& $args['nome'] == 0) || (!isset($args['data'])&& strlen($args['data']) == '') || (!isset($args['sala'])&& strlen($args['sala']) == '') || (!isset($args['medico'])&& strlen($args['medico']) =='')) {
            $this->db->where('ae.medico_consulta_id', $operador_id);
            $this->db->where('ae.data', $dataAtual);
        } else {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
            }
            if (isset($args['data']) && strlen($args['data']) > 0) {
                $this->db->where('ae.data', $args['data']);
            }
            if (isset($args['sala']) && strlen($args['sala']) > 0) {
                $this->db->where('ae.agenda_exames_nome_id', $args['sala']);
            }
            if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
                $this->db->where('ae.situacao', $args['situacao']);
            } if (isset($args['medico']) && strlen($args['medico']) > 0) {
                $this->db->where('ae.medico_consulta_id', $args['medico']);
            }
        }
        return $this->db;
    }

    function listarguiafaturamento() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('g.ambulatorio_guia_id,
                            ae.valor_total as valortotal,
                            ae.valor1 as valorfaturado,
                            p.nome as paciente,
                            ae.agenda_exames_id,
                            ae.faturado,
                            g.data_criacao,
                            c.nome,
                            ae.financeiro,
                            pt.nome as procedimento,
                            o.nome as medico,
                            ae.paciente_id');
        $this->db->from('tb_ambulatorio_guia g');
        $this->db->join('tb_agenda_exames ae', 'ae.guia_id = g.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id= al.medico_parecer1', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('g.data_criacao >=', $_POST['txtdata_inicio']);
        $this->db->where('g.data_criacao <=', $_POST['txtdata_fim']);
        $this->db->where("c.dinheiro", 'f');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($_POST['nome']) && strlen($_POST['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $_POST['nome'] . "%");
        }
        if (isset($_POST['convenio']) && $_POST['convenio'] != "") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $this->db->orderby('g.ambulatorio_guia_id');
        $this->db->orderby('g.data_criacao');
        $this->db->orderby('p.nome');
        $this->db->orderby('ae.paciente_id');
        $this->db->orderby('c.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listargxmlfaturamento($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('g.ambulatorio_guia_id,
                            ae.valor_total,
                            ae.valor,
                            ae.autorizacao,
                            p.convenionumero,
                            p.nome as paciente,
                            o.nome as medico,
                            o.conselho,
                            o.cbo_ocupacao_id,
                            o.cpf,
                            ae.data_autorizacao,
                            ae.data_realizacao,
                            pt.codigo,
                            pt.nome as procedimento,
                            ae.data,
                            c.nome as convenio,
                            ae.quantidade,
                            e.data_cadastro,
                            e.data_atualizacao,
                            g.data_criacao,
                            ae.paciente_id');
        $this->db->from('tb_ambulatorio_guia g');
        $this->db->join('tb_agenda_exames ae', 'ae.guia_id = g.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("c.dinheiro", 'f');
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($_POST['datainicio']) && strlen($_POST['datainicio']) > 0) {
            $this->db->where('g.data_criacao >=', $_POST['datainicio']);
        }
        if (isset($_POST['datafim']) && strlen($_POST['datafim']) > 0) {
            $this->db->where('g.data_criacao <=', $_POST['datafim']);
        }
        if (isset($_POST['convenio']) && $_POST['convenio'] != "") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($procedimento_tuss_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimento_tuss_id', $procedimento_tuss_id);
        $this->db->update('tb_procedimento_tuss');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function autorizarsessao($agenda_exames_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('confirmado', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_autorizacao', $horario);
        $this->db->set('operador_autorizacao', $operador_id);
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->update('tb_agenda_exames');
    }

    function gravarnome($nome) {
        try {
            $this->db->set('nome', $nome);
            $this->db->insert('tb_agenda_exames_nome');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $agenda_exames_nome_id = $this->db->insert_id();
            return $agenda_exames_nome_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function contadorexames() {

        $this->db->select('agenda_exames_id');
        $this->db->from('tb_exames');
        $this->db->where('situacao !=', 'CANCELADO');
        $this->db->where("agenda_exames_id", $_POST['txtagenda_exames_id']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function contadorexamestodos() {

        $this->db->select('pt.grupo');
        $this->db->from('tb_exames e');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->where('e.situacao !=', 'CANCELADO');
        $this->db->where("e.guia_id", $_POST['txtguia_id']);
        $this->db->where("pt.grupo", $_POST['txtgrupo']);
        $return = $this->db->count_all_results();
        return $return;
    }

    function gravarexame() {
        try {
            $horario = date("Y-m-d H:i:s");
            $data = date("Y-m-d");
            $operador_id = $this->session->userdata('operador_id');
            $empresa_id = $this->session->userdata('empresa_id');

            if ($_POST['txttipo'] == 'EXAME') {

                $this->db->set('ativo', 'f');
                $this->db->where('exame_sala_id', $_POST['txtsalas']);
                $this->db->update('tb_exame_sala');

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $agenda_exames_id = $_POST['txtagenda_exames_id'];
                $this->db->set('sala_id', $_POST['txtsalas']);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_realizador', $_POST['txtmedico']);
                }
                if ($_POST['txttecnico'] != "") {
                    $this->db->set('tecnico_realizador', $_POST['txttecnico']);
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_exames');
                $exames_id = $this->db->insert_id();

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data', $data);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_parecer1', $_POST['txtmedico']);
                }
                $this->db->insert('tb_ambulatorio_laudo');
                $guia_id = $_POST['txtguia_id'];

                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_consulta_id', $_POST['txtmedico']);
                    $this->db->set('medico_agenda', $_POST['txtmedico']);
                }
                $this->db->set('realizada', 'true');
                $this->db->set('data_realizacao', $horario);
                $this->db->set('operador_realizacao', $operador_id);
                $this->db->set('agenda_exames_nome_id', $_POST['txtsalas']);
                $this->db->where('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->update('tb_agenda_exames');

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->set('sala_id', $_POST['txtsalas']);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->insert('tb_ambulatorio_chamada');
            }

            if ($_POST['txttipo'] == 'CONSULTA') {

                $this->db->set('situacao', 'FINALIZADO');
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                if ($_POST['indicado'] == 'on') {
                    $this->db->set('indicado', 't');
                }
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $agenda_exames_id = $_POST['txtagenda_exames_id'];
                $this->db->set('sala_id', $_POST['txtsalas']);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_realizador', $_POST['txtmedico']);
                }
                $this->db->set('tecnico_realizador', $_POST['txttecnico']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_exames');
                $exames_id = $this->db->insert_id();

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data', $data);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_parecer1', $_POST['txtmedico']);
                }
                $this->db->insert('tb_ambulatorio_laudo');
                $guia_id = $_POST['txtguia_id'];

                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_consulta_id', $_POST['txtmedico']);
                    $this->db->set('medico_agenda', $_POST['txtmedico']);
                }
                $this->db->set('realizada', 'true');
                $this->db->set('data_realizacao', $horario);
                $this->db->set('operador_realizacao', $operador_id);
                $this->db->set('agenda_exames_nome_id', $_POST['txtsalas']);
                $this->db->where('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->update('tb_agenda_exames');

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->set('sala_id', $_POST['txtsalas']);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->insert('tb_ambulatorio_chamada');
            }

            if ($_POST['txttipo'] == 'FISIOTERAPIA') {

                $this->db->set('situacao', 'FINALIZADO');
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                if ($_POST['indicado'] == 'on') {
                    $this->db->set('indicado', 't');
                }
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $agenda_exames_id = $_POST['txtagenda_exames_id'];
                $this->db->set('sala_id', $_POST['txtsalas']);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_realizador', $_POST['txtmedico']);
                }
                $this->db->set('tecnico_realizador', $_POST['txttecnico']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_exames');
                $exames_id = $this->db->insert_id();

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data', $data);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('tipo', $_POST['txttipo']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_parecer1', $_POST['txtmedico']);
                }
                $this->db->insert('tb_ambulatorio_laudo');
                $guia_id = $_POST['txtguia_id'];

                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_consulta_id', $_POST['txtmedico']);
                    $this->db->set('medico_agenda', $_POST['txtmedico']);
                }
                $this->db->set('realizada', 'true');
                $this->db->set('data_realizacao', $horario);
                $this->db->set('operador_realizacao', $operador_id);
                $this->db->set('agenda_exames_nome_id', $_POST['txtsalas']);
                $this->db->where('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->update('tb_agenda_exames');

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
                $this->db->set('sala_id', $_POST['txtsalas']);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->insert('tb_ambulatorio_chamada');
            }

            return $guia_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarexametodos() {
        try {

            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $data = date("Y-m-d");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('ativo', 'f');
            $this->db->where('exame_sala_id', $_POST['txtsalas']);
            $this->db->update('tb_exame_sala');

            $this->db->select('e.procedimento_tuss_id,
                                e.agenda_exames_id');
            $this->db->from('tb_agenda_exames e');
            $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = e.procedimento_tuss_id', 'left');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
            $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
            $this->db->where('e.situacao !=', 'CANCELADO');
            $this->db->where("e.guia_id", $_POST['txtguia_id']);
            $this->db->where("pt.grupo", $_POST['txtgrupo']);
            $query = $this->db->get();
            $return = $query->result();
            $this->db->trans_start();
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
            $this->db->set('sala_id', $_POST['txtsalas']);
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->insert('tb_ambulatorio_chamada');

            foreach ($return as $value) {
                $procedimento = $value->procedimento_tuss_id;
                $agenda_exames_id = $value->agenda_exames_id;


                $this->db->set('realizada', 'true');
                $this->db->set('data_realizacao', $horario);
                $this->db->set('operador_realizacao', $operador_id);
                $this->db->set('agenda_exames_nome_id', $_POST['txtsalas']);
                $this->db->where('agenda_exames_id', $agenda_exames_id);
                $this->db->update('tb_agenda_exames');

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $procedimento);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('agenda_exames_id', $agenda_exames_id);
                $this->db->set('sala_id', $_POST['txtsalas']);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_realizador', $_POST['txtmedico']);
                }
                if ($_POST['txttecnico'] != "") {
                    $this->db->set('tecnico_realizador', $_POST['txttecnico']);
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_exames');
                $exames_id = $this->db->insert_id();

                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data', $data);
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('procedimento_tuss_id', $procedimento);
                $this->db->set('exame_id', $exames_id);
                $this->db->set('guia_id', $_POST['txtguia_id']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                if ($_POST['txtmedico'] != "") {
                    $this->db->set('medico_parecer1', $_POST['txtmedico']);
                }
                $this->db->insert('tb_ambulatorio_laudo');
            }
            $guia_id = $_POST['txtguia_id'];
            $this->db->trans_complete();

            return $guia_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function telefonema($agenda_exame_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('telefonema', 't');
            $this->db->set('data_telefonema', $horario);
            $this->db->set('operador_telefonema', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function observacao($agenda_exame_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('observacoes', $_POST['txtobservacao']);
            $this->db->set('data_observacoes', $horario);
            $this->db->set('operador_observacoes', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function cancelarespera() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', null);
            $this->db->set('procedimento_tuss_id', null);
            $this->db->set('guia_id', null);
            $this->db->set('situacao', "LIVRE");
            $this->db->set('observacoes', "");
            $this->db->set('valor', NULL);
            $this->db->set('ativo', 't');
            $this->db->set('convenio_id', null);
            $this->db->set('autorizacao', null);
            $this->db->set('data_cancelamento', $horario);
            $this->db->set('operador_cancelamento', $operador_id);
            $this->db->set('cancelada', 't');
            $this->db->set('situacao', 'CANCELADO');
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $this->db->where('agenda_exames_id', $_POST['txtagenda_exames_id']);
            $this->db->update('tb_agenda_exames');


            $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_atendimentos_cancelamento');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function bloquear($agenda_exame_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', null);
            $this->db->set('procedimento_tuss_id', null);
            $this->db->set('guia_id', null);
            $this->db->set('situacao', "OK");
            $this->db->set('observacoes', "");
            $this->db->set('valor', NULL);
            $this->db->set('ativo', 'f');
            $this->db->set('bloqueado', 't');
            $this->db->set('convenio_id', null);
            $this->db->set('autorizacao', null);
            $this->db->set('data_bloqueio', $horario);
            $this->db->set('operador_bloqueio', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function desbloquear($agenda_exame_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', null);
            $this->db->set('procedimento_tuss_id', null);
            $this->db->set('guia_id', null);
            $this->db->set('situacao', "OK");
            $this->db->set('observacoes', "");
            $this->db->set('valor', NULL);
            $this->db->set('ativo', 't');
            $this->db->set('bloqueado', 'f');
            $this->db->set('convenio_id', null);
            $this->db->set('autorizacao', null);
            $this->db->set('data_desbloqueio', $horario);
            $this->db->set('operador_desbloqueio', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exame_id);
            $this->db->update('tb_agenda_exames');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function cancelarexame() {
        try {
            $this->db->set('ativo', 't');
            $this->db->where('exame_sala_id', $_POST['txtsala_id']);
            $this->db->update('tb_exame_sala');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', null);
            $this->db->set('procedimento_tuss_id', null);
            $this->db->set('guia_id', null);
            $this->db->set('situacao', "LIVRE");
            $this->db->set('observacoes', "");
            $this->db->set('valor', NULL);
            $this->db->set('ativo', 't');
            $this->db->set('convenio_id', null);
            $this->db->set('autorizacao', null);
            $this->db->set('data_cancelamento', $horario);
            $this->db->set('operador_cancelamento', $operador_id);
            $this->db->set('cancelada', 't');
            $this->db->set('situacao', 'CANCELADO');
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $this->db->where('agenda_exames_id', $_POST['txtagenda_exames_id']);
            $this->db->update('tb_agenda_exames');

            $this->db->set('data_cancelamento', $horario);
            $this->db->set('operador_cancelamento', $operador_id);
            $this->db->set('cancelada', 't');
            $this->db->set('situacao', 'CANCELADO');
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $this->db->where('exames_id', $_POST['txtexames_id']);
            $this->db->update('tb_exames');

            $this->db->set('data_cancelamento', $horario);
            $this->db->set('operador_cancelamento', $operador_id);
            $this->db->set('cancelada', 't');
            $this->db->set('situacao', 'CANCELADO');
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $this->db->where('exame_id', $_POST['txtexames_id']);
            $this->db->update('tb_ambulatorio_laudo');

            $this->db->set('agenda_exames_id', $_POST['txtagenda_exames_id']);
            $this->db->set('paciente_id', $_POST['txtpaciente_id']);
            $this->db->set('procedimento_tuss_id', $_POST['txtprocedimento_tuss_id']);
            $this->db->set('ambulatorio_cancelamento_id', $_POST['txtmotivo']);
            $this->db->set('observacao_cancelamento', $_POST['observacaocancelamento']);
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_atendimentos_cancelamento');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function voltarexame($exame_id, $sala_id, $agenda_exames_id) {
        try {


            $this->db->set('realizada', 'f');
            $this->db->where('agenda_exames_id', $agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            $this->db->set('ativo', 't');
            $this->db->where('exame_sala_id', $sala_id);
            $this->db->update('tb_exame_sala');

            $this->db->where('exames_id', $exame_id);
            $this->db->delete('tb_exames');


            $this->db->where('exame_id', $exame_id);
            $this->db->delete('tb_ambulatorio_laudo');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function finalizarexame($exames_id, $sala_id) {
        try {

            $this->db->set('ativo', 't');
            $this->db->where('exame_sala_id', $sala_id);
            $this->db->update('tb_exame_sala');

            $this->db->set('situacao', 'FINALIZADO');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('exames_id', $exames_id);
            $this->db->update('tb_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function pendenteexame($exames_id, $sala_id) {
        try {

            $this->db->set('ativo', 't');
            $this->db->where('exame_sala_id', $sala_id);
            $this->db->update('tb_exame_sala');

            $this->db->set('situacao', 'PENDENTE');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_pendente', $horario);
            $this->db->set('operador_pendente', $operador_id);
            $this->db->where('exames_id', $exames_id);
            $this->db->update('tb_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarpaciente($agenda_exames_id) {
        try {
            $OK = 'OK';
            $this->db->set('paciente_id', $_POST['txtpacienteid']);
            $this->db->set('procedimento_tuss_id', $_POST['txprocedimento']);
            $this->db->set('situacao', $OK);
            $this->db->set('ativo', 'false');
            if (isset($_POST['txtConfirmado'])) {
                $this->db->set('confirmado', 't');
            }
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('tipo', 'EXAME');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('agenda_exames_id', $agenda_exames_id);
            $this->db->update('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravar($agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $sala_id, $id) {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('horarioagenda_id', $agenda_id);
            $this->db->set('inicio', $horaconsulta);
            $this->db->set('fim', $horaverifica);
            $this->db->set('nome', $nome);
            $this->db->set('data_inicio', $datainicial);
            $this->db->set('data_fim', $datafinal);
            $this->db->set('data', $index);
            $this->db->set('nome_id', $id);
            $this->db->set('agenda_exames_nome_id', $sala_id);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('tipo', 'EXAME');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $agenda_exames_id = $this->db->insert_id();
            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarconsulta($agenda_id, $horaconsulta, $horaverifica, $nome, $datainicial, $datafinal, $index, $medico_id, $id) {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('horarioagenda_id', $agenda_id);
            $this->db->set('inicio', $horaconsulta);
            $this->db->set('fim', $horaverifica);
            $this->db->set('nome', $nome);
            $this->db->set('data_inicio', $datainicial);
            $this->db->set('data_fim', $datafinal);
            $this->db->set('data', $index);
            $this->db->set('tipo_consulta_id', $_POST['txttipo']);
            $this->db->set('nome_id', $id);
            $this->db->set('medico_consulta_id', $medico_id);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('tipo', 'CONSULTA');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_agenda_exames');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $agenda_exames_id = $this->db->insert_id();
            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravardicom($data) {
        try {

            /* inicia o mapeamento no banco */
            $this->db->set('wkl_aetitle', $data['titulo']);
            $this->db->set('wkl_procstep_startdate', $data['data']);
            $this->db->set('wkl_procstep_starttime', $data['hora']);
            $this->db->set('wkl_modality', $data['tipo']);
            $this->db->set('wkl_perfphysname', $data['tecnico']);
            $this->db->set('wkl_procstep_descr', $data['procedimento']);
            $this->db->set('wkl_procstep_id', $data['procedimento_tuss_id']);
            $this->db->set('wkl_reqprocid', $data['procedimento_tuss_id_solicitado']);
            $this->db->set('wkl_reqprocdescr', $data['procedimento_solicitado']);
            $this->db->set('wkl_studyinstuid', $data['identificador_id']);
            $this->db->set('wkl_accnumber', $data['pedido_id']);
            $this->db->set('wkl_reqphysician', $data['solicitante']);
            $this->db->set('wkl_refphysname', $data['referencia']);
            $this->db->set('wkl_patientid', $data['paciente_id']);
            $this->db->set('wkl_patientname', $data['paciente']);
            $this->db->set('wkl_patientbirthdate', $data['nascimento']);
            $this->db->set('wkl_patientsex', $data['sexo']);

            $this->db->insert('tb_integracao');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $agenda_exames_id = $this->db->insert_id();
            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function fecharfinanceiro() {
//        try {
        /* inicia o mapeamento no banco */
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $data = date("Y-m-d");
        $data30 = date('Y-m-d', strtotime("+30 days", strtotime($data)));
        $credor_devedor_id = $_POST['relacao'];
        $conta_id = $_POST['conta'];
        $convenio_id = $_POST['convenio'];
        $data_inicio = $_POST['data1'];
        $data_fim = $_POST['data2'];

        $sql = "UPDATE ponto.tb_agenda_exames
SET operador_financeiro = $operador_id, data_financeiro= '$horario', financeiro = 't'
where agenda_exames_id in (SELECT ae.agenda_exames_id
FROM ponto.tb_agenda_exames ae 
LEFT JOIN ponto.tb_procedimento_convenio pc ON pc.procedimento_convenio_id = ae.procedimento_tuss_id 
LEFT JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = pc.procedimento_tuss_id 
LEFT JOIN ponto.tb_exames e ON e.agenda_exames_id = ae.agenda_exames_id 
LEFT JOIN ponto.tb_ambulatorio_laudo al ON al.exame_id = e.exames_id 
LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id 
WHERE ae.cancelada = 'false' 
AND ae.confirmado >= 'true' 
AND ae.data >= '$data_inicio' 
AND ae.data <= '$data_fim' 
AND c.convenio_id = $convenio_id 
ORDER BY ae.agenda_exames_id)";
        $this->db->query($sql);

        $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['dinheiro'])));
        $this->db->set('devedor', $credor_devedor_id);
        $this->db->set('data', $data30);
        $this->db->set('tipo', 'FATURADO CONVENIO');
        $this->db->set('observacao', "PERIODO $data_inicio ATE $data_fim");
        $this->db->set('conta', $conta_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_financeiro_contasreceber');
    }

    private function instanciar($agenda_exames_id) {

        if ($agenda_exames_id != 0) {
            $this->db->select('agenda_exames_id, horarioagenda_id, paciente_id, procedimento_tuss_id, inicio, fim, nome, ativo, confirmado, data_inicio, data_fim');
            $this->db->from('tb_agenda_exames');
            $this->db->where("agenda_exames_id", $agenda_exames_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_agenda_exames_id = $agenda_exames_id;

            $this->_horarioagenda_id = $return[0]->horarioagenda_id;
            $this->_paciente_id = $return[0]->paciente_id;
            $this->_procedimento_tuss_id = $return[0]->procedimento_tuss_id;
            $this->_inicio = $return[0]->inicio;
            $this->_fim = $return[0]->fim;
            $this->_nome = $return[0]->nome;
            $this->_ativo = $return[0]->ativo;
            $this->_confirmado = $return[0]->confirmado;
            $this->_data_inicio = $return[0]->data_inicio;
            $this->_data_fim = $return[0]->data_fim;
        } else {
            $this->_agenda_exames_id = null;
        }
    }

}

?>
