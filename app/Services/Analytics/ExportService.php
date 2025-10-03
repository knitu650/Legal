<?php

namespace App\Services\Analytics;

class ExportService
{
    public function exportToExcel($data, $filename = 'export.xls')
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $this->generateExcelContent($data);
        exit;
    }
    
    public function exportToPDF($data, $filename = 'export.pdf')
    {
        // Use PDF library to generate
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Generate PDF
        exit;
    }
    
    public function exportToCSV($data, $filename = 'export.csv')
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Write headers
        if (!empty($data) && is_array($data[0])) {
            fputcsv($output, array_keys($data[0]));
        }
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    protected function generateExcelContent($data)
    {
        $html = '<table border="1">';
        
        if (!empty($data) && is_array($data[0])) {
            $html .= '<tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr>';
        }
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
}
