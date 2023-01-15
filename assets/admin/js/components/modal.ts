import * as delegate from 'delegate';

export default class Modal {
    public static current: Modal = null;
    public overlay: HTMLElement;
    public el: HTMLElement;

    constructor(el) {

        this.overlay = null;
        this.el = el;
    }

    static factory(): void {
        delegate('[data-action="modal-close"]', 'click', Modal.closeCurrentOpened);
        delegate('[data-action="modal-open"]', 'click', (e) => (new Modal(document.querySelector(e.target.dataset.modalTarget))).open());
    }

    static closeCurrentOpened(): void {
        if (Modal.isAnyOpen()) {
            Modal.current.close();
        }
    }

    static isAnyOpen = (): boolean => Modal.current !== null;

    open(): void {
        Modal.closeCurrentOpened();

        this.buildOverlay();
        setTimeout(() => {
            this.openOverlay();
            this.openModal();
        }, 1);

        document.addEventListener('keyup', this.handleKeyUp);

        Modal.current = this;
    }

    handleKeyUp = (e): void => {
        if (e.key === "Escape") {
            this.close();
        }
    };

    openOverlay(): void {
        this.overlay.classList.add('pwo-modal__overlay--active');
    }

    openModal(): void {
        document.querySelector('html').classList.add('pwo-modal--opened');
        this.el.classList.add('pwo-modal--active');
    }

    buildOverlay(): void {
        this.overlay = document.querySelector('.pwo-modal__overlay');
        if (this.overlay === null) {
            this.overlay = document.createElement('DIV');
            this.overlay.classList.add('pwo-modal__overlay');
            document.querySelector('body').append(this.overlay);
        }
    }

    close = (): void => {
        this.closeOverlay();
        this.closeModal();
        document.removeEventListener('keyup', this.handleKeyUp);
    };

    closeOverlay(): void {
        this.overlay.classList.remove('pwo-modal__overlay--active');

        setTimeout(() => {
            this.overlay.remove();
            this.el.dispatchEvent(new CustomEvent('modal-closed'));
        }, 1000);
    }

    closeModal = (): void => {
        document.querySelector('html').classList.remove('pwo-modal--opened');
        this.el.classList.remove('pwo-modal--active');
    }
}