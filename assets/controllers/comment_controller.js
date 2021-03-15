import { Controller } from 'stimulus';
import Visibility from "../js/visibility";

export default class extends Controller {
    connect() {
        let button = this.element.getElementsByTagName('button')[0];
        let pannel = this.element.getElementsByTagName('nav')[0];

        button.addEventListener('click', () => {
            Visibility.toggle(pannel);
        })

        pannel.addEventListener('mouseleave', () => {
            Visibility.hide(pannel);
        })
    }
}
