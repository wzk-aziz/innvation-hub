<?php
require_once 'vendor/autoload.php';

try {
    echo "Testing simplified TCPDF...\n";
    
    // Test with minimal configuration
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Disable header and footer to avoid font issues
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Test PDF Content', 0, 1, 'L');
    
    // Try to generate PDF
    $pdfContent = $pdf->Output('test.pdf', 'S');
    
    if (strlen($pdfContent) > 0) {
        echo "✓ PDF generation successful (" . strlen($pdfContent) . " bytes)\n";
        
        // Save to file for testing
        file_put_contents('test_output.pdf', $pdfContent);
        echo "✓ PDF saved as test_output.pdf\n";
    } else {
        echo "✗ PDF generation failed\n";
    }
    
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "✗ Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
