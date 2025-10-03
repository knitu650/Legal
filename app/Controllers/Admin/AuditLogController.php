<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogModel = new AuditLog();
        
        $userId = $this->request->query('user_id');
        $action = $this->request->query('action');
        $startDate = $this->request->query('start_date');
        $endDate = $this->request->query('end_date');
        
        $query = $auditLogModel;
        
        if ($userId) {
            $query = $query->where('user_id', $userId);
        }
        
        if ($action) {
            $query = $query->where('action', $action);
        }
        
        $logs = $query->orderBy('created_at', 'DESC')->limit(100)->get();
        
        $this->view('admin/audit-logs/index', [
            'pageTitle' => 'Audit Logs',
            'logs' => $logs
        ]);
    }
}
