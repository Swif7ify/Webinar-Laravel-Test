export function useTaskDetails() {
    return {
        task: null,
        loading: false,
        countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
        timer: null,

        async openModal(taskId) {
            this.loading = true;
            this.task = null;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'task-details-modal' }));

            try {
                const response = await fetch(`/student/assignments/${taskId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch task details');
                }

                this.task = await response.json();
                this.startTimer();
            } catch (error) {
                console.error('Error fetching task details:', error);
                if (window.useToast) {
                    window.useToast().error('Could not load assignment details. Please try again.');
                }
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'task-details-modal' }));
            } finally {
                this.loading = false;
            }
        },

        closeModal() {
            if (this.timer) clearInterval(this.timer);
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'task-details-modal' }));
        },

        startTimer() {
            if (this.timer) clearInterval(this.timer);
            if (!this.task || !this.task.due_date) return;
            
            const due = new Date(this.task.due_date).getTime();
            
            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = due - now;
                
                if (distance < 0) {
                    clearInterval(this.timer);
                    this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                    return;
                }
                
                this.countdown = {
                    days: String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0'),
                    hours: String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0'),
                    minutes: String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0'),
                    seconds: String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0')
                };
            };
            
            updateTimer();
            this.timer = setInterval(updateTimer, 1000);
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric', 
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
            });
        }
    };
}
