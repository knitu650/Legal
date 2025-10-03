#!/bin/bash

# Script to create all public assets files
# This creates CSS and JS files for all panels

# User Panel CSS
cat > public/assets/css/user/documents.css << 'EOF'
.documents-table-wrapper {
    background-color: var(--bg-primary);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.documents-toolbar {
    padding: var(--spacing-lg);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.document-row {
    display: flex;
    align-items: center;
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
    transition: background-color var(--transition-base);
}

.document-row:hover {
    background-color: var(--bg-secondary);
}

.document-status {
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
}

.status-draft { background-color: var(--warning-bg); color: var(--warning-color); }
.status-completed { background-color: var(--success-bg); color: var(--success-color); }
.status-signed { background-color: var(--info-bg); color: var(--info-color); }
EOF

cat > public/assets/css/user/profile.css << 'EOF'
.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    padding: 3rem 2rem;
    border-radius: var(--radius-lg);
    text-align: center;
    color: white;
    margin-bottom: var(--spacing-xl);
}

.profile-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: var(--radius-full);
    background-color: white;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: var(--font-weight-bold);
    margin: 0 auto 1rem;
}

.profile-section {
    background-color: var(--bg-primary);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: var(--spacing-lg);
}
EOF

# Admin Panel CSS
cat > public/assets/css/admin/dashboard.css << 'EOF'
.admin-dashboard {
    padding: var(--spacing-xl);
}

.metrics-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.metric-card {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.metric-icon {
    font-size: 2.5rem;
    opacity: 0.8;
    margin-bottom: var(--spacing-sm);
}

.metric-value {
    font-size: var(--font-size-3xl);
    font-weight: var(--font-weight-bold);
}

.metric-label {
    font-size: var(--font-size-sm);
    opacity: 0.9;
}
EOF

cat > public/assets/css/admin/tables.css << 'EOF'
.data-table-container {
    background-color: var(--bg-primary);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.table-header {
    padding: var(--spacing-lg);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.action-icon {
    padding: 0.5rem;
    cursor: pointer;
    color: var(--text-secondary);
    transition: color var(--transition-base);
}

.action-icon:hover {
    color: var(--primary-color);
}
EOF

cat > public/assets/css/admin/forms.css << 'EOF'
.form-container {
    max-width: 600px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
}

.input-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-tertiary);
}

.has-icon .form-control {
    padding-left: 2.5rem;
}
EOF

# MIS Panel CSS
cat > public/assets/css/mis/dashboard.css << 'EOF'
.mis-dashboard {
    padding: var(--spacing-xl);
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.kpi-card {
    background-color: var(--bg-primary);
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--primary-color);
    box-shadow: var(--shadow-sm);
}
EOF

cat > public/assets/css/mis/reports.css << 'EOF'
.report-filters {
    background-color: var(--bg-primary);
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-lg);
}

.date-range-picker {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.report-preview {
    background-color: var(--bg-primary);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    min-height: 400px;
}
EOF

cat > public/assets/css/mis/charts.css << 'EOF'
.chart-container {
    background-color: var(--bg-primary);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.chart-header {
    margin-bottom: var(--spacing-lg);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-canvas {
    max-height: 400px;
}
EOF

# Franchise Panel CSS
cat > public/assets/css/franchise/dashboard.css << 'EOF'
.franchise-dashboard {
    padding: var(--spacing-xl);
}

.performance-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.performance-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border-top: 3px solid var(--primary-color);
}
EOF

echo "All CSS files created successfully!"
