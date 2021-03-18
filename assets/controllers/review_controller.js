import { Controller } from 'stimulus';
import Visibility from "../js/visibility";

export default class extends Controller {
    connect() {
        let stars = this.element.getElementsByClassName('fa-star');
        let target = this.element.getElementsByTagName('span')[0];
        let isSelected = false;

        for (let i=0; i < stars.length; i++) {
            stars[i].addEventListener('mouseover', () => {
                if (!isSelected) {
                    for (let j=0; j < stars.length; j++) {
                        if (j <= i) {
                            Visibility.replace(stars[j], 'far', 'fas');
                        } else {
                            Visibility.replace(stars[j], 'fas', 'far');
                        }
                    }
                }
            })

            stars[i].addEventListener('click', () => {
                for (let j=0; j < stars.length; j++) {
                    if (j <= i) {
                        Visibility.replace(stars[j], 'far', 'fas');
                    } else {
                        Visibility.replace(stars[j], 'fas', 'far');
                    }
                    isSelected = true;
                    fetch(target.textContent + '?score=' + (i+1)).then((response) => {
                        if (!response.ok) {
                            isSelected = false;
                        }
                    })
                }
            })
        }
    }
}
