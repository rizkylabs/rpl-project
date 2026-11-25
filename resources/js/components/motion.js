import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';

export function initMotion() {
    gsap.registerPlugin(ScrollTrigger);
    const media = gsap.matchMedia();
    media.add('(prefers-reduced-motion: no-preference)', () => {
        gsap.fromTo('[data-enter]', { y: 10 }, { y: 0, duration: .45, ease: 'power2.out', clearProps: 'transform' });
        document.querySelectorAll('[data-reveal]').forEach(element => {
            // Keep the layout intact. Only the visual state is animated.
            const tween = gsap.fromTo(element, { y: 16, opacity: 0 }, {
                y: 0, opacity: 1, duration: .38, ease: 'power2.out', paused: true,
            });
            const shouldShow = trigger => trigger.isActive || ScrollTrigger.maxScroll(window) <= 1;
            const sync = trigger => tween.progress(shouldShow(trigger) ? 1 : 0).pause();
            const trigger = ScrollTrigger.create({
                trigger: element,
                start: 'top 92%',
                end: 'bottom 8%',
                onToggle: self => {
                    if (shouldShow(self)) tween.play();
                    else tween.reverse();
                },
                onRefresh: sync,
            });
            sync(trigger);
        });
        // GSAP matchMedia reverts its animations and triggers on preference changes.
    });
    media.add('(prefers-reduced-motion: no-preference) and (pointer: fine)', () => {
        const lenis = new Lenis({ autoRaf: true, smoothWheel: true, syncTouch: false });
        lenis.on('scroll', ScrollTrigger.update);
        return () => lenis.destroy();
    });
}
