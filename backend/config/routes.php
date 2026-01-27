<?php
/**
 * H.A.C. Renovation - Definición de Rutas
 */

return [
    // Autenticación
    'GET:/login' => ['AuthController', 'showLogin'],
    'POST:/login' => ['AuthController', 'login'],
    'GET:/logout' => ['AuthController', 'logout'],
    
    // Dashboard
    'GET:/' => ['AdminController', 'dashboard'],
    'GET:/dashboard' => ['AdminController', 'dashboard'],
    
    // Clientes
    'GET:/clients' => ['ClientController', 'index'],
    'GET:/clients/create' => ['ClientController', 'create'],
    'POST:/clients' => ['ClientController', 'store'],
    'GET:/clients/:id' => ['ClientController', 'show'],
    'GET:/clients/:id/edit' => ['ClientController', 'edit'],
    'PUT:/clients/:id' => ['ClientController', 'update'],
    'DELETE:/clients/:id' => ['ClientController', 'destroy'],
    
    // Cotizaciones
    'GET:/quotes' => ['QuoteController', 'index'],
    'GET:/quotes/create' => ['QuoteController', 'create'],
    'POST:/quotes' => ['QuoteController', 'store'],
    'GET:/quotes/:id' => ['QuoteController', 'show'],
    'GET:/quotes/:id/edit' => ['QuoteController', 'edit'],
    'PUT:/quotes/:id' => ['QuoteController', 'update'],
    'DELETE:/quotes/:id' => ['QuoteController', 'destroy'],
    'POST:/quotes/:id/send' => ['QuoteController', 'send'],
    
    // Proyectos
    'GET:/projects' => ['ProjectController', 'index'],
    'GET:/projects/:id' => ['ProjectController', 'show'],
    'POST:/projects' => ['ProjectController', 'store'],
    'PUT:/projects/:id' => ['ProjectController', 'update'],
    
    // Preguntas
    'GET:/questions' => ['QuestionController', 'index'],
    'GET:/questions/create' => ['QuestionController', 'create'],
    'POST:/questions' => ['QuestionController', 'store'],
    'GET:/questions/:id/edit' => ['QuestionController', 'edit'],
    'PUT:/questions/:id' => ['QuestionController', 'update'],
    'DELETE:/questions/:id' => ['QuestionController', 'destroy'],
    
    // Servicios
    'GET:/services' => ['ServiceController', 'index'],
    'GET:/services/create' => ['ServiceController', 'create'],
    'POST:/services' => ['ServiceController', 'store'],
    'GET:/services/:id/edit' => ['ServiceController', 'edit'],
    'PUT:/services/:id' => ['ServiceController', 'update'],
    'DELETE:/services/:id' => ['ServiceController', 'destroy'],
    
    // Empresas
    'GET:/companies' => ['CompanyController', 'index'],
    'GET:/companies/create' => ['CompanyController', 'create'],
    'POST:/companies' => ['CompanyController', 'store'],
    'GET:/companies/:id' => ['CompanyController', 'edit'],
    'GET:/companies/:id/edit' => ['CompanyController', 'edit'],
    'POST:/companies/:id' => ['CompanyController', 'update'], // Maneja POST sin _method
    'PUT:/companies/:id' => ['CompanyController', 'update'],
    'DELETE:/companies/:id' => ['CompanyController', 'destroy'],
    
    // Reportes
    'GET:/reports' => ['ReportController', 'index'],
    'GET:/reports/quotes' => ['ReportController', 'quotes'],
    'GET:/reports/projects' => ['ReportController', 'projects'],
];