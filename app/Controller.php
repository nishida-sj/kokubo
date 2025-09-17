<?php

abstract class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);

        $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("View {$view} not found at {$viewPath}");
        }

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    protected function redirect($url, $statusCode = 302)
    {
        if (strpos($url, 'http') !== 0) {
            $url = site_url($url);
        }
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAdmin()
    {
        if (!is_admin_logged_in()) {
            $this->redirect('admin/login');
        }
    }
}