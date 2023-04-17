<?php

namespace Utils;

use Error;
use DI;

class Router
{
    private function checkIsApi(string $uri): bool
    {
        return str_starts_with($uri, '/api/');
    }

    private function parseURI(string $uri): array
    {
        if ($this->checkIsApi($uri)) {
            $uriWithoutApi = str_replace('/api/', '', $uri);
            $uriWihoutQueryString = explode('?', $uriWithoutApi);
            $uriParts = explode('/', $uriWihoutQueryString[0]);

            /**
             * Actually, we've missed the method part here.
             * However, I doubt this functionality is required for this demo app, 
             * as we have only one method here.
             */

            if (count($uriParts) > 1) {
                throw new Error('API URL is malformed!');
            }

            return [
                'controller' => ucfirst($uriParts[0]),
                'isApi' => true,
            ];
        } else {
            throw new Error('You can access this website only via API');
        }
    }

    public function execute(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uriData = $this->parseURI($uri);

        if ($uriData['isApi']) {
            $controllerName = "\Controllers\\Api\\{$uriData['controller']}Controller";
            if (class_exists($controllerName)) {
                $controller = new $controllerName;
                $controller->execute();
            } else {
                throw new Error("Controller {$controllerName} not found!");
            }
        } else {
            throw new Error('Currently, we only support access via API.');
        }
    }
}
