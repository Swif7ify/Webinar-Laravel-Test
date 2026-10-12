import gsap from 'gsap';

export function useAuthAnimations() {
    return {
        init() {
            const tl = gsap.timeline();
            
            // Stagger in the hero text elements slightly from below
            const heroChildren = document.querySelectorAll('.login-hero > *:not(.overview-card):not(.features-card)');
            if (heroChildren.length > 0) {
                tl.from(heroChildren, {
                    y: 20,
                    opacity: 0,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: 'power3.out'
                });
            }

            // Slide in the left card (Overview or Features) from below
            const leftCard = document.querySelector('.overview-card, .features-card');
            if (leftCard) {
                tl.from(leftCard, {
                    y: 40,
                    opacity: 0,
                    duration: 0.7,
                    ease: 'power3.out'
                }, "-=0.3");
            }

            // Slide in the main right-side auth card from the right
            const authCard = document.querySelector('.login-card');
            if (authCard) {
                tl.from(authCard, {
                    x: 60,
                    opacity: 0,
                    duration: 0.8,
                    ease: 'power3.out'
                }, "-=0.6");
            }

            // Animate the progress bar fill and text counter if they exist
            const progressFill = document.querySelector('.overview-card__progress-fill');
            const progressText = document.querySelector('.overview-card__progress-value');
            
            if (progressFill && progressText) {
                // Read the intended target from the text before we overwrite it
                const targetPercent = parseInt(progressText.innerText, 10) || 75;
                
                // Animate both the bar width and the text number synchronously
                let proxy = { percent: 0 };
                
                tl.to(proxy, {
                    percent: targetPercent,
                    duration: 1.5,
                    ease: "power3.out",
                    onUpdate: () => {
                        progressFill.style.width = proxy.percent + "%";
                        progressText.innerText = Math.round(proxy.percent) + "%";
                    }
                }, "-=0.4");
            }
        }
    };
}
