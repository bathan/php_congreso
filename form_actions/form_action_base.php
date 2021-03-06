<?php
include_once __DIR__ . '/../include/config.php';

class form_action_base {

    const ACTION_LOGIN_REGULAR = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_LOGIN_TOKEN = 'login_token';
    const ACTION_REGISTER = 'register';
    const ACTION_UPDATE_INFO = 'update_info';
    const ACTION_FORUM_ADD = 'forum_add';
    const ACTION_DELETE_USER = 'delete_user';
    const ACTION_EMAIL_USERS = 'email_users';
    const ACTION_UPLOAD_TRABAJO = 'upload_trabajo';
    const ACTION_RETRIEVE_PASS = 'retrieve_password';
    const ACTION_VOTE = 'vote';
    const ACTION_COMMENT_TRABAJO = 'add_trabajo_comment';
    const ACTION_SEND_COMMENT_TRABAJO = 'send_trabajo_comment';
    const ACTION_SEND_INIT_EMAIL = 'send_init_email';

    protected $action;
    private $formData = [];
    protected $result;

    public function __construct(Array $formData = []) {
        $this->formData = $formData;

        if(!isset($this->formData['action'])) {
            throw new Exception("[".get_class($this)."] Missing Action");
        }else{
            $this->action = $this->formData['action'];
        }
    }

    protected function validateRequiredFields(Array $requiredFields)
    {
        $missing_fields = [];
        foreach($requiredFields as $rf) {
            if(!isset($rf,$this->formData)) {
                $missing_fields[] = $rf;
            }elseif(@$this->formData[$rf]==''){
                $missing_fields[] = $rf;
            }
        }
        if(count($missing_fields)>0) {
            throw new Exception("Campos requeridos faltantes: ".implode(",",$missing_fields));
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($r) {
        $this->result = $r;
    }
}