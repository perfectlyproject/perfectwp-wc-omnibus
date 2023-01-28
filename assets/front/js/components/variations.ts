import $ from 'jquery';

export default class Variations {
    static factory(): void {
        $(document).on( 'found_variation', 'form.cart', function( event, variation ) {
            Variations.showMessage(variation.variation_id);
        });
    }

    static showMessage(variation: number) {
        document.querySelectorAll(`[data-variation-id]`).forEach((el: HTMLElement) => {
            el.style.display = 'none';
        });
        document.querySelectorAll(`[data-variation-id="${variation}"]`).forEach((el: HTMLElement) => {
            el.style.display = 'block';
        });
    }
}