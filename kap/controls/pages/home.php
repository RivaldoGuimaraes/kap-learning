<?php
class HomeController {
    public function __construct() {
        Oraculum_Plugins::Load('auth'); // Carrega o plugin de autenticação
        if (post('send')) {
            $user=(trim(post('user')));
            $pass=(trim(post('pass')));
            if(($user!='')&&($pass!='')) {
                $auth=new Oraculum_Auth(); // Cria a instância
                $db=new Oraculum_Models('mysql'); // Carrega a configuração do banco mysqç
                $db->LoadModelClass('users');// Mapeia a tabela/entidade usuarios do banco
                $authtableclass=new Users(); // Cria um objeto da entidade

                /* Define qual o objeto corresponde a classe
                do banco com os usuários e passa como parâmetro */
                $auth->setDbAutentication($authtableclass);

                /* Define campos para autenticação */
                $auth->setDbKeyField('userid');
                $auth->setDbUserField('user');
                $auth->setDbPasswordField('password');

                /* Repassa dados do usuario para autenticar */
                $auth->setUser($user);
                $auth->setPassword($pass);

                /* Faz a validação */
                if ($auth->DbAuth()) {
                    $auth->sethomeurl(URL);
                    $auth->setsess(SESS);
                    $fields=array('userid',
                                  'user',
                                  'email',
                                  'status',
                                  'photo',
                                  'usertype',
                                  'lastlogin');
                    $auth->RecordFields($fields);
                    $auth->RecordSession(TRUE);
                } else{
                    Oraculum_Register::set('error','Usu&aacute;rio e/ou senha inv&aacute;lidos!');
                    Oraculum_WebApp::LoadView()
                        ->AddTemplate('geral')
                        ->LoadPage('login');
                }
            } else {
                Oraculum_Register::set('error','Voc&ecirc; deixou algum campo em branco!');
                Oraculum_WebApp::LoadView()
                    ->AddTemplate('geral')
                    ->LoadPage('login');
            }

        } else {
            $auth=new Oraculum_Auth(); // Cria a instância
            $auth->setsess(SESS);
            if ($auth->verify()) { // Verifica se já está logado
                Oraculum_WebApp::LoadView()
                    ->AddTemplate('geral')
                    ->LoadPage('home');
            } else {
                Oraculum_WebApp::LoadView()
                    ->AddTemplate('geral')
                    ->LoadPage('login');
            }
        }
    }
}