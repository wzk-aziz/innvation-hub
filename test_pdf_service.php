<?php
require_once 'vendor/autoload.php';
require_once 'app/Services/PdfService.php';

// Mock data to test the actual PdfService
$mockIdea = [
    'id' => 1,
    'title' => 'Test Idea for PDF',
    'description' => 'This is a test description for the PDF generation functionality.',
    'expected_impact' => 'High impact expected',
    'required_resources' => 'Minimal resources needed',
    'status' => 'submitted',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'theme_name' => 'Innovation',
    'created_at' => '2025-09-01 10:00:00'
];

$mockEvaluations = [
    [
        'score' => 8,
        'comment' => 'Great idea with good potential',
        'evaluator_name' => 'Jane Smith',
        'created_at' => '2025-09-01 11:00:00'
    ]
];

$mockFeedback = [
    [
        'comment' => 'Admin feedback: Please provide more details',
        'admin_name' => 'Admin User',
        'created_at' => '2025-09-01 12:00:00'
    ]
];

try {
    echo "Testing PDF Service with actual data structure...\n";
    
    $pdfService = new \App\Services\PdfService();
    $pdf = $pdfService->generateIdeaPdf($mockIdea, $mockEvaluations, $mockFeedback);
    
    // Generate PDF content
    $pdfContent = $pdf->Output('', 'S');
    
    if (strlen($pdfContent) > 0) {
        echo "✓ PDF Service generation successful (" . strlen($pdfContent) . " bytes)\n";
        
        // Save the test PDF
        file_put_contents('test_service_output.pdf', $pdfContent);
        echo "✓ PDF saved as test_service_output.pdf\n";
        echo "✓ PDF export is working correctly!\n";
    } else {
        echo "✗ PDF Service generation failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Exception in PDF Service: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "✗ Fatal Error in PDF Service: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
