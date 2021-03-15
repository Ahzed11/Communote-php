export default class Visibility {
    static toggle(e) {
        e.classList.contains('hidden') ? e.classList.remove('hidden') : e.classList.add('hidden');
    }

    static hide(e){
        e.classList.add('hidden');
    }
}
