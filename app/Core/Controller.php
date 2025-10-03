<?php

namespace App\Core;

class Controller
{
    protected $request;
    protected $response;
    
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    protected function view($view, $data = [])
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: $view");
        }
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        echo $content;
    }
    
    protected function json($data, $statusCode = 200)
    {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Content-Type', 'application/json');
        echo json_encode($data);
    }
    
    protected function redirect($url, $statusCode = 302)
    {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Location', $url);
        exit;
    }
    
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    protected function validate($data, $rules)
    {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }
    
    protected function auth()
    {
        return Session::get('user');
    }
    
    protected function isAuthenticated()
    {
        return Session::has('user');
    }
    
    protected function hasRole($role)
    {
        $user = $this->auth();
        return $user && $user->role_id == $role;
    }
}
