import { Controller } from 'stimulus';
import { useDebounce } from "stimulus-use";

function createMenuItem(content) {
    let li = document.createElement('li');
    li.textContent = content;
    li.classList.add('text-black')
    li.classList.add('dark:text-white')
    li.classList.add('p-2')
    li.classList.add('cursor-pointer')
    li.classList.add('hover:text-purple-700')
    li.classList.add('dark:hover:text-green-400')
    return li;
}

function removeChildren(parent) {
    while (parent.lastChild) {
        parent.removeChild(parent.lastChild);
    }
}

export default class extends Controller {
    static debounces = [ 'fetch' ];
    static targets = [ 'input', 'output', 'target' ];

    connect() {
        useDebounce(this,  {
            wait: 400,
        });
    }

    fetch() {
        removeChildren(this.outputTarget);

        const target = this.targetTarget.textContent;
        const term = this.inputTarget.value;
        const path = target + '?term=' + term;

        if (this.inputTarget.value === '') {
            return;
        }

        fetch(path)
            .then(response => response.json())
            .then(data => {
                data.forEach((e) => {
                    this.outputTarget.appendChild(createMenuItem(e.code + ' - ' + e.title));
                })
            }).then(() => {
                for(let item of this.outputTarget.childNodes) {
                    item.addEventListener('click', () => {
                        const content = item.textContent;
                        this.inputTarget.value = content;
                        removeChildren(this.outputTarget);
                    })
                }
            });
    }
}
