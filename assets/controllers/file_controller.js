import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = [ 'input', 'output' ];

    connect() {
        let placeholder = this.inputTarget.getAttribute('placeholder');

        if (placeholder !== '') {
            this.outputTarget.textContent = placeholder;
            this.colorize();
        }
    }

    updateCustomDiv() {
        const split = this.inputTarget.value.split('\\');
        this.outputTarget.textContent = split[split.length - 1];
        this.colorize();
    }

    colorize () {
        this.outputTarget.classList.add('text-purple-700');
        this.outputTarget.classList.add('dark:text-green-400');
    }
}
