export function useCountdown(dueDateString) {
    return {
        countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
        timer: null,

        init() {
            if (!dueDateString) {
                this.countdown = { days: '--', hours: '--', minutes: '--', seconds: '--' };
                return;
            }
            
            const due = new Date(dueDateString).getTime();
            
            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = due - now;
                
                if (distance < 0) {
                    if (this.timer) clearInterval(this.timer);
                    this.countdown = { days: '--', hours: '--', minutes: '--', seconds: '--' };
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

        destroy() {
            if (this.timer) clearInterval(this.timer);
        }
    };
}
