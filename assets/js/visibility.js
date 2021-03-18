export default class Visibility {
    static toggle(e) {
        e.classList.contains('hidden') ? e.classList.remove('hidden') : e.classList.add('hidden');
    }

    static replace(e, a, b) {
        if (e.classList.contains(a)) {
            e.classList.remove(a);
        }
        e.classList.add(b);
    }

    static hide(e){
        e.classList.add('hidden');
    }
}
