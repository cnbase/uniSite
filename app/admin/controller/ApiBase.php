<?php
namespace admin\controller;

use uniPHP\core\{
    PDO,Request
};

class ApiBase
{
    /**
     * 模块ID
     * @var int
     */
    public int $module_id = 1;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PDO
     */
    protected \uniPHP\core\PDO $pdo;

    /**
     * ApiBase constructor.
     * @throws \ErrorException
     */
    public function __construct()
    {
        $this->request = \uniPHP::use('Request');
        $dbConfig = \uniPHP::instance()->getConfig('database');
        $this->pdo = \uniPHP::use('MySQL')::pdo('database',$dbConfig);
    }
}