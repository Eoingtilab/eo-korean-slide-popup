<?php
namespace EOKSP;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin {
    private static $instance = null;
    private $admin;
    private $front;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once EOKSP_PATH . 'includes/class-eoksp-helper.php';
        require_once EOKSP_PATH . 'includes/class-eoksp-admin.php';
        require_once EOKSP_PATH . 'includes/class-eoksp-front.php';

        $this->admin = new Admin();
        $this->front = new Front();
    }
}
