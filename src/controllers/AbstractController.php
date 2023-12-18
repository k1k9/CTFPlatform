<?php
/**
 * AbstractController
 * Defines all Controllers behaviors
 * 
 * @author k1k9
 */
namespace app\controllers;
abstract class AbstractController
{    
    protected array $contentType;
    protected array $statusCode;
    public function __construct(){
        // Define response type
        $this->contentType = [
            'html' => 'Content-Type: text/html; charset=utf-8',
            'json' => 'Content-Type: application/json; charset=utf-8',
        ];

        // Define response code
        $this->statusCode = [
            200 => 'Success - The request was successful.',
            400 => 'Bad Request - The server did not understand the request syntax.',
            401 => 'Unauthorized - Authentication is required to access this resource.',
            403 => 'Forbidden - You do not have permission to access this resource.',
            404 => 'Not Found - The requested resource could not be found on the server.',
            500 => 'Internal Server Error - The server encountered an unexpected condition.',
        ];
    }

    protected function redirect($url) {
        /**
         * Redirect to given url
         */
        header("Location: $url");
        exit();
    }

    protected function returnJson(int $statusCode, $data){
        http_response_code($statusCode);
        header($this->contentType['json']);
        echo json_encode($data);
    }

    protected function renderView(string $view, array $data = [], array $head = []) {
        extract($head);
        ob_start();
        include ROOT . "/views/Template.php";
        $template = ob_get_clean();
        extract($data);
        ob_start();
        include ROOT . "/views/$view.php";
        $view = ob_get_clean();
        http_response_code(200);
        header($this->contentType['html']);
        echo str_replace("{{content}}",$view, $template);
    }

    protected function returnStatusCode(int $statusCode, $additional = '')
    {
        if (strlen($additional) > 0) {
            $resp = [$statusCode => $this->statusCode[$statusCode], 'additional' => $additional];
        } else {
            $resp = [$statusCode => $this->statusCode[$statusCode]];
        }
        $this->returnJson($statusCode, $resp);
    }

    protected function retrunErrorView(int $errorCode = 404) {
        $this->renderView(
            view: 'Error',
            head: [
                'statusCode' => $errorCode,
                'message' => $this->statusCode[$errorCode]]
        );
    }
}