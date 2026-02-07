<?php
/**
 * H.A.C. Renovation - QuestionController
 */

class QuestionController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Listar preguntas
     */
    public function index()
    {
        $questions = Question::all();
        $services = Service::all();

        if (self::isApiRequest()) {
            Response::success('Questions retrieved', $questions);
        } else {
            Response::view('questions/index', [
                'questions' => $questions,
                'services' => $services
            ]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $services = Service::active();
        
        Response::view('questions/create', ['services' => $services]);
    }

    /**
     * Guardar nueva pregunta
     */
    public function store()
    {
        $data = [
            'service_id' => $_POST['service_id'] ?? '',
            'question_text' => $_POST['question_text'] ?? '',
            'question_type' => $_POST['question_type'] ?? 'text',
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'order' => $_POST['order'] ?? null,
            'form_position' => $_POST['form_position'] ?? 1,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Opciones para select/radio/checkbox
        if (in_array($data['question_type'], ['select', 'radio', 'checkbox'])) {
            $options = $_POST['options'] ?? [];
            if (is_string($options)) {
                $options = explode("\n", $options);
            }
            $data['options'] = array_filter(array_map('trim', $options));
        }

        // Traducciones
        $translations = [
            'fr' => $_POST['translation_fr'] ?? '',
            'es' => $_POST['translation_es'] ?? ''
        ];
        $data['translations'] = $translations;

        if (empty($data['service_id']) || empty($data['question_text'])) {
            if (self::isApiRequest()) {
                Response::error('Service and question text are required');
            } else {
                $_SESSION['error'] = 'Service and question text are required';
                Response::redirect('/questions/create');
            }
        }

        $id = Question::create($data);

        if (self::isApiRequest()) {
            Response::success('Question created', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Question created successfully';
            Response::redirect('/questions');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $question = Question::find($id);
        
        if (!$question) {
            Response::notFound('Question not found');
        }

        $services = Service::active();
        $translations = Question::getTranslations($question);

        Response::view('questions/edit', [
            'question' => $question,
            'services' => $services,
            'translations' => $translations
        ]);
    }

    /**
     * Actualizar pregunta
     */
    public function update($id)
    {
        $question = Question::find($id);
        
        if (!$question) {
            Response::notFound('Question not found');
        }

        $data = [
            'service_id' => $_POST['service_id'] ?? $question['service_id'],
            'question_text' => $_POST['question_text'] ?? $question['question_text'],
            'question_type' => $_POST['question_type'] ?? $question['question_type'],
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'order' => $_POST['order'] ?? $question['order'],
            'form_position' => $_POST['form_position'] ?? ($question['form_position'] ?? 1),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Opciones para select/radio/checkbox
        if (in_array($data['question_type'], ['select', 'radio', 'checkbox'])) {
            $options = $_POST['options'] ?? [];
            if (is_string($options)) {
                $options = explode("\n", $options);
            }
            $data['options'] = array_filter(array_map('trim', $options));
        } else {
            $data['options'] = null;
        }

        // Traducciones
        $translations = [
            'fr' => $_POST['translation_fr'] ?? '',
            'es' => $_POST['translation_es'] ?? ''
        ];
        $data['translations'] = $translations;

        Question::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Question updated');
        } else {
            $_SESSION['success'] = 'Question updated successfully';
            Response::redirect('/questions');
        }
    }

    /**
     * Eliminar pregunta
     */
    public function destroy($id)
    {
        $question = Question::find($id);
        
        if (!$question) {
            Response::notFound('Question not found');
        }

        Question::delete($id);

        if (self::isApiRequest()) {
            Response::success('Question deleted');
        } else {
            Response::redirect('/questions?deleted=1');
        }
    }

    /**
     * Verificar si es petición API
     */
    private static function isApiRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($requestUri, '/api/') !== false;
    }
}