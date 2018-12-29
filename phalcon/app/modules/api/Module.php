<?php
namespace app\modules\api;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use app\library\jwt\JWT;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface{

	/* 注册：自动加载模块 */
	public function registerAutoloaders(DiInterface $di=null){
		$loader = new Loader();
		$loader->registerNamespaces([
			'app\modules\api\controller'=>__DIR__.'/controller/',
			'app\modules\api\model'=>__DIR__.'/model/',
		]);
		$loader->register();
	}

	/* 注册：模块服务 */
	public function registerServices(DiInterface $di){
		// 注册：视图
		$di->set('view', function(){
			$view = new View();
			$view->setDI($this);
			$view->setViewsDir(__DIR__.'/view/');
			$view->registerEngines([
				'.php'=>'Phalcon\\Mvc\\View\\Engine\\Php'
			]);
			return $view;
		});
		// 加密
		$di->set('jwt',function(){
			return new JWT();
		});
		// Redis
		$di->set('redis',function(){
			$config = $this->getConfig();
			$redis = new \Redis();
			$redis->connect($config->redis->host,$config->redis->port);
			return $redis;
		});
	}

}