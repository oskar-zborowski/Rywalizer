import Content from './layout/Content/Content';
import Footer from './layout/Footer/Footer';
import Topbar from './layout/Topbar/Topbar';
import './main.scss';

class Main {

    public constructor() {
        const root = $('#app');

        root.append([
            new Topbar().vdom,
            new Content().vdom,
            new Footer().vdom
        ]);
    }

}

new Main();