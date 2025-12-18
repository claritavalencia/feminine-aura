<?php

class Response {

    /**
     * Envoie une réponse JSON de succès
     * @param mixed $data Données à retourner
     * @param string $message Message de succès
     * @param int $statusCode Code HTTP
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200) {
        http_response_code($statusCode);

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    /**
     * Envoie une réponse JSON d'erreur
     * @param string $message Message d'erreur
     * @param int $statusCode Code HTTP
     * @param mixed $errors Détails des erreurs
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null) {
        http_response_code($statusCode);

        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    /**
     * Réponse pour ressource créée
     */
    public static function created($data = null, $message = 'Resource created successfully') {
        self::success($data, $message, 201);
    }

    /**
     * Réponse pour ressource non trouvée
     */
    public static function notFound($message = 'Resource not found') {
        self::error($message, 404);
    }

    /**
     * Réponse pour non autorisé
     */
    public static function unauthorized($message = 'Unauthorized') {
        self::error($message, 401);
    }

    /**
     * Réponse pour interdit
     */
    public static function forbidden($message = 'Forbidden') {
        self::error($message, 403);
    }

    /**
     * Réponse pour erreur de validation
     */
    public static function validationError($errors, $message = 'Validation error') {
        self::error($message, 422, $errors);
    }

    /**
     * Réponse pour erreur serveur
     */
    public static function serverError($message = 'Internal server error') {
        self::error($message, 500);
    }

    /**
     * Réponse pour méthode non autorisée
     */
    public static function methodNotAllowed($message = 'Method not allowed') {
        self::error($message, 405);
    }
}
