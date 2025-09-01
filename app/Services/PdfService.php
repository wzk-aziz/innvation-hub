<?php

declare(strict_types=1);

namespace App\Services;

use TCPDF;

/**
 * PDF Service
 * Handles PDF generation for ideas
 */
class PdfService
{
    /**
     * Generate PDF for an idea
     */
    public function generateIdeaPdf($idea, $evaluations = [], $feedback = [])
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Innovation Hub');
        $pdf->SetAuthor('Innovation Hub');
        $pdf->SetTitle('Idée: ' . $idea['title']);
        $pdf->SetSubject('Fiche Idée');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'Innovation Hub', 'Fiche Idée Détaillée');

        // Set header and footer fonts
        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 11);

        // Calculate average score
        $averageScore = null;
        if (!empty($evaluations)) {
            $totalScore = 0;
            foreach ($evaluations as $evaluation) {
                $totalScore += $evaluation['score'];
            }
            $averageScore = $totalScore / count($evaluations);
        }

        // Build the HTML content
        $html = $this->buildPdfContent($idea, $evaluations, $feedback, $averageScore);

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf;
    }

    /**
     * Build the PDF content HTML
     */
    private function buildPdfContent($idea, $evaluations, $feedback, $averageScore)
    {
        $html = '
        <style>
            h1 { color: #2c5282; font-size: 18px; margin-bottom: 10px; }
            h2 { color: #3182ce; font-size: 14px; margin-top: 15px; margin-bottom: 8px; }
            h3 { color: #4299e1; font-size: 12px; margin-top: 10px; margin-bottom: 5px; }
            .meta-info { background-color: #f7fafc; padding: 10px; margin-bottom: 15px; }
            .meta-item { margin-bottom: 5px; }
            .status { font-weight: bold; padding: 3px 8px; border-radius: 3px; }
            .status-submitted { background-color: #fef5e7; color: #d69e2e; }
            .status-under_review { background-color: #e6fffa; color: #319795; }
            .status-accepted { background-color: #f0fff4; color: #38a169; }
            .status-rejected { background-color: #fed7d7; color: #e53e3e; }
            .evaluation-item { border: 1px solid #e2e8f0; padding: 8px; margin-bottom: 8px; }
            .feedback-item { border: 1px solid #e2e8f0; padding: 8px; margin-bottom: 8px; background-color: #f9f9f9; }
            .score { font-weight: bold; color: #d69e2e; }
        </style>

        <h1>' . htmlspecialchars($idea['title']) . '</h1>

        <div class="meta-info">
            <div class="meta-item"><strong>Auteur:</strong> ' . htmlspecialchars($idea['first_name'] . ' ' . $idea['last_name']) . '</div>
            <div class="meta-item"><strong>Email:</strong> ' . htmlspecialchars($idea['email']) . '</div>
            <div class="meta-item"><strong>Thématique:</strong> ' . htmlspecialchars($idea['theme_name'] ?? 'N/A') . '</div>
            <div class="meta-item"><strong>Statut:</strong> <span class="status status-' . $idea['status'] . '">' . $this->getStatusText($idea['status']) . '</span></div>
            <div class="meta-item"><strong>Soumise le:</strong> ' . date('d/m/Y à H:i', strtotime($idea['created_at'])) . '</div>';
            
        if (!empty($idea['updated_at']) && $idea['updated_at'] !== $idea['created_at']) {
            $html .= '<div class="meta-item"><strong>Modifiée le:</strong> ' . date('d/m/Y à H:i', strtotime($idea['updated_at'])) . '</div>';
        }
        
        $html .= '
        </div>

        <h2>Description</h2>
        <p>' . nl2br(htmlspecialchars($idea['description'])) . '</p>';

        if (!empty($idea['expected_impact'])) {
            $html .= '
            <h2>Impact Attendu</h2>
            <p>' . nl2br(htmlspecialchars($idea['expected_impact'])) . '</p>';
        }

        if (!empty($idea['required_resources'])) {
            $html .= '
            <h2>Ressources Nécessaires</h2>
            <p>' . nl2br(htmlspecialchars($idea['required_resources'])) . '</p>';
        }

        // Evaluations section
        if (!empty($evaluations)) {
            $html .= '<h2>Évaluations (' . count($evaluations) . ')';
            if ($averageScore !== null) {
                $html .= ' - Moyenne: <span class="score">' . number_format($averageScore, 1) . '/5</span>';
            }
            $html .= '</h2>';

            foreach ($evaluations as $evaluation) {
                $html .= '
                <div class="evaluation-item">
                    <div><strong>Évaluateur:</strong> ' . htmlspecialchars($evaluation['evaluator_first_name'] . ' ' . $evaluation['evaluator_last_name']) . '</div>
                    <div><strong>Score:</strong> <span class="score">' . $evaluation['score'] . '/5</span></div>
                    <div><strong>Date:</strong> ' . date('d/m/Y à H:i', strtotime($evaluation['created_at'])) . '</div>';
                
                if (!empty($evaluation['comment'])) {
                    $html .= '<div><strong>Commentaire:</strong> ' . nl2br(htmlspecialchars($evaluation['comment'])) . '</div>';
                }
                
                $html .= '</div>';
            }
        } else {
            $html .= '<h2>Évaluations</h2><p>Aucune évaluation pour le moment.</p>';
        }

        // Feedback section
        if (!empty($feedback)) {
            $html .= '<h2>Retours de l\'Administration (' . count($feedback) . ')</h2>';

            foreach ($feedback as $feedbackItem) {
                $html .= '
                <div class="feedback-item">
                    <div><strong>Administrateur:</strong> ' . htmlspecialchars($feedbackItem['first_name'] . ' ' . $feedbackItem['last_name']) . '</div>
                    <div><strong>Date:</strong> ' . date('d/m/Y à H:i', strtotime($feedbackItem['created_at'])) . '</div>
                    <div><strong>Message:</strong> ' . nl2br(htmlspecialchars($feedbackItem['message'])) . '</div>
                </div>';
            }
        } else {
            $html .= '<h2>Retours de l\'Administration</h2><p>Aucun retour pour le moment.</p>';
        }

        return $html;
    }

    /**
     * Get status text in French
     */
    private function getStatusText($status)
    {
        return match($status) {
            'submitted' => 'Soumise',
            'under_review' => 'En révision',
            'accepted' => 'Acceptée',
            'rejected' => 'Rejetée',
            default => ucfirst($status)
        };
    }
}
