<?php
// backend/studio_profile.php
return [
    'nome' => 'Diplomas Raúl',
    'descricao' => 'Estúdio de fotografia com foco especial em colégios e escolas, mas que atende a todos os públicos para diplomas, formaturas e ensaios.',
    'endereco' => 'Estúdio Central e Ensaios Externos',
    'telefone' => '(11) 99999-0000', // Altere para o número real dele depois
    'whatsapp' => '(11) 99999-0000', // Altere para o número real dele depois
    'email' => 'contato@diplomasraul.com.br',
    
    // Horários reais atualizados
    'horario' => [
        'segunda_a_quinta' => '08:00 às 20:00',
        'sexta' => '08:00 às 18:00',
        'sabado' => 'Fechado', // Ele não citou sábado na mensagem, então deixei fechado
        'domingo' => '09:00 às 18:00'
    ],
    
    'servicos' => [
        'Fotografia para Colégios e Escolas (Especialidade)',
        'Fotos para Diplomas',
        'Atendimento ao público em geral'
    ],
    
    // Estratégia de vendas atualizada (Sem preços fixos)
    'faixa_valores' => [
        'orcamentos' => 'Os valores base estão no nosso portfólio, mas como cada ensaio pode variar dependendo das suas preferências, os orçamentos e fechamentos são feitos diretamente com o Raúl.',
    ],
    
    // Regra provisória sobre as molduras
    'informacoes_extras' => [
        'molduras' => 'Se o cliente perguntar se a foto vem com moldura, informe educadamente que você precisa verificar as opções disponíveis de moldura diretamente com o Raúl pelo WhatsApp.',
    ],

    'agendamento' => [
        'canais' => 'WhatsApp ou por este Chatbot',
    ],
];