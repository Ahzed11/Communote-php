// Adapted from https://github.com/stimulus-components/stimulus-textarea-autogrow

import { Controller } from 'stimulus';
import { useDebounce } from "stimulus-use";

export default class extends Controller {

    static debounces = [ 'autogrow' ];

    connect() {
        useDebounce(this,  {
            wait: 400,
        });
    }

    autogrow () {
        this.element.style.height = 'auto' // Force re-print before calculating the scrollHeight value.
        this.element.style.height = `${this.element.scrollHeight}px`
    }
}
