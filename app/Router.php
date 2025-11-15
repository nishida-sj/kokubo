<?php
// ルーター クラス

class Router
{
    private $routes = [];

    public function define($routes)
    {
        $this->routes = $routes;
    }

    public function resolve($uri, $method = 'GET')
    {
        // クエリパラメータを除去
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        foreach ($this->routes as $route => $controller) {
            $pattern = $this->convertRouteToRegex($route);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // 最初の完全マッチを削除

                return $this->callController($controller, $matches, $method);
            }
        }

        // 404エラー
        return $this->render404();
    }

    private function convertRouteToRegex($route)
    {
        // パラメータを正規表現に変換 {param} -> ([^/]+)
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        // エスケープ
        $pattern = str_replace('/', '\/', $pattern);
        return "/^{$pattern}$/";
    }

    private function callController($controller, $params = [], $method = 'GET')
    {
        list($controllerName, $action) = explode('@', $controller);

        // 管理画面のコントローラーパス調整
        if (strpos($controllerName, 'Admin/') === 0) {
            $controllerPath = APP_PATH . '/Controllers/' . $controllerName . '.php';

            // 複数のクラス名形式を試す
            $possibleClassNames = [
                str_replace('/', '_', $controllerName),  // Admin_WorksController
                str_replace('/', '\\', $controllerName), // Admin\WorksController
            ];
        } else {
            $controllerPath = APP_PATH . '/Controllers/' . $controllerName . '.php';
            $possibleClassNames = [$controllerName];
        }

        if (!file_exists($controllerPath)) {
            throw new Exception("Controller not found: {$controllerPath}");
        }

        require_once $controllerPath;

        // 複数のクラス名形式を試す
        $className = null;
        foreach ($possibleClassNames as $possibleName) {
            if (class_exists($possibleName, false)) {
                $className = $possibleName;
                break;
            }
        }

        if (!$className) {
            throw new Exception("Class not found. Tried: " . implode(', ', $possibleClassNames));
        }

        $controllerInstance = new $className();

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception("Method not found: {$className}@{$action}");
        }

        // HTTPメソッドチェック
        if ($method === 'POST' && !in_array($action, ['send', 'login', 'store', 'update', 'delete'])) {
            throw new Exception("Method not allowed: {$method}");
        }

        return call_user_func_array([$controllerInstance, $action], $params);
    }

    private function render404()
    {
        header("HTTP/1.0 404 Not Found");

        if (file_exists(APP_PATH . '/Views/pages/404.php')) {
            ob_start();
            $title = '404 Not Found';
            require APP_PATH . '/Views/layouts/base.php';
            return ob_get_clean();
        }

        return '<h1>404 Not Found</h1><p>お探しのページが見つかりません。</p>';
    }
}