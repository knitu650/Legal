// E-Signature Pad
const SignaturePad = {
    canvas: null,
    ctx: null,
    isDrawing: false,
    
    init: function(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        
        this.ctx = this.canvas.getContext('2d');
        this.setupEventListeners();
    },
    
    setupEventListeners: function() {
        this.canvas.addEventListener('mousedown', this.startDrawing.bind(this));
        this.canvas.addEventListener('mousemove', this.draw.bind(this));
        this.canvas.addEventListener('mouseup', this.stopDrawing.bind(this));
        this.canvas.addEventListener('mouseout', this.stopDrawing.bind(this));
        
        // Touch events
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            this.startDrawing(e.touches[0]);
        });
        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            this.draw(e.touches[0]);
        });
        this.canvas.addEventListener('touchend', this.stopDrawing.bind(this));
    },
    
    startDrawing: function(e) {
        this.isDrawing = true;
        const rect = this.canvas.getBoundingClientRect();
        this.ctx.beginPath();
        this.ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    },
    
    draw: function(e) {
        if (!this.isDrawing) return;
        
        const rect = this.canvas.getBoundingClientRect();
        this.ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        this.ctx.stroke();
    },
    
    stopDrawing: function() {
        this.isDrawing = false;
    },
    
    clear: function() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    },
    
    getDataURL: function() {
        return this.canvas.toDataURL();
    },
    
    save: function() {
        const signatureData = this.getDataURL();
        Ajax.post('/api/user/signature/save', { signature: signatureData },
            () => Notify.success('Signature saved successfully'),
            () => Notify.error('Failed to save signature')
        );
    }
};
