export default class Visibility {
    static toggle(e) {
        if(e.classList.contains('invisible')){
            e.classList.remove('invisible');
            e.classList.add('visible');
        } else {
            e.classList.remove('visible');
            e.classList.add('invisible');
        }

    }

    static replace(e, a, b) {
        if (e.classList.contains(a)) {
            e.classList.remove(a);
        }
        e.classList.add(b);
    }

    static hide(e){
        e.classList.add('invisible');
    }
}
