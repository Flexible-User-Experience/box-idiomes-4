import { Controller } from '@hotwired/stimulus';
import { useTransition } from 'stimulus-use';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
    static targets = [ 'icon' ]
    lastKnownScrollYPosition = 0;
    ticking = false;

    connect()
    {
        document.addEventListener('scroll', this._onConnect.bind(this));
        useTransition(this, {
            element: this.iconTarget,
            enterActive: 'fade-enter-active',
            enterFrom: 'fade-enter-from',
            enterTo: 'fade-enter-to',
            leaveActive: 'fade-leave-active',
            leaveFrom: 'fade-leave-from',
            leaveTo: 'fade-leave-to',
            hiddenClass: 'd-none',
        });
    }

    clicked()
    {
        document.body.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
    }

    _onConnect()
    {
        this.lastKnownScrollYPosition = window.scrollY;
        if (!this.ticking) {
            window.requestAnimationFrame(() => {
                if (this.lastKnownScrollYPosition >= 150) {
                    this.enter();
                    this.ticking = true;
                } else {
                    this.leave();
                    this.ticking = false;
                }
            });
            this.ticking = true;
        }
    }
}
