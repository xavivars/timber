<?php

class TimberTestRouter extends WP_UnitTestCase {

	function testThemeRoute(){
		$template = Timber::load_template('single.php');
		$this->assertTrue($template);
	}

	function testThemeRouteDoesntExist(){
		$template = Timber::load_template('singlefoo.php');
		$this->assertFalse($template);
	}

	function testFullPathRoute(){
		$hello = WP_CONTENT_DIR.'/plugins/hello.php';
		$template = Timber::load_template($hello);
		$this->assertTrue($template);
	}

	function testFullPathRouteDoesntExist(){
		$hello = WP_CONTENT_DIR.'/plugins/hello-foo.php';
		$template = Timber::load_template($hello);
		$this->assertFalse($template);
	}

	function testRouterClass(){
		$this->assertTrue(class_exists('PHPRouter\Router'));
	}

	function testAppliedRoute(){
		$_SERVER['REQUEST_METHOD'] = 'GET';
		global $matches;
		$matches = array();
		Timber::add_route('foo', function(){
			global $matches;
			$this->assertTrue(true);
			$matches[] = true;
		});
		$this->go_to(home_url('foo'));
		global $timber;
		$timber->init_routes();
		$this->assertEquals(1, count($matches));
	}

	function testFailedRoute(){
		$_SERVER['REQUEST_METHOD'] = 'GET';
		global $matches;
		$matches = array();
		Timber::add_route('foo', function(){
			$this->assertTrue(false);
			$matches[] = true;
		});
		$this->go_to(home_url('bar'));
		global $timber;
		$timber->init_routes();
		$this->assertEquals(0, count($matches));
	}
}