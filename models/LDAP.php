<?php
/**
 * Created by PhpStorm.
 * User: seryakovda
 * Date: 08.04.2021
 * Time: 15:33
 */

namespace models;

/**
 * Авторизация (auth) и информация о пользователе (info) через Active Directory
 * @example http://ad.site.ru/services/ad.php?action=auth&login=user&pwd=password&remote_addr=192.168.0.20
 * @example http://ad.site.ru/services/ad.php?action=info&login=user&remote_addr=192.168.0.20
 */

class LDAP
{
    protected
        $ldapConnected = false,
        $ldapServer = 'organisation.local',
        $ldapDn = "DC=organisation,DC=local",
        $ldapUserDomain = 'organisation\\',
        // системный пользователь - для получения информации о любом пользователе без пароля
        $SYS_LOGIN = 'sysuser',
        $SYS_PWD = 'syspassword';
    protected $ACTION, $PWD, $ldapEntries, $ldapconn, $REMOTE_ADDR, $ldapbind , $LOGIN;


    public function run() {
        try {
            $this->getRequest();

            switch($this->ACTION) {
                // Проверка авторизации (логин-пароль)
                case 'auth':
                    if (empty($this->LOGIN) || empty($this->PWD)) throw new \Exception('EMPTY CREDS', 1);
                    $this->ldapConnect();
                    $this->ldapBind($this->LOGIN, $this->PWD);
                    $resp = array('result' => 'success');
                    break;
                // Информация о пользователе по логину
                case 'info':
                    if (empty($this->LOGIN)) throw new \Exception('EMPTY LOGIN', 1);
                    $this->ldapConnect();
                    $this->ldapBind($this->SYS_LOGIN, $this->SYS_PWD);
                    $this->ldapSearch();
                    $it = $this->ldapEntries[0];
                    $resp = array(
                        'result' => 'success',
                        'samaccountname' => $it['samaccountname'][0],
                        'displayname' => $it['displayname'][0],
                        'company' => $it['company'][0],
                        'mail' => $it['mail'][0],
                        'lablecomputer' => $it['lablecomputer'][0],
                        'department' => $it['department'][0],
                        'description' => $it['description'][0],
                    );
                    break;
                default:
                    $this->ACTION = 'Wrong action';
                    throw new \Exception('Wrong action', 1);
            }
            if($this->ldapConnected) { $this->ldapClose(); }
            syslog(LOG_INFO, "[AD][{$this->ACTION}][ok][remote_addr={$this->REMOTE_ADDR} login={$this->LOGIN}]");
            // header('HTTP/1.1 200 OK', true); // ХЗ почему, но если включить, file_get_contents($uri) будет тормозить, препарация курлом curl --verbose не выявила различий
            die(json_encode($resp));
        } catch (\Exception $e) {
            if($this->ldapConnected) { $this->ldapClose(); }
            syslog(LOG_ERR, "[AD][{$this->ACTION}][error][remote_addr={$this->REMOTE_ADDR} login={$this->LOGIN}]: " . $e->getMessage());
            header('HTTP/1.1 403 Forbidden', true);
            die($e->getMessage());
        }
    }

    protected function getRequest() {
        $this->LOGIN = trim($_REQUEST['login']);
        $this->PWD = trim($_REQUEST['pwd']);
        $this->REMOTE_ADDR = trim($_REQUEST['remote_addr']);
        $this->ACTION = trim($_REQUEST['action']);
    }

    // соединение с сервером
    protected function ldapConnect() {
        $this->ldapconn = ldap_connect($this->ldapServer);
        if($this->ldapconn === false) {
            throw new \Exception('LDAP FAILED');
        }
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        $this->ldapConnected = true;
    }

    protected function ldapBind($LOGIN, $PWD) {
        $this->ldapbind = ldap_bind($this->ldapconn, $this->ldapUserDomain . $LOGIN, $PWD);
        if(!$this->ldapbind) throw new \Exception('NOT FOUND', 2);
    }

    protected function ldapSearch() {
        $filter = '(&(objectCategory=user)(samaccountname=' . $this->LOGIN.'))';
        $sr = ldap_search($this->ldapconn, $this->ldapDn, $filter);
        $this->ldapEntries = ldap_get_entries($this->ldapconn, $sr);
        if($this->ldapEntries['count'] != 1) throw new \Exception('TOO MANY RESULTS: ' . $this->ldapEntries['count'], 3);
    }


    protected function ldapClose() {
        ldap_close($this->ldapconn);
    }
}