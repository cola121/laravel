<?php
namespace APP\Lib;

class JsonResult
{
    const SUCCESS = 'SUCCESS';
    const WARNING = 'WARNING';
    const ERROR   = 'ERROR';
    const FAULT   = 'FAULT';
    const INFO    = 'INFO';

    private $type;
    private $status;
    private $message;
    private $data;

    public function __construct()
    {
        $vars = func_get_args();
        switch (count($vars)) {
            case 0:
                $vars = array(null);
            case 1:
                $keys = array('message');
                $def_vars = array(
                    'type'  => self::SUCCESS,
                    'status'=> 0
                );
                break;
            case 2:
                if (is_int($vars[1]) || is_numeric($vars[1])) {
                    $keys = array('message', 'status');
                    $vars[1] = intval($vars[1]);
                    if ($vars[1]) {
                        $def_vars = array('type'  => self::ERROR);
                    } else {
                        $def_vars = array('type'  => self::SUCCESS);
                    }
                } elseif (is_string($vars[1])) {
                    $keys = array('message', 'type');
                    if ($vars[1] == self::SUCCESS) {
                        $def_vars = array('status'  => 0);
                    } else {
                        $def_vars = array('status'  => 1);
                    }
                } else {
                    throw new Exception();
                }
                break;
            case 3:
                $keys = array('message', 'status', 'type');
                $def_vars = array();
                break;
            case 4:
                $keys = array('message', 'status', 'type', 'data');
                $def_vars = array();
                break;
            default:
                throw new Exception();
        }

        $vars = array_combine($keys, $vars);
        $vars = array_merge($def_vars, $vars);

        $this->status = $vars['status'];
        $this->type = $vars['type'];
        if (!$vars['status'] && ($vars['type'] == 'SUCCESS')) {
            $this->data = $vars['message'];
        } else {
            $this->message = $vars['message'];
            $this->data = $vars['data'];
        }
    }

    public function getJson()
    {
        return str_replace('\r\n', '\n', json_encode(array(
            'level'    => $this->type,
            'errorCode'  => $this->status,
            'errorMessage' => $this->message,
            'data' => $this->data
                )
            )
        );
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function __toString()
    {
        return $this->getJson();
    }
}
