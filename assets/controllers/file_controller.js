import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = [ 'input', 'output' ];

    updateCustomDiv() {
        const split = this.inputTarget.value.split('\\');
        this.outputTarget.textContent = split[split.length - 1];
        this.outputTarget.classList.add('text-purple-700');
        this.outputTarget.classList.add('dark:text-green-400');
    }
}
