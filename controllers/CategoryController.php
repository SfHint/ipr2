<?php
class CategoryController {
    private $model;
    public function __construct($model) {
        $this->model = $model;
    }

    public function index() {
        $data = $this->model->getAll();
        $this->json($data);
    }

    public function show($id) {
        $item = $this->model->getById($id);
        if (!$item) {
            $this->notFound('Category not found');
        }
        $this->json($item);
    }

    public function store($input) {
        $this->validate($input, ['name','slug']);
        $id = $this->model->create($input);
        http_response_code(201);
        $this->json(['message' => 'Created', 'id' => (int)$id]);
    }

    public function update($id, $input) {
        $this->validate($input, ['name','slug']);
        $exists = $this->model->getById($id);
        if (!$exists) $this->notFound('Category not found');
        $this->model->update($id, $input);
        $this->json(['message' => 'Updated']);
    }

    public function destroy($id) {
        $exists = $this->model->getById($id);
        if (!$exists) $this->notFound('Category not found');
        $this->model->delete($id);
        $this->json(['message' => 'Deleted']);
    }

    private function validate($input, $required) {
        foreach ($required as $field) {
            if (!isset($input[$field]) || trim($input[$field]) === '') {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Validation error', 'message' => "$field is required"]);
                exit;
            }
        }
    }

    private function notFound($message) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found', 'message' => $message]);
        exit;
    }

    private function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
