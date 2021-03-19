import { Controller } from 'stimulus';
import Visibility from "../js/visibility";

export default class extends Controller {
    static targets = [ 'parent', 'button', 'dropdown' ];

    connect() {
        this.buttonTarget.addEventListener('click', () => {
            Visibility.toggle(this.dropdownTarget);
        })

        this.buttonTarget.addEventListener('mouseenter', () => {
            Visibility.replace(this.dropdownTarget, 'invisible', 'visible');
        })

        this.parentTarget.addEventListener('mouseleave', () => {
            Visibility.replace(this.dropdownTarget, 'visible', 'invisible');
        })
    }
}
